<?php
class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Lấy sản phẩm với phân trang (cập nhật để có đầy đủ thông tin)
    /**
     * Lấy sản phẩm có phân trang với đầy đủ thông tin màu sắc, size, tồn kho
     */
    public function getProductsPaginated($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT
            p.id,
            p.name,
            p.description,
            p.image_thumbnail,
            c.name as category_name,
            -- Giá thấp nhất trong các variants
            COALESCE(MIN(CASE WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0
                              THEN pv.sale_price
                              ELSE pv.price END), 0) as min_price,
            -- Giá cao nhất trong các variants
            COALESCE(MAX(CASE WHEN pv.sale_price IS NOT NULL AND pv.sale_price > 0
                              THEN pv.sale_price
                              ELSE pv.price END), 0) as max_price,
            -- Tổng số lượng tồn kho
            COALESCE(SUM(pv.quantity), 0) as total_quantity,
            -- Danh sách màu sắc
            GROUP_CONCAT(DISTINCT
                CASE WHEN a.name = 'Màu Sắc' THEN av.value END
                ORDER BY av.value ASC
            ) as colors,
            -- Danh sách kích thước
            GROUP_CONCAT(DISTINCT
                CASE WHEN a.name = 'Kích Thước' THEN av.value END
                ORDER BY av.value ASC
            ) as sizes,
            COUNT(DISTINCT pv.id) as variant_count,
            p.created_at
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN productvariants pv ON p.id = pv.product_id
            LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
            LEFT JOIN attributevalues av ON pvv.value_id = av.id
            LEFT JOIN attributes a ON av.attribute_id = a.id
            GROUP BY p.id, p.name, p.description, c.name, p.created_at
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Lấy chi tiết sản phẩm kèm màu sắc, size và tổng tồn kho
     */
    public function getProductDetailWithVariants($id)
    {
        $sql = "SELECT p.*, 
                   c.name as category_name,
                   -- Danh sách màu sắc (loại bỏ trùng lặp)
                   GROUP_CONCAT(DISTINCT 
                       CASE WHEN a.name = 'Màu Sắc' THEN av.value END
                       ORDER BY av.value ASC
                   ) as colors,
                   -- Danh sách kích thước (loại bỏ trùng lặp)
                   GROUP_CONCAT(DISTINCT 
                       CASE WHEN a.name = 'Kích Thước' THEN av.value END
                       ORDER BY av.value ASC
                   ) as sizes,
                   -- Tổng tồn kho
                   COALESCE(SUM(pv.quantity), 0) as total_stock,
                   -- Số lượng variants
                   COUNT(DISTINCT pv.id) as total_variants
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN productvariants pv ON p.id = pv.product_id
            LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
            LEFT JOIN attributevalues av ON pvv.value_id = av.id
            LEFT JOIN attributes a ON av.attribute_id = a.id
            WHERE p.id = :id
            GROUP BY p.id, p.name, p.description, c.name";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Chuyển colors và sizes thành array
            $product['colors_list'] = !empty($product['colors'])
                ? array_filter(explode(',', $product['colors']))
                : [];

            $product['sizes_list'] = !empty($product['sizes'])
                ? array_filter(explode(',', $product['sizes']))
                : [];

            // Đảm bảo dữ liệu số nguyên
            $product['total_stock'] = (int)($product['total_stock'] ?? 0);
            $product['total_variants'] = (int)($product['total_variants'] ?? 0);
        }

        return $product;
    }

    // Lấy tất cả sản phẩm
    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC";

        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public function getProductById($id)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo danh mục
    public function getProductsByCategory($category_id)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id
                ORDER BY p.created_at DESC";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
    public function addProduct($name, $description, $category_id, $thumbnail = null)
    {
        $sql = "INSERT INTO products (name, description, category_id, image_thumbnail)
            VALUES (:name, :description, :category_id, :thumbnail)";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':thumbnail', $thumbnail, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $this->db->pdo->lastInsertId();
        }

        return false;
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $name, $description, $category_id, $thumbnail = null)
    {
        $sql = "UPDATE products 
                SET name = :name, 
                    description = :description, 
                    category_id = :category_id, 
                    image_thumbnail = :thumbnail,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':thumbnail', $thumbnail, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function deleteProduct(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Tìm kiếm sản phẩm
    public function searchProducts($keyword)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.name LIKE :keyword OR p.description LIKE :keyword
                ORDER BY p.created_at DESC";

        $stmt = $this->db->pdo->prepare($sql);
        $search_keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $search_keyword, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số sản phẩm
    public function countProducts()
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->db->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy sản phẩm với số lượng variants và ảnh
    public function getProductsWithDetails()
    {
        $sql = "SELECT p.*, 
                       c.name as category_name,
                       COUNT(DISTINCT pv.id) as variant_count,
                       COUNT(DISTINCT pi.id) as image_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN productvariants pv ON p.id = pv.product_id
                LEFT JOIN productimages pi ON pv.id = pi.variant_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";

        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
