<?php
require_once 'database/function.php';

class CategoryModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->pdo;
    }

    /** Lấy tất cả danh mục */
    public function getAll()
    {
        $sql = "SELECT id, name, parent_id, image_url, created_at, updated_at
            FROM categories
            ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /** Lấy tất cả kèm tên cha (nếu cần cho list admin) */
    public function getAllCategories()
    {
        $sql = "SELECT c1.id, c1.name, c1.parent_id, c1.image_url,
                       c2.name AS parent_name
                FROM categories c1
                LEFT JOIN categories c2 ON c1.parent_id = c2.id
                ORDER BY c1.parent_id ASC, c1.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy 1 danh mục theo id */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, parent_id, image_url FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Lưu mới danh mục (có ảnh) */
    public function insert($name, $parent_id, $image_url)
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, parent_id, image_url) VALUES (?, ?, ?)");
        // Nếu $parent_id = null, PDO sẽ bind NULL đúng kiểu
        return $stmt->execute([$name, $parent_id, $image_url]);
    }

    /** Cập nhật danh mục (có ảnh) */
    public function update($id, $name, $parent_id, $image_url)
    {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, parent_id = ?, image_url = ? WHERE id = ?");
        return $stmt->execute([$name, $parent_id, $image_url, $id]);
    }

    /** Lấy đường dẫn ảnh để xoá file khi cần */
    public function getImagePathById($id)
    {
        $stmt = $this->conn->prepare("SELECT image_url FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: null;
    }

    /** Xoá cascade (sản phẩm/biến thể) rồi xoá category */
    public function deleteCategoryCascade($category_id)
    {
        // 1. Lấy danh sách sản phẩm thuộc danh mục
        $stmt = $this->conn->prepare("SELECT id FROM products WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $product_id = $product['id'];

            // 2. Lấy danh sách biến thể thuộc sản phẩm
            $stmt = $this->conn->prepare("SELECT id FROM productvariants WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($variants as $variant) {
                $variant_id = $variant['id'];

                // 3. Xoá các dòng trong productvariantvalues
                $stmt = $this->conn->prepare("DELETE FROM productvariantvalues WHERE variant_id = ?");
                $stmt->execute([$variant_id]);
            }

            // 4. Xoá các dòng trong productvariants
            $stmt = $this->conn->prepare("DELETE FROM productvariants WHERE product_id = ?");
            $stmt->execute([$product_id]);

            // 5. Xoá sản phẩm
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
        }

        // 6. Cuối cùng xoá danh mục
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$category_id]);
    }

    /** Kiểm tra danh mục còn tồn kho trong variants hay không */
    public function hasProducts($category_id)
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM productvariants pv
            INNER JOIN products p ON pv.product_id = p.id
            WHERE p.category_id = ? AND pv.quantity > 0
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
}
