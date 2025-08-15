<?php

class VariantModel
{
    /** @var Database */
    private $db;

    public function __construct()
    {
        // $this->db->pdo là PDO đã cấu hình
        $this->db = new Database();
    }

    /* =========================================================
     * ===============        BASIC QUERIES        =============
     * =======================================================*/

    /**
     * Lấy danh sách biến thể, có filter theo product_id, tồn kho tối thiểu, phân trang.
     *
     * Schema cột productvariants: id, product_id, price, sale_price, quantity, image_url, ... 
     * (tham chiếu schema) :contentReference[oaicite:0]{index=0}
     */

    // models/admin/VariantModel.php

    public function listWithDetails(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $sql = "
        SELECT 
            v.*,
            p.name AS product_name,
            p.image_thumbnail AS product_thumbnail,
            c.name AS category_name,
            -- attribute_id: 1 = Màu Sắc, 2 = Kích Thước
            GROUP_CONCAT(DISTINCT CASE WHEN av.attribute_id = 1 THEN av.value END ORDER BY av.value SEPARATOR ', ') AS color_names,
            GROUP_CONCAT(DISTINCT CASE WHEN av.attribute_id = 2 THEN av.value END ORDER BY av.value SEPARATOR ', ') AS size_names,
            (SELECT i.image_url FROM productimages i WHERE i.variant_id = v.id ORDER BY i.id ASC LIMIT 1) AS thumbnail_display,
            CASE 
                WHEN v.sale_price IS NOT NULL AND v.sale_price > 0 THEN v.sale_price
                ELSE v.price
            END AS effective_price
        FROM productvariants v
        INNER JOIN products p       ON p.id = v.product_id
        LEFT  JOIN categories c     ON c.id = p.category_id
        LEFT  JOIN productvariantvalues pv ON pv.variant_id = v.id
        LEFT  JOIN attributevalues av      ON av.id = pv.value_id
        WHERE 1=1
    ";

        $params = [];
        if (!empty($filters['product_id'])) {
            $sql .= " AND v.product_id = :product_id";
            $params[':product_id'] = (int)$filters['product_id'];
        }
        if (isset($filters['min_quantity'])) {
            $sql .= " AND v.quantity >= :min_qty";
            $params[':min_qty'] = (int)$filters['min_quantity'];
        }

        $sql .= "
        GROUP BY v.id
        ORDER BY v.id DESC
        LIMIT :limit OFFSET :offset
    ";

        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $this->db->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['variant_name']  = $r['variant_name'] ?? ('Biến thể #' . (int)$r['id']);
            $r['price_display'] = number_format((float)$r['effective_price'], 0, ',', '.') . 'đ';
            $r['color_list']    = $r['color_names'] ? array_map('trim', explode(',', $r['color_names'])) : [];
            $r['size_list']     = $r['size_names'] ? array_map('trim', explode(',', $r['size_names'])) : [];
            if (empty($r['thumbnail_display'])) {
                $r['thumbnail_display'] = $r['image_url'] ?? $r['product_thumbnail'] ?? './assets/admin/assets/images/placeholder.png';
            }
        }
        return $rows;
    }

    public function getDetail(int $variantId): ?array
    {
        $sql = "
        SELECT 
            v.*,
            p.name AS product_name,
            p.image_thumbnail AS product_thumbnail,
            c.name AS category_name,
            GROUP_CONCAT(DISTINCT CASE WHEN av.attribute_id = 1 THEN av.value END ORDER BY av.value SEPARATOR ', ') AS color_names,
            GROUP_CONCAT(DISTINCT CASE WHEN av.attribute_id = 2 THEN av.value END ORDER BY av.value SEPARATOR ', ') AS size_names,
            (SELECT i.image_url FROM productimages i WHERE i.variant_id = v.id ORDER BY i.id ASC LIMIT 1) AS thumbnail_display,
            CASE 
                WHEN v.sale_price IS NOT NULL AND v.sale_price > 0 THEN v.sale_price
                ELSE v.price
            END AS effective_price
        FROM productvariants v
        INNER JOIN products p       ON p.id = v.product_id
        LEFT  JOIN categories c     ON c.id = p.category_id
        LEFT  JOIN productvariantvalues pv ON pv.variant_id = v.id
        LEFT  JOIN attributevalues av      ON av.id = pv.value_id
        WHERE v.id = :id
        GROUP BY v.id
        LIMIT 1
    ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) return null;

        $r['variant_name']  = $r['variant_name'] ?? ('Biến thể #' . (int)$r['id']);
        $r['price_display'] = number_format((float)$r['effective_price'], 0, ',', '.') . 'đ';
        $r['color_list']    = $r['color_names'] ? array_map('trim', explode(',', $r['color_names'])) : [];
        $r['size_list']     = $r['size_names'] ? array_map('trim', explode(',', $r['size_names'])) : [];
        if (empty($r['thumbnail_display'])) {
            $r['thumbnail_display'] = $r['image_url'] ?? $r['product_thumbnail'] ?? './assets/admin/assets/images/placeholder.png';
        }
        return $r;
    }

    public function list(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $sql = "SELECT v.*
                FROM productvariants v
                WHERE 1=1";
        $params = [];

        if (!empty($filters['product_id'])) {
            $sql .= " AND v.product_id = :product_id";
            $params[':product_id'] = (int)$filters['product_id'];
        }
        if (isset($filters['min_quantity'])) {
            $sql .= " AND v.quantity >= :min_qty";
            $params[':min_qty'] = (int)$filters['min_quantity'];
        }

        // phân trang
        $offset = max(0, ($page - 1) * $perPage);
        $sql .= " ORDER BY v.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết 1 biến thể + các giá trị thuộc tính đi kèm (màu/size, ...).
     * productvariantvalues(variant_id, value_id) → attributevalues(id, attribute_id, value, color_code)
     * (tham chiếu bảng liên quan) :contentReference[oaicite:1]{index=1} :contentReference[oaicite:2]{index=2}
     */
    public function getById(int $variantId): ?array
    {
        $sql = "SELECT v.*
                FROM productvariants v
                WHERE v.id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $variant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$variant) {
            return null;
        }

        // Lấy danh sách giá trị thuộc tính (value + thuộc tính cha)
        $sqlAttr = "SELECT pv.value_id,
                           av.value,
                           av.color_code,
                           av.attribute_id
                    FROM productvariantvalues pv
                    INNER JOIN attributevalues av ON av.id = pv.value_id
                    WHERE pv.variant_id = :vid";
        $stmt = $this->db->pdo->prepare($sqlAttr);
        $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $variant['attribute_values'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy ảnh của biến thể
        // productimages có FK CASCADE về productvariants (xóa biến thể sẽ xóa ảnh) :contentReference[oaicite:3]{index=3} :contentReference[oaicite:4]{index=4}
        $sqlImg = "SELECT id, image_url, created_at
                   FROM productimages
                   WHERE variant_id = :vid
                   ORDER BY id ASC";
        $stmt = $this->db->pdo->prepare($sqlImg);
        $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $variant['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $variant;
    }

    /**
     * Lấy tất cả biến thể theo product_id kèm thuộc tính và số ảnh.
     * productvariants có FK tới products ON DELETE CASCADE (tham chiếu) :contentReference[oaicite:5]{index=5} :contentReference[oaicite:6]{index=6}
     */
    public function getByProduct(int $productId): array
    {
        $sql = "SELECT v.*,
                       (SELECT COUNT(1) FROM productimages i WHERE i.variant_id = v.id) AS image_count
                FROM productvariants v
                WHERE v.product_id = :pid
                ORDER BY v.id DESC";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Gắn attribute values cho từng biến thể (1 query/biến thể – đủ dùng bản đầu)
        $sqlAttr = "SELECT pv.value_id, av.value, av.color_code, av.attribute_id
                    FROM productvariantvalues pv
                    INNER JOIN attributevalues av ON av.id = pv.value_id
                    WHERE pv.variant_id = :vid";
        $stmtAttr = $this->db->pdo->prepare($sqlAttr);
        foreach ($variants as &$v) {
            $stmtAttr->bindValue(':vid', (int)$v['id'], PDO::PARAM_INT);
            $stmtAttr->execute();
            $v['attribute_values'] = $stmtAttr->fetchAll(PDO::FETCH_ASSOC);
            $stmtAttr->closeCursor();
        }
        return $variants;
    }

    /* =========================================================
     * ===============             CRUD             ============
     * =======================================================*/

    /**
     * Tạo mới biến thể + gán các value_id (màu/size, ...).
     * - Bảng productvariants có AUTO_INCREMENT id. :contentReference[oaicite:7]{index=7}
     * - Bảng productvariantvalues có PK (variant_id, value_id). :contentReference[oaicite:8]{index=8}
     */
    public function create(array $data, array $valueIds = []): int
    {
        // $data: product_id, price, sale_price (nullable), quantity, image_url (nullable)
        $this->db->pdo->beginTransaction();
        try {
            $sql = "INSERT INTO productvariants (product_id, price, sale_price, quantity, image_url)
                    VALUES (:product_id, :price, :sale_price, :quantity, :image_url)";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':product_id', (int)$data['product_id'], PDO::PARAM_INT);
            $stmt->bindValue(':price', $data['price']);
            $stmt->bindValue(':sale_price', $data['sale_price'] ?? null);
            $stmt->bindValue(':quantity', (int)($data['quantity'] ?? 0), PDO::PARAM_INT);
            $stmt->bindValue(':image_url', $data['image_url'] ?? null);
            $stmt->execute();

            $variantId = (int)$this->db->pdo->lastInsertId();

            if (!empty($valueIds)) {
                $ins = $this->db->pdo->prepare(
                    "INSERT INTO productvariantvalues (variant_id, value_id) VALUES (:vid, :val)"
                );
                foreach (array_unique($valueIds) as $val) {
                    $ins->bindValue(':vid', $variantId, PDO::PARAM_INT);
                    $ins->bindValue(':val', (int)$val, PDO::PARAM_INT);
                    $ins->execute();
                }
            }

            $this->db->pdo->commit();
            return $variantId;
        } catch (Throwable $e) {
            $this->db->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Cập nhật biến thể. Có thể truyền mảng $valueIds để thay thế toàn bộ giá trị thuộc tính.
     */
    public function update(int $variantId, array $data, ?array $valueIds = null): bool
    {
        $this->db->pdo->beginTransaction();
        try {
            $fields = [];
            $params = [':id' => $variantId];

            foreach (['product_id', 'price', 'sale_price', 'quantity', 'image_url'] as $col) {
                if (array_key_exists($col, $data)) {
                    $fields[] = "$col = :$col";
                    $params[":$col"] = $data[$col];
                }
            }

            if ($fields) {
                $sql = "UPDATE productvariants SET " . implode(', ', $fields) . " WHERE id = :id";
                $stmt = $this->db->pdo->prepare($sql);
                foreach ($params as $k => $v) {
                    $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
                    $stmt->bindValue($k, $v, $type);
                }
                $stmt->execute();
            }

            if (is_array($valueIds)) {
                // Replace toàn bộ map (FK CASCADE của productvariantvalues theo variant_id) :contentReference[oaicite:9]{index=9}
                $del = $this->db->pdo->prepare("DELETE FROM productvariantvalues WHERE variant_id = :vid");
                $del->bindValue(':vid', $variantId, PDO::PARAM_INT);
                $del->execute();

                if (!empty($valueIds)) {
                    $ins = $this->db->pdo->prepare(
                        "INSERT INTO productvariantvalues (variant_id, value_id) VALUES (:vid, :val)"
                    );
                    foreach (array_unique($valueIds) as $val) {
                        $ins->bindValue(':vid', $variantId, PDO::PARAM_INT);
                        $ins->bindValue(':val', (int)$val, PDO::PARAM_INT);
                        $ins->execute();
                    }
                }
            }

            $this->db->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Xoá biến thể.
     * - productimages: ON DELETE CASCADE → ảnh xoá theo biến thể. :contentReference[oaicite:10]{index=10}
     * - productvariantvalues: ON DELETE CASCADE → map thuộc tính xoá theo. :contentReference[oaicite:11]{index=11}
     * - orderitems.variant_id: ON DELETE SET NULL → giữ lịch sử đơn hàng. :contentReference[oaicite:12]{index=12} :contentReference[oaicite:13]{index=13}
     */
    public function delete(int $variantId): bool
    {
        $stmt = $this->db->pdo->prepare("DELETE FROM productvariants WHERE id = :id");
        $stmt->bindValue(':id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /* =========================================================
     * ===============        STOCK & IMAGES        ============
     * =======================================================*/

    public function incrementStock(int $variantId, int $delta): bool
    {
        $stmt = $this->db->pdo->prepare(
            "UPDATE productvariants SET quantity = quantity + :d WHERE id = :id"
        );
        $stmt->bindValue(':d', $delta, PDO::PARAM_INT);
        $stmt->bindValue(':id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function decrementStock(int $variantId, int $delta, bool $preventNegative = true): bool
    {
        if ($preventNegative) {
            // quantity là UNSIGNED nên tránh âm (schema) :contentReference[oaicite:14]{index=14}
            $sql = "UPDATE productvariants
                    SET quantity = CASE WHEN quantity >= :d THEN quantity - :d ELSE quantity END
                    WHERE id = :id";
        } else {
            $sql = "UPDATE productvariants SET quantity = quantity - :d WHERE id = :id";
        }
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':d', $delta, PDO::PARAM_INT);
        $stmt->bindValue(':id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Gắn nhiều ảnh cho biến thể (thêm bản ghi productimages).
     * productimages: id, variant_id, image_url (schema) :contentReference[oaicite:15]{index=15}
     */
    public function addImages(int $variantId, array $imageUrls): int
    {
        if (empty($imageUrls)) return 0;

        $sql = "INSERT INTO productimages (variant_id, image_url) VALUES (:vid, :url)";
        $stmt = $this->db->pdo->prepare($sql);
        $count = 0;
        foreach ($imageUrls as $url) {
            if ($url === null || $url === '') continue;
            $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
            $stmt->bindValue(':url', $url);
            $stmt->execute();
            $count++;
        }
        return $count;
    }

    public function removeImage(int $imageId): bool
    {
        $stmt = $this->db->pdo->prepare("DELETE FROM productimages WHERE id = :id");
        $stmt->bindValue(':id', $imageId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
