<?php
session_start();

include "database/function.php";
include "commons/helpers.php";

//=========================ADMIN=========================
include "controllers/admin/DashboardController.php";

$act = $_GET['act'] ?? null;

match ($act) {
    'dashboard' => (new DashboardController())->index(),
    default => die("Không tìm thấy file"),
};
