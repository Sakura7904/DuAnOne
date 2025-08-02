<?php
session_start();

include "database/function.php";
include "commons/helpers.php";

//=========================ADMIN=========================
include "controllers/admin/DashboardController.php";
include "controllers/admin/ProductImageController.php";

$act = $_GET['act'] ?? null;
$action = $_GET['action'] ?? 'index';

match ($act) {
    'dashboard' => (new DashboardController())->index(),
    //Tên file trùng với tên act, ví dụ dashboard thì tên file sẽ là dashboard.php

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

    default => die("Không tìm thấy file"),
};
