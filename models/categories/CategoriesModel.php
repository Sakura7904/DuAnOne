<?php
require_once 'database/function.php';

class CategoriesModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->pdo;
    }

    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($name, $parent_id) {
        $stmt = $this->conn->prepare("INSERT INTO categories(name, parent_id) VALUES (?, ?)");
        return $stmt->execute([$name, $parent_id]);
    }

    public function update($id, $name, $parent_id) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
        return $stmt->execute([$name, $parent_id, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
