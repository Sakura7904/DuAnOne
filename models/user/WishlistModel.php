<?php

class WishlistModel
{
    private $db;

    public function __construct()
    {
        // Database của bạn phải có $pdo (PDO instance)
        $this->db = new Database();
    }

    /* ========================= CRUD cơ bản ========================= */

    // Thêm 1 variant vào wishlist (idempotent nếu có UNIQUE/PK (user_id, variant_id))
    public function add(int $userId, int $variantId): bool
    {
        $sql = "INSERT IGNORE INTO wishlistitems (user_id, variant_id) VALUES (:uid, :vid)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Xoá 1 variant khỏi wishlist
    public function remove(int $userId, int $variantId): bool
    {
        $sql = "DELETE FROM wishlistitems WHERE user_id = :uid AND variant_id = :vid";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // (Tuỳ chọn) Xoá TẤT CẢ biến thể của 1 sản phẩm trong wishlist
    public function removeAllVariantsOfProduct(int $userId, int $productId): int
    {
        $sql = "DELETE w FROM wishlistitems w
                JOIN productvariants v ON v.id = w.variant_id
                WHERE w.user_id = :uid AND v.product_id = :pid";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->rowCount();
    }

    // Kiểm tra 1 variant có trong wishlist chưa
    public function exists(int $userId, int $variantId): bool
    {
        $sql = "SELECT 1 FROM wishlistitems WHERE user_id = :uid AND variant_id = :vid LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    // Bật/tắt wishlist cho 1 variant
    public function toggle(int $userId, int $variantId): array
    {
        if ($this->exists($userId, $variantId)) {
            $this->remove($userId, $variantId);
            return ['in_wishlist' => false];
        }
        $this->add($userId, $variantId);
        return ['in_wishlist' => true];
    }

    // Xoá toàn bộ wishlist của user
    public function clear(int $userId): int
    {
        $stmt = $this->db->pdo->prepare("DELETE FROM wishlistitems WHERE user_id = :uid");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->rowCount();
    }

    /* ========================= Hỗ trợ đếm/đánh dấu ========================= */

    // Đếm số item trong wishlist (badge header)
    public function countByUser(int $userId): int
    {
        $stmt = $this->db->pdo->prepare("SELECT COUNT(*) FROM wishlistitems WHERE user_id = :uid");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // Lấy danh sách variant_id trong wishlist (để highlight tim theo variant)
    public function getVariantIdsByUser(int $userId): array
    {
        $stmt = $this->db->pdo->prepare("SELECT variant_id FROM wishlistitems WHERE user_id = :uid");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    // Map tồn tại theo mảng variantIds: trả [variantId => true]
    public function existsMap(int $userId, array $variantIds): array
    {
        if (empty($variantIds)) return [];
        $placeholders = implode(',', array_fill(0, count($variantIds), '?'));
        $sql = "SELECT variant_id FROM wishlistitems WHERE user_id = ? AND variant_id IN ($placeholders)";
        $stmt = $this->db->pdo->prepare($sql);
        $i = 1;
        $stmt->bindValue($i++, $userId, PDO::PARAM_INT);
        foreach ($variantIds as $vid) {
            $stmt->bindValue($i++, (int)$vid, PDO::PARAM_INT);
        }
        $stmt->execute();
        $found = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
        return array_fill_keys($found, true);
    }

    // *** QUAN TRỌNG CHO PHP THUẦN: biết sản phẩm nào đã “thích” (bất kỳ biến thể nào) ***
    public function getProductIdsByUser(int $userId): array
    {
        $sql = "SELECT DISTINCT v.product_id
                FROM wishlistitems w
                JOIN productvariants v ON v.id = w.variant_id
                WHERE w.user_id = :uid";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $map = [];
        foreach ($rows as $pid) $map[(int)$pid] = true;
        return $map; // [product_id => true]
    }

    /* ========================= Lấy dữ liệu render view ========================= */

    /**
     * Danh sách item wishlist (phân trang) đủ thông tin hiển thị
     *  - product_name, product_id (+ p.slug nếu muốn)
     *  - variant_id, in_stock (quantity > 0)
     *  - price, sale_price, display_price
     *  - image_url (ưu tiên ảnh variant, fallback thumbnail product)
     *  - added_at
     *
     * $sort: 'newest' | 'price_asc' | 'price_desc'
     */
   public function getByUser(int $userId, int $limit = 12, int $offset = 0, string $sort = 'newest'): array
{
    $orderBy = "w.created_at DESC";
    if ($sort === 'price_asc')  $orderBy = "display_price ASC, w.created_at DESC";
    if ($sort === 'price_desc') $orderBy = "display_price DESC, w.created_at DESC";

    $limit  = max(1, (int)$limit);
    $offset = max(0, (int)$offset);

    $sql = "
        SELECT
            w.variant_id,
            w.created_at AS added_at,

            v.product_id,
            v.price,
            v.sale_price,
            v.quantity,
            v.image_url AS variant_image,

            p.name AS product_name,
            -- p.slug AS slug,                 -- <== BỎ DÒNG NÀY
            p.image_thumbnail AS product_thumbnail,

            CASE
                WHEN v.sale_price IS NOT NULL AND v.sale_price < v.price THEN v.sale_price
                ELSE v.price
            END AS display_price,

            COALESCE(v.image_url, p.image_thumbnail) AS image_url,

            CASE WHEN v.quantity > 0 THEN 1 ELSE 0 END AS in_stock
        FROM wishlistitems w
        JOIN productvariants v ON v.id = w.variant_id
        JOIN products p        ON p.id = v.product_id
        WHERE w.user_id = :uid
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ";

    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /* ========================= Helper theo product_id ========================= */

    // Lấy 1 variant mặc định của product (ưu tiên giá hiển thị thấp nhất)
    public function getDefaultVariantIdForProduct(int $productId): ?int
    {
        $sql = "
            SELECT id
            FROM productvariants
            WHERE product_id = :pid
            ORDER BY COALESCE(sale_price, price) ASC, id ASC
            LIMIT 1
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $vid = $stmt->fetchColumn();
        return $vid ? (int)$vid : null;
    }

    // Trả về 1 variant_id bất kỳ của PRODUCT đã có trong wishlist (nếu có)
    public function getAnyVariantIdInWishlistForProduct(int $userId, int $productId): ?int
    {
        $sql = "SELECT w.variant_id
                FROM wishlistitems w
                JOIN productvariants v ON v.id = w.variant_id
                WHERE w.user_id = :uid AND v.product_id = :pid
                ORDER BY w.created_at DESC
                LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $vid = $stmt->fetchColumn();
        return $vid ? (int)$vid : null;
    }

    // Toggle theo product_id (THÔNG MINH): có bất kỳ variant nào của product → xoá; chưa có → thêm variant mặc định
    public function toggleByProduct(int $userId, int $productId): array
    {
        // Nếu user đã thích bất kỳ biến thể nào của sản phẩm này → xoá biến thể đó
        $existingVid = $this->getAnyVariantIdInWishlistForProduct($userId, $productId);
        if ($existingVid) {
            $this->remove($userId, $existingVid);
            return ['ok' => true, 'in_wishlist' => false, 'variant_id' => $existingVid, 'removed_existing' => true];
        }

        // Nếu chưa có → thêm biến thể mặc định
        $vid = $this->getDefaultVariantIdForProduct($productId);
        if (!$vid) {
            return ['ok' => false, 'message' => 'Sản phẩm chưa có biến thể'];
        }
        $this->add($userId, $vid);
        return ['ok' => true, 'in_wishlist' => true, 'variant_id' => $vid, 'added_default' => true];
    }
}
