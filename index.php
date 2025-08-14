<?php
session_start();

include "database/function.php";
include "commons/helpers.php";
require_once __DIR__ . '/vendor/autoload.php';

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
include "controllers/admin/ProductController.php";
include "controllers/admin/OrdersController.php";

// ========================= Controller client =========================
include "controllers/user/HomeController.php";
include "controllers/user/DetailProductController.php";
include "controllers/user/AuthClientController.php";
include "controllers/user/CartController.php";
include "controllers/user/OrderController.php";
include "controllers/user/PurchaseController.php";
include "controllers/user/ProductByCategoryController.php";
include "controllers/user/WishlishController.php";

$admin = $_GET['admin'] ?? "";
$user = $_GET['user'] ?? "";
$action = $_GET['action'] ?? 'index';

// =========================Check đăng nhập =========================
if (!empty($admin) && !in_array($admin, ['login', 'loginForm'])) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('location: index.php?admin=loginForm');
        exit();
    }
}
if (!empty($admin)) {
    match ($admin) {
        'dashboard' => (new DashboardController())->index(),

        // ===== Đâng nhập (admin) =====
        'loginForm' => (new AuthController())->loginForm(),
        'login'      => (new AuthController())->login(),
        'logout'     => (new AuthController())->logout(),

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

        // ===== PRODUCTS =====
        'list_products'   => (new ProductController())->index(),
        'add_products'   => (new ProductController())->create(),
        'store_products'   => (new ProductController())->store(),
        'update_product'  => (new ProductController())->update(),
        'edit_product'    => (new ProductController())->edit($_GET['id'] ?? 0),
        'show_product'    => (new ProductController())->show($_GET['id'] ?? 0),
        'delete_product' => (new ProductController())->delete($_GET['id'] ?? 0),
        'delete_product_gallery' => (new ProductController())->deleteProductGallery(),


        // ===== QUẢN LÝ USERS =====
        'list_accounts'         => (new AccountsController())->index(),
        'change_status_accounts' => (new AccountsController())->changeStatus($_POST['id']),
        'promote_accounts_role'    => (new AccountsController())->changeRole($_POST['id']),

        // ===== ORDERS (admin) =====
        'list-order'                    => (new OrdersController())->order(),
        'order_update_status'       => (new OrdersController())->updateOrderStatus(),
        'order_item_update_status'  => (new OrdersController())->updateItemStatus(),
        'order_item_bulk_update'    => (new OrdersController())->bulkUpdateItemStatus(),
        'order_update_payment'      => (new OrdersController())->updatePaymentStatus(),
        'order_items_apply_status' => (new OrdersController())->applyStatusByItems(),


        // ===== Mặc định không tìm thấy =====
        default => die("Không tìm file nào như vậy."),
    };
}

if (!empty($user) || (empty($admin) && empty($user))) {
    match ($user) {
        // ===== Trang chủ =====
        'home' => (new HomeController())->home(),

        // ===== Trang sản phẩm the danh mục =====
        'productsByCategory' => (new ProductByCategoryController())->showByCategory(),

        // ===== Trang chi tiết sản =====
        'detailProduct' => (new DetailProductController())->detailProduct(),
        'getSizesByColor' => (new DetailProductController())->getSizesByColor(),
        'getVariantByColorAndSize' => (new DetailProductController())->getVariantByColorAndSize(),

        // ===== Trang đăng ký đăng nhập =====
        'login' => (new AuthClientController())->login(),
        'handleLogin' => (new AuthClientController())->handleLogin(),
        'handleRegister' => (new AuthClientController())->handleRegister(),
        'logout' => (new AuthClientController())->logout(),
        'changePassword' => (new AuthClientController())->changePassword(),
        'handleChangePassword' => (new AuthClientController())->handleChangePassword(),

        // ===== Trang profile =====
        'profile' => (new AuthClientController())->profile(),
        'handleUpdateProfile' => (new AuthClientController())->handleUpdateProfile(),

        // ===== Trang cart =====
        'cart' => (new CartController())->cart(),
        'addToCart' => (new CartController())->addToCart(),
        'updateCartQuantity' => (new CartController())->updateCartQuantity(),
        'removeFromCart' => (new CartController())->removeFromCart(),
        'clearCart' => (new CartController())->clearCart(),

        // ===== Trang thanh toán =====
        'order'              => (new OrderController())->order(),
        'actCheckoutFromCart' => (new OrderController())->actCheckoutFromCart(),

        // ===== Thanh toán MOMO=====
        'actMomoReturn'   => (new OrderController())->actMomoReturn(),
        'actMomoIPN'      => (new OrderController())->actMomoIPN(),

        // ===== Trang thanh toán Stripe =====        
        'actStripeReturn'     => (new OrderController())->actStripeReturn(),

        // ===== Trang đơn hàng =====        
        'purchase'     => (new PurchaseController())->purchase(),
        'cancelOrderItem' => (new PurchaseController())->cancelOrderItem(),

        // ===== Wishlist =====
        'wishlist'           => (new WishlistController())->index(),          // GET:  index.php?user=wishlist&pg=1&sort=newest
        'toggleWishlist'     => (new WishlistController())->toggle(),         // POST: index.php?user=toggleWishlist   (variant_id|product_id)
        'addToWishlist'      => (new WishlistController())->add(),            // GET:  index.php?user=addToWishlist&variant_id=123
        'removeFromWishlist' => (new WishlistController())->remove(),         // GET:  index.php?user=removeFromWishlist&variant_id=123
        'clearWishlist'      => (new WishlistController())->clear(),          // POST: index.php?user=clearWishlist
        'countWishlist'      => (new WishlistController())->count(),          // GET:  index.php?user=countWishlist


        default => die("Không tìm thấy file nào như thế cả!!!"),
    };
}
