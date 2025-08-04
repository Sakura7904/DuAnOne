<?php
session_start();

include "database/function.php";
include "commons/helpers.php";


if (isset($_GET['page'])) {
    $content = getContentPath();
    include "views/admin/master.php";
    exit();
}

// ========================= Controller admin =========================
include "controllers/admin/DashboardController.php";
include "controllers/admin/ProductImageController.php";
include "controllers/admin/CategoriesController.php";
include "controllers/admin/AuthController.php";
include "controllers/admin/AccountsController.php";

$admin = $_GET['admin'] ?? "";
$action = $_GET['action'] ?? 'index';

// =========================Check đăng nhập =========================
if (!empty($admin) && !in_array($admin, ['login', 'loginForm'])) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('location: index.php?admin=loginForm');
        exit();
    }
}

match ($admin) {
    'dashboard' => (new DashboardController())->index(),

    // ===== Product Image =====
    'product_images' => (function () use ($action) {
        $controller = new ProductImageController();

        return match ($action) {
            'index'                => $controller->index(),
            'store'                => $controller->store(),
            'edit'                 => $controller->edit(),
            'update'               => $controller->update(),
            'delete'               => $controller->delete(),
            'bulkDelete'           => $controller->bulkDelete(),
            'getVariantsByProduct' => $controller->getVariantsByProduct(),
            default                => $controller->index(),
        };
    })(),

    // ===== CATEGORIES =====
    'list_categories'   => (new CategoriesController())->index(),
    'create_category'   => (new CategoriesController())->create(),
    'store_category'    => (new CategoriesController())->store(),
    'edit_category'     => (new CategoriesController())->edit($_GET['id']),
    'update_category'   => (new CategoriesController())->update($_GET['id']),
    'delete_category'   => (new CategoriesController())->delete($_GET['id']),


    // ===== Đâng nhập (admin) =====
    'loginForm' => (new AuthController())->loginForm(),
    'login'      => (new AuthController())->login(),
    'logout'     => (new AuthController())->logout(),

    // ===== QUẢN LÝ USERS =====
    'list_accounts'         => (new AccountsController())->index(),
   'change_status_accounts' => (new AccountsController())->changeStatus($_POST['id']),
   'promote_accounts_role'    => (new AccountsController())->changeRole($_POST['id']),


    // ===== Mặc định không tìm thấy =====
    default => die("Không tìm thấy hành động phù hợp."),
};
