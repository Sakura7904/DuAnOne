<?php

class AuthModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function findByEmail($email)
  {

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
