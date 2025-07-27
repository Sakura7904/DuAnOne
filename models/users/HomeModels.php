<?php
require_once 'database/function.php';

class HomeModels {
    private $conn;

    public $db;
    public function __construct()
    {
        $this->db = new Database();
    }
}
