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

$act = $_GET['act'] ?? null;
$action = $_GET['action'] ?? 'index';

match ($act) {
    'dashboard' => (new DashboardController())->index(),
    //Tên file trùng với tên act, ví dụ dashboard thì tên file sẽ là dashboard.php

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

    // ===== MẶC ĐỊNH =====
    default             => die("Không tìm thấy hành động phù hợp"),
};
