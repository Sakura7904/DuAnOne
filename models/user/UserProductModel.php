<?php
class UserProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /* =================== Danh mục =================== */

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY id ASC";
        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =================== Sản phẩm: helpers =================== */

    // Lấy tất cả id con 
    private function getAllChildCategoryIdsInternal(int $parentId): array
    {
        $sql = "SELECT id FROM categories WHERE parent_id = :pid";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':pid', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        $childIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $all = [];
        foreach ($childIds as $cid) {
            $cid = (int)$cid;
            $all[] = $cid;
            $all = array_merge($all, $this->getAllChildCategoryIdsInternal($cid));
        }
        return $all;
    }

    // Đếm đã bán theo product (đơn vị: item)
    public function getSoldCountByProduct($productId)
    {
        $sql = "
            SELECT COALESCE(SUM(oi.quantity), 0) AS sold_count
            FROM productvariants pv
            JOIN orderitems oi ON oi.variant_id = pv.id
            JOIN orders o      ON o.id = oi.order_id
            WHERE pv.product_id = :pid
              AND (
                    oi.status IN ('completed','delivered')
                    OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid')
                  )
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        return (int)$stmt->fetchColumn();
    }

    // Màu sắc (attribute_id = 1 theo dump của bạn)
    public function getProductColors($productId)
    {
        $sql = "
            SELECT 
                v.id as variant_id,
                av.value AS color_name,
                av.color_code,
                v.image_url
            FROM productvariants v
            INNER JOIN productvariantvalues vv ON v.id = vv.variant_id
            INNER JOIN attributevalues av      ON vv.value_id = av.id
            WHERE v.product_id = :product_id
              AND av.attribute_id = 1
            GROUP BY v.id, av.value, av.color_code, v.image_url
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ======= Bộ lọc sản phẩm hợp lệ (biến thể + Màu + Size + Giá) ======= 
       Dùng bằng cách đặt nguyên khối EXISTS này vào WHERE của mỗi query:
       - Biến thể có giá > 0 (ưu tiên sale_price nếu có)
       - Cùng 1 variant có cả Màu và Size
    */
    private function validVariantExistsSQL(): string
    {
        return "
            EXISTS (
              SELECT 1
              FROM productvariants v_exist
              JOIN productvariantvalues pvv ON pvv.variant_id = v_exist.id
              JOIN attributevalues      av  ON av.id = pvv.value_id
              JOIN attributes           a   ON a.id = av.attribute_id
              WHERE v_exist.product_id = p.id
                AND COALESCE(NULLIF(v_exist.sale_price, 0), v_exist.price) > 0
              GROUP BY v_exist.id
              HAVING
                SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
            )
        ";
    }

    /* =================== Trang mới nhất =================== */

    public function getLatest($limit = 8)
    {
        $exists = $this->validVariantExistsSQL();

        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v
                        JOIN productvariantvalues pvv ON pvv.variant_id = v.id
                        JOIN attributevalues av ON av.id = pvv.value_id
                        JOIN attributes a ON a.id = av.attribute_id
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL AND v.image_url <> ''
                        GROUP BY v.id, v.image_url
                        HAVING
                          SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                          AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                (
                  SELECT COALESCE(SUM(oi.quantity), 0)
                  FROM productvariants pv2
                  JOIN orderitems oi ON oi.variant_id = pv2.id
                  JOIN orders o      ON o.id = oi.order_id
                  WHERE pv2.product_id = p.id
                    AND (
                          oi.status IN ('completed','delivered')
                          OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid')
                        )
                ) AS sold_count,
                p.created_at
            FROM products p
            WHERE $exists
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =================== Chi tiết sản phẩm =================== */

    public function getById($id)
    {
        // Cho phép hiển thị chi tiết sản phẩm nếu có ít nhất 1 variant hợp lệ
        $exists = $this->validVariantExistsSQL();

        $sql = "
            SELECT 
                p.*, 
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        JOIN productvariantvalues pvv ON pvv.variant_id = v.id
                        JOIN attributevalues av ON av.id = pvv.value_id
                        JOIN attributes a ON a.id = av.attribute_id
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL AND v.image_url <> '' 
                        GROUP BY v.id, v.image_url
                        HAVING
                          SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                          AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price) 
                    FROM productvariants v WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                (
                  SELECT COALESCE(SUM(oi.quantity), 0)
                  FROM productvariants pv2
                  JOIN orderitems oi ON oi.variant_id = pv2.id
                  JOIN orders o      ON o.id = oi.order_id
                  WHERE pv2.product_id = p.id
                    AND (
                          oi.status IN ('completed','delivered')
                          OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid')
                        )
                ) AS sold_count
            FROM products p
            WHERE p.id = :id AND $exists
            LIMIT 1
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =================== Danh sách theo danh mục =================== */

    public function countProductsByCategory($categoryId)
    {
        $ids = array_merge([(int)$categoryId], $this->getAllChildCategoryIdsInternal((int)$categoryId));
        $ids = array_values(array_unique($ids));
        if (empty($ids)) return 0;

        $ph = [];
        foreach ($ids as $i => $val) $ph[] = ':id' . $i;
        $in = implode(',', $ph);

        $exists = $this->validVariantExistsSQL();

        $sql = "
            SELECT COUNT(*)
            FROM products p
            WHERE p.category_id IN ($in)
              AND $exists
        ";
        $stmt = $this->db->pdo->prepare($sql);
        foreach ($ids as $i => $val) {
            $stmt->bindValue(':id' . $i, (int)$val, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getProductsByCategory($categoryId, $sort = 'newest', $limit = 12, $offset = 0)
    {
        $orderBy = "p.created_at DESC";
        if ($sort === 'low_to_high') {
            $orderBy = "
                COALESCE(
                    (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
                    (SELECT MIN(v.price)      FROM productvariants v WHERE v.product_id = p.id)
                ) ASC
            ";
        } elseif ($sort === 'high_to_low') {
            $orderBy = "
                COALESCE(
                    (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
                    (SELECT MIN(v.price)      FROM productvariants v WHERE v.product_id = p.id)
                ) DESC
            ";
        }

        $ids = array_merge([(int)$categoryId], $this->getAllChildCategoryIdsInternal((int)$categoryId));
        $ids = array_values(array_unique($ids));
        if (empty($ids)) return [];

        $ph = [];
        foreach ($ids as $i => $val) $ph[] = ':id' . $i;
        $in = implode(',', $ph);

        $limit  = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $exists = $this->validVariantExistsSQL();

        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                p.category_id,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        JOIN productvariantvalues pvv ON pvv.variant_id = v.id
                        JOIN attributevalues av ON av.id = pvv.value_id
                        JOIN attributes a ON a.id = av.attribute_id
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
                        GROUP BY v.id, v.image_url
                        HAVING
                          SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                          AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                (
                  SELECT COALESCE(SUM(oi.quantity), 0)
                  FROM productvariants pv2
                  JOIN orderitems oi ON oi.variant_id = pv2.id
                  JOIN orders o      ON o.id = oi.order_id
                  WHERE pv2.product_id = p.id
                    AND (
                          oi.status IN ('completed','delivered')
                          OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid')
                        )
                ) AS sold_count,
                p.created_at
            FROM products p
            WHERE 
                p.category_id IN ($in)
                AND $exists
            ORDER BY $orderBy
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($ids as $i => $val) {
            $stmt->bindValue(':id' . $i, (int)$val, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =================== Related  =================== */

    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        $exists = $this->validVariantExistsSQL();

        $sql = "SELECT 
                    p.id,
                    p.name,
                    COALESCE(
                        p.image_thumbnail,
                        (
                            SELECT v.image_url
                            FROM productvariants v
                            JOIN productvariantvalues pvv ON pvv.variant_id = v.id
                            JOIN attributevalues av ON av.id = pvv.value_id
                            JOIN attributes a ON a.id = av.attribute_id
                            WHERE v.product_id = p.id
                              AND v.image_url IS NOT NULL AND v.image_url <> ''
                            GROUP BY v.id, v.image_url
                            HAVING
                              SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                              AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
                            ORDER BY v.id ASC
                            LIMIT 1
                        )
                    ) AS image_thumbnail,
                    MIN(CASE 
                        WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0 
                        THEN pv.sale_price ELSE pv.price END
                    ) AS min_price,
                    MAX(CASE 
                        WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0 
                        THEN pv.sale_price ELSE pv.price END
                    ) AS max_price,
                    COALESCE(SUM(
                        CASE 
                          WHEN (oi.status IN ('completed','delivered')
                               OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid'))
                          THEN oi.quantity ELSE 0 
                        END
                    ), 0) AS sold_count,
                    GROUP_CONCAT(
                        DISTINCT CASE 
                            WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac')
                            THEN CONCAT(av.value, ':', COALESCE(av.color_code, ''))
                            ELSE NULL
                        END
                        ORDER BY av.id
                        SEPARATOR '|'
                    ) AS colors
                FROM products p
                LEFT JOIN productvariants pv     ON p.id = pv.product_id
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av     ON pvv.value_id = av.id
                LEFT JOIN attributes a           ON av.attribute_id = a.id
                LEFT JOIN orderitems oi ON oi.variant_id = pv.id
                LEFT JOIN orders o      ON o.id = oi.order_id
                WHERE p.category_id = :category_id
                  AND p.id != :product_id
                  AND $exists
                GROUP BY p.id, p.name, p.image_thumbnail
                ORDER BY RAND()
                LIMIT :limit";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Parse color_options từ cột colors
        foreach ($products as &$product) {
            $product['color_options'] = [];
            if (!empty($product['colors'])) {
                foreach (explode('|', $product['colors']) as $color) {
                    if ($color === '' || $color === null) continue;
                    [$name, $code] = array_pad(explode(':', $color), 2, null);
                    $product['color_options'][] = ['name' => $name, 'color_code' => $code];
                }
            }
            unset($product['colors']);
        }

        return $products;
    }

    /* =================== Tìm kiếm =================== */

    public function searchProduct(string $keyword, int $limit = 100, int $offset = 0): array
    {
        $exists = $this->validVariantExistsSQL();

        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                p.category_id,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        JOIN productvariantvalues pvv ON pvv.variant_id = v.id
                        JOIN attributevalues av ON av.id = pvv.value_id
                        JOIN attributes a ON a.id = av.attribute_id
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
                        GROUP BY v.id, v.image_url
                        HAVING
                          SUM(CASE WHEN LOWER(a.name) IN ('color','màu','màu sắc','mau','mau sac') THEN 1 ELSE 0 END) > 0
                          AND SUM(CASE WHEN LOWER(a.name) IN ('size','kích thước','kich thuoc','kích thuớc') THEN 1 ELSE 0 END) > 0
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                (
                  SELECT COALESCE(SUM(oi.quantity), 0)
                  FROM productvariants pv2
                  JOIN orderitems oi ON oi.variant_id = pv2.id
                  JOIN orders o      ON o.id = oi.order_id
                  WHERE pv2.product_id = p.id
                    AND (
                          oi.status IN ('completed','delivered')
                          OR (o.status IN ('completed','delivered') AND o.payment_status = 'paid')
                        )
                ) AS sold_count,
                p.created_at
            FROM products p
            WHERE p.name LIKE :kw
              AND $exists
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':kw', '%' . trim($keyword) . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =================== Cây danh mục  =================== */

    public function getCategoriesWithChildren()
    {
        $sql = "SELECT * FROM categories ORDER BY parent_id, id";
        $stmt = $this->db->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tree = [];
        foreach ($rows as $row) {
            if ($row['parent_id'] === null) {
                $tree[$row['id']] = $row;
                $tree[$row['id']]['children'] = [];
            } else {
                $tree[$row['parent_id']]['children'][] = $row;
            }
        }
        return $tree;
    }
}
