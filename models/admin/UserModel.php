<?php

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

   public function getAll()
{
    $sql = "SELECT * FROM users 
            ORDER BY 
                CASE WHEN role = 'admin' THEN 0 ELSE 1 END, 
                created_at DESC";
    $stmt = $this->db->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function updateRole($id, $newRole)
{
    $currentUser = $this->getById($id);
    if ($currentUser && $currentUser['role'] === 'admin' && $newRole === 'user') {
        return false;
    }

    $sql = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindParam(':role', $newRole, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

}
