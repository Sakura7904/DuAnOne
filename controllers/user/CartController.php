<?php
include_once 'models/user/CartModel.php';

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    /**
     * Hiển thị trang giỏ hàng
     */
    public function cart()
    {
        $cartItems = [];
        $cartTotal = 0;
        $cartItemCount = 0;

        // Kiểm tra user đã đăng nhập chưa
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // Lấy hoặc tạo giỏ hàng cho user
            $cart = $this->cartModel->getOrCreateCart($userId);

            if ($cart) {
                $cartItems = $this->cartModel->getCartItems($cart['id']);
                $cartTotal = $this->cartModel->getCartTotal($cart['id']);
                $cartItemCount = $this->cartModel->getCartItemCount($cart['id']);
            }
        }

        $content = getContentPathClient('', 'cart');
        view('user/index', [
            'content' => $content,
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'cartItemCount' => $cartItemCount
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'];
            header('Location: ?user=login');
            exit;
        }

        $variantId = $_POST['variant_id'] ?? $_GET['variant_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? $_GET['quantity'] ?? 1;
        $userId = $_SESSION['user_id'];

        $back = $_SERVER['HTTP_REFERER'] ?? '?user=home';

        if ($variantId <= 0 || $quantity <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Dữ liệu không hợp lệ'];
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '?user=shop'));
            exit;
        }

        if (!$this->cartModel->checkProductAvailability($variantId, $quantity)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Sản phẩm không đủ số lượng trong kho'];
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '?user=shop'));
            exit;
        }

        try {
            $cart = $this->cartModel->getOrCreateCart($userId);
            $result = $this->cartModel->addToCart($cart['id'], $variantId, $quantity);

            if ($result) {
                $this->cartModel->updateCartTimestamp($cart['id']);
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi thêm sản phẩm'];
            }
        } catch (Exception $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }
        header('Location: ' . $back);
        exit;
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCartQuantity()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập'];
            header('Location: ?user=cart');
            exit;
        }

        $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
        $quantity   = (int)($_POST['quantity'] ?? 1);

        if ($cartItemId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Dữ liệu không hợp lệ'];
            header('Location: ?user=cart');
            exit;
        }

        try {
            $cartItem = $this->cartModel->getCartItemDetail($cartItemId);
            if (!$cartItem) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'];
                header('Location: ?user=cart');
                exit;
            }

            $stock = (int)$cartItem['stock'];
            $quantity = max(1, $quantity);

            if ($stock > 0 && $quantity > $stock) {
                $quantity = $stock;
                $_SESSION['alert'] = ['type' => 'error', 'message' => "Số lượng vượt quá tồn kho. Đã chỉnh về {$stock}."];
            }

            $ok = $this->cartModel->updateCartItemQuantity($cartItemId, $quantity);
            $_SESSION['alert'] = $ok
                ? ['type' => 'success', 'message' => 'Đã cập nhật số lượng thành công!']
                : ['type' => 'error',   'message' => 'Có lỗi xảy ra'];
        } catch (Exception $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }

        header('Location: ?user=cart');
        exit;
    }


    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập'];
            header('Location: ?user=cart');
            exit;
        }

        $cartItemId = $_POST['cart_item_id'] ?? $_GET['cart_item_id'] ?? 0;

        if ($cartItemId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Dữ liệu không hợp lệ'];
            header('Location: ?user=cart');
            exit;
        }

        try {
            $result = $this->cartModel->removeCartItem($cartItemId);

            if ($result) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra'];
            }
        } catch (Exception $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }

        header('Location: ?user=cart');
        exit;
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập'];
            header('Location: ?user=cart');
            exit;
        }

        $userId = $_SESSION['user_id'];

        try {
            $cart = $this->cartModel->getCartByUserId($userId);

            if ($cart) {
                $result = $this->cartModel->clearCart($cart['id']);

                if ($result) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa toàn bộ giỏ hàng!'];
                } else {
                    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra'];
                }
            } else {
                $_SESSION['alert'] = ['type' => 'info', 'message' => 'Giỏ hàng đã trống'];
            }
        } catch (Exception $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }

        header('Location: ?user=cart');
        exit;
    }
}
