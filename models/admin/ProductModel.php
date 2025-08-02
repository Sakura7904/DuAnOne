<?php
class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
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
    public function addProduct($name, $description, $category_id)
    {
        $sql = "INSERT INTO products (name, description, category_id) 
                VALUES (:name, :description, :category_id)";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->db->pdo->lastInsertId();
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $name, $description, $category_id)
    {
        $sql = "UPDATE products 
                SET name = :name, description = :description, category_id = :category_id,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function deleteProduct($id)
    {
        try {
            $this->db->pdo->beginTransaction();
            
            // Xóa ảnh của các variants thuộc sản phẩm này
            $sql1 = "DELETE pi FROM productimages pi 
                     INNER JOIN productvariants pv ON pi.variant_id = pv.id 
                     WHERE pv.product_id = :id";
            $stmt1 = $this->db->pdo->prepare($sql1);
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();
            
            // Xóa variants của sản phẩm
            $sql2 = "DELETE FROM productvariants WHERE product_id = :id";
            $stmt2 = $this->db->pdo->prepare($sql2);
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();
            
            // Xóa sản phẩm
            $sql3 = "DELETE FROM products WHERE id = :id";
            $stmt3 = $this->db->pdo->prepare($sql3);
            $stmt3->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt3->execute();
            
            $this->db->pdo->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            return false;
        }
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

    // Lấy sản phẩm với phân trang
    public function getProductsWithPagination($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
?>
