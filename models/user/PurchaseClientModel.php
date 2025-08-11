<?php

class PurchaseModel
{
    /** @var Database */
    public $db;

    public function __construct()
    {
        $this->db = new Database(); // $this->db->pdo là PDO
    }
}
