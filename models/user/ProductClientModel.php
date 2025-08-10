<?php

class ProductClientModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Lấy chi tiết đầy đủ sản phẩm cho trang client
     */
    public function getProductDetail($productId)
    {
        $sql = "SELECT p.*,
                       c.name as category_name,
                       c.parent_id as parent_category_id,
                       parent_cat.name as parent_category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN categories parent_cat ON c.parent_id = parent_cat.id
                WHERE p.id = :product_id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả variants của sản phẩm kèm attributes
     */
    public function getProductVariants($productId)
    {
        $sql = "SELECT pv.*,
                       GROUP_CONCAT(
                           CONCAT(a.name, ':', av.value, ':', av.color_code)
                           ORDER BY a.id
                           SEPARATOR '|'
                       ) as attributes
                FROM productvariants pv
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av ON pvv.value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.product_id = :product_id
                GROUP BY pv.id
                ORDER BY pv.id ASC";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xử lý attributes cho mỗi variant
        foreach ($variants as &$variant) {
            $variant['attributes_array'] = [];
            if (!empty($variant['attributes'])) {
                $attributes = explode('|', $variant['attributes']);
                foreach ($attributes as $attr) {
                    $parts = explode(':', $attr);
                    if (count($parts) >= 2) {
                        $variant['attributes_array'][$parts[0]] = [
                            'value' => $parts[1],
                            'color_code' => $parts[2] ?? null
                        ];
                    }
                }
            }
        }

        return $variants;
    }

    /**
     * Lấy tất cả hình ảnh của các variants trong sản phẩm
     */
    public function getProductImages($productId)
    {
        $sql = "SELECT pi.*,
                       pv.id as variant_id,
                       GROUP_CONCAT(
                           CONCAT(a.name, ':', av.value)
                           ORDER BY a.id
                           SEPARATOR '|'
                       ) as variant_attributes
                FROM productimages pi
                JOIN productvariants pv ON pi.variant_id = pv.id
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av ON pvv.value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.product_id = :product_id
                GROUP BY pi.id
                ORDER BY pi.created_at ASC";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy các giá trị attributes duy nhất của sản phẩm
     */
    public function getProductAttributes($productId)
    {
        $sql = "SELECT a.id,
                       a.name as attribute_name,
                       av.id as value_id,
                       av.value,
                       av.color_code
                FROM attributes a
                JOIN attributevalues av ON a.id = av.attribute_id
                JOIN productvariantvalues pvv ON av.id = pvv.value_id
                JOIN productvariants pv ON pvv.variant_id = pv.id
                WHERE pv.product_id = :product_id
                GROUP BY a.id, av.id
                ORDER BY a.id, av.id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nhóm theo attribute
        $attributes = [];
        foreach ($results as $row) {
            $attrName = $row['attribute_name'];
            if (!isset($attributes[$attrName])) {
                $attributes[$attrName] = [
                    'id' => $row['id'],
                    'name' => $attrName,
                    'values' => []
                ];
            }

            $attributes[$attrName]['values'][] = [
                'id' => $row['value_id'],
                'value' => $row['value'],
                'color_code' => $row['color_code']
            ];
        }

        return array_values($attributes);
    }

    /**
     * Lấy comments của sản phẩm
     */
    public function getProductComments($productId, $limit = 10, $offset = 0)
    {
        $sql = "SELECT c.*,
                       u.full_name,
                       u.email
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.product_id = :product_id
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số comments của sản phẩm
     */
    public function countProductComments($productId)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM comments 
                WHERE product_id = :product_id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Lấy sản phẩm liên quan (cùng category)
     */
    /**
     * Lấy sản phẩm liên quan kèm màu sắc
     */
    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        $sql = "SELECT p.id,
                   p.name,
                   p.image_thumbnail,
                   MIN(CASE 
                       WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0 
                       THEN pv.sale_price 
                       ELSE pv.price 
                   END) as min_price,
                   MAX(CASE 
                       WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0 
                       THEN pv.sale_price 
                       ELSE pv.price 
                   END) as max_price,
                   GROUP_CONCAT(
                       DISTINCT CASE 
                           WHEN LOWER(a.name) IN ('color', 'màu', 'màu sắc', 'mau', 'mau sac')
                           THEN CONCAT(av.value, ':', COALESCE(av.color_code, ''))
                           ELSE NULL
                       END
                       ORDER BY av.id
                       SEPARATOR '|'
                   ) as colors
            FROM products p
            LEFT JOIN productvariants pv ON p.id = pv.product_id
            LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
            LEFT JOIN attributevalues av ON pvv.value_id = av.id
            LEFT JOIN attributes a ON av.attribute_id = a.id
            WHERE p.category_id = :category_id 
            AND p.id != :product_id
            GROUP BY p.id
            ORDER BY RAND()
            LIMIT :limit";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xử lý dữ liệu màu sắc cho từng sản phẩm
        foreach ($products as &$product) {
            $product['color_options'] = [];
            if (!empty($product['colors'])) {
                $colors = explode('|', $product['colors']);
                foreach ($colors as $color) {
                    if (!empty($color)) {
                        $parts = explode(':', $color);
                        if (count($parts) >= 1) {
                            $product['color_options'][] = [
                                'name' => $parts[0],
                                'color_code' => $parts[1] ?? null
                            ];
                        }
                    }
                }
            }
            // Xóa cột colors gốc không cần thiết
            unset($product['colors']);
        }

        return $products;
    }


    /**
     * Lấy variant cụ thể bằng ID
     */
    public function getVariantById($variantId)
    {
        $sql = "SELECT pv.*,
                       p.name as product_name,
                       GROUP_CONCAT(
                           CONCAT(a.name, ':', av.value, ':', av.color_code)
                           ORDER BY a.id
                           SEPARATOR '|'
                       ) as attributes
                FROM productvariants pv
                JOIN products p ON pv.product_id = p.id
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av ON pvv.value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.id = :variant_id
                GROUP BY pv.id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm variant dựa trên product ID và attributes
     */
    public function findVariantByAttributes($productId, $attributes)
    {
        // Tạo điều kiện WHERE dựa trên attributes
        $conditions = [];
        $params = [':product_id' => $productId];

        foreach ($attributes as $attrName => $attrValue) {
            $conditions[] = "(a.name = :attr_name_{$attrName} AND av.value = :attr_value_{$attrName})";
            $params[":attr_name_{$attrName}"] = $attrName;
            $params[":attr_value_{$attrName}"] = $attrValue;
        }

        $whereClause = implode(' OR ', $conditions);

        $sql = "SELECT pv.id, COUNT(*) as match_count
                FROM productvariants pv
                JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                JOIN attributevalues av ON pvv.value_id = av.id
                JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.product_id = :product_id
                AND ({$whereClause})
                GROUP BY pv.id
                HAVING match_count = " . count($attributes) . "
                LIMIT 1";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    /**
     * Kiểm tra variant có trong wishlist của user không
     */
    public function isInWishlist($userId, $variantId)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM wishlistitems 
                WHERE user_id = :user_id AND variant_id = :variant_id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Lấy tất cả thông tin cần thiết cho trang chi tiết sản phẩm
     */
    public function getCompleteProductData($productId, $userId = null)
    {
        $data = [];

        // Thông tin cơ bản sản phẩm
        $data['product'] = $this->getProductDetail($productId);

        if (!$data['product']) {
            return null;
        }

        // Variants và attributes
        $data['variants'] = $this->getProductVariants($productId);
        $data['attributes'] = $this->getProductAttributes($productId);

        // Hình ảnh
        $data['images'] = $this->getProductImages($productId);

        // Comments
        $data['comments'] = $this->getProductComments($productId, 5);
        $data['total_comments'] = $this->countProductComments($productId);

        // Sản phẩm liên quan
        $data['related_products'] = $this->getRelatedProducts(
            $productId,
            $data['product']['category_id']
        );

        // Thông tin wishlist nếu có user
        if ($userId) {
            foreach ($data['variants'] as &$variant) {
                $variant['in_wishlist'] = $this->isInWishlist($userId, $variant['id']);
            }
        }

        return $data;
    }

    /**
     * Thêm vào wishlist
     */
    public function addToWishlist($userId, $variantId)
    {
        $sql = "INSERT IGNORE INTO wishlistitems (user_id, variant_id) 
            VALUES (:user_id, :variant_id)";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Lấy các size có sẵn của một màu cụ thể
     */
    public function getSizesByColor($productId, $colorValue)
    {
        $sql = "SELECT DISTINCT 
                   size_av.value as size_value,
                   size_av.id as size_value_id,
                   pv.id as variant_id,
                   pv.quantity
            FROM productvariants pv
            JOIN productvariantvalues pvv_color ON pv.id = pvv_color.variant_id
            JOIN attributevalues color_av ON pvv_color.value_id = color_av.id
            JOIN attributes color_attr ON color_av.attribute_id = color_attr.id
            JOIN productvariantvalues pvv_size ON pv.id = pvv_size.variant_id
            JOIN attributevalues size_av ON pvv_size.value_id = size_av.id
            JOIN attributes size_attr ON size_av.attribute_id = size_attr.id
            WHERE pv.product_id = :product_id
            AND LOWER(color_attr.name) IN ('color', 'màu', 'màu sắc', 'mau', 'mau sac')
            AND color_av.value = :color_value
            AND LOWER(size_attr.name) IN ('size', 'kích thước', 'kich thuoc', 'kích cỡ', 'kich co')
            ORDER BY size_av.id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':color_value', $colorValue, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin variant và số lượng tồn kho theo màu và size
     */
    public function getVariantByColorAndSize($productId, $colorValue, $sizeValue)
    {
        $sql = "SELECT pv.*
            FROM productvariants pv
            JOIN productvariantvalues pvv_color ON pv.id = pvv_color.variant_id
            JOIN attributevalues color_av ON pvv_color.value_id = color_av.id
            JOIN attributes color_attr ON color_av.attribute_id = color_attr.id
            JOIN productvariantvalues pvv_size ON pv.id = pvv_size.variant_id
            JOIN attributevalues size_av ON pvv_size.value_id = size_av.id
            JOIN attributes size_attr ON size_av.attribute_id = size_attr.id
            WHERE pv.product_id = :product_id
            AND LOWER(color_attr.name) IN ('color', 'màu', 'màu sắc', 'mau', 'mau sac')
            AND color_av.value = :color_value
            AND LOWER(size_attr.name) IN ('size', 'kích thước', 'kich thuoc', 'kích cỡ', 'kich co')
            AND size_av.value = :size_value
            LIMIT 1";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':color_value', $colorValue, PDO::PARAM_STR);
        $stmt->bindParam(':size_value', $sizeValue, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
