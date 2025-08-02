<?php
session_start();

include "database/function.php";
include "commons/helpers.php";


if (isset($_GET['page'])) {
    $content = getContentPath();
    include "views/admin/master.php";
    exit();
}

// ========================= LOAD CONTROLLER =========================
include "controllers/admin/DashboardController.php";
include "controllers/admin/CategoriesController.php";

$act = $_GET['act'] ?? null;

match ($act) {
    // ===== DASHBOARD =====
    'dashboard'         => (new DashboardController())->index(),

    // ===== CATEGORIES =====
    'list_categories'   => (new CategoriesController())->index(),
    'create_category'   => (new CategoriesController())->create(),
    'store_category'    => (new CategoriesController())->store(),
    'edit_category'     => (new CategoriesController())->edit($_GET['id']),
    'update_category'   => (new CategoriesController())->update($_GET['id']),
    'delete_category'   => (new CategoriesController())->delete($_GET['id']),

    // ===== MẶC ĐỊNH =====
    default             => die("Không tìm thấy hành động phù hợp"),
};
