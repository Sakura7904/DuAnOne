<?php
class UserProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
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





    // Lấy top N sản phẩm mới nhất (theo ngày tạo), lấy đúng 1 ảnh, giá theo variant (nếu có)
    public function getLatest($limit = 8)
    {
        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                -- Ưu tiên ảnh chính của sản phẩm, nếu không có thì lấy ảnh đầu tiên từ variant
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
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
                p.created_at
            FROM products p
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Chi tiết sản phẩm theo ID (bao gồm lấy giá/ảnh đại diện hợp lý nhất)
    public function getById($id)
    {
        $sql = "
            SELECT 
                p.*, 
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id AND v.image_url IS NOT NULL AND v.image_url <> '' 
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
                ) AS sale_price
            FROM products p
            WHERE p.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
            INNER JOIN attributevalues av ON vv.value_id = av.id
            WHERE v.product_id = :product_id
              AND av.attribute_id = 1 -- 1 = màu sắc
            GROUP BY v.id, av.value, av.color_code, v.image_url
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($categoryId, $sort = 'newest', $limit = 12, $offset = 0)
    {
        $orderBy = "p.created_at DESC";
        if ($sort === 'low_to_high') {
            $orderBy = "
        COALESCE(
            (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
            (SELECT MIN(v.price) FROM productvariants v WHERE v.product_id = p.id)
        ) ASC
    ";
        } elseif ($sort === 'high_to_low') {
            $orderBy = "
        COALESCE(
            (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
            (SELECT MIN(v.price) FROM productvariants v WHERE v.product_id = p.id)
        ) DESC
    ";
        }

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
                    WHERE v.product_id = p.id 
                      AND v.image_url IS NOT NULL 
                      AND v.image_url <> '' 
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
            p.created_at
        FROM products p
        WHERE p.category_id = :category_id
        ORDER BY $orderBy
        LIMIT :limit OFFSET :offset
    ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProductsByCategory($categoryId)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :category_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }


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
