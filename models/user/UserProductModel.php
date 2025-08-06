<?php
class UserProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
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
}
