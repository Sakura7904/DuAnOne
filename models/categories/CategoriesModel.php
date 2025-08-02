<?php
require_once 'database/function.php';

class CategoriesModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->pdo;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($name, $parent_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO categories(name, parent_id) VALUES (?, ?)");
        return $stmt->execute([$name, $parent_id]);
    }

    public function update($id, $name, $parent_id)
    {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
        return $stmt->execute([$name, $parent_id, $id]);
    }

    public function deleteCategoryCascade($category_id) {
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


    public function hasProducts($category_id)
    {
        $sql = "
        SELECT COUNT(*) as total
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
