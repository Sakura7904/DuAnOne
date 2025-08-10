<?php

include_once "models/user/OrderModel.php";

class OrderController
{
    /** @var OrderModel */
    private $orderModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->orderModel = new OrderModel();
    }

    /* ========== Helpers ========== */

    private function flashSwal($icon, $title, $text = '')
    {
        $_SESSION['swal'] = [
            'icon'  => $icon,   // success | error | warning | info | question
            'title' => $title,
            'text'  => $text
        ];
    }

    private function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    private function postTrim($key, $default = '')
    {
        return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
    }

    private function getInt($arr, $key, $default = 0)
    {
        return isset($arr[$key]) ? (int)$arr[$key] : $default;
    }

    /* ==================================
     * View: trang order (list/detail)
     * index.php: 'order' => (new OrderController())->order(),
     * ================================== */
    public function order()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        // Lấy giỏ + tính tổng
        $items  = $this->orderModel->getCartItemsDetailed($userId);
        $totals = $this->orderModel->calcCartTotals($items);

        $customer = $this->orderModel->getCustomerBasic($userId);

        $content = getContentPathClient('', 'order');
        view('user/index', [
            'content'  => $content,
            'customer' => $customer,
            'items'    => $items,
            'totals'   => $totals,
        ]);
    }


    /* =====================================================
     * Actions — index.php router gọi trực tiếp từng hàm này
     * ===================================================== */

    public function actCheckoutFromCart()
    {
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        $receiverName    = $this->postTrim('receiver_name');
        $receiverPhone   = $this->postTrim('receiver_phone');
        $shippingAddress = $this->postTrim('shipping_address');
        $paymentMethod   = $this->postTrim('payment_method', 'COD');

        if ($userId <= 0 || $receiverName === '' || $receiverPhone === '' || $shippingAddress === '') {
            $_SESSION['order_alert'] = ['type' => 'warning', 'message' => 'Vui lòng nhập đủ thông tin giao hàng.'];
            $this->redirect('index.php?user=order');
        }

        $res = $this->orderModel->createOrderFromCart(
            $userId,
            $receiverName,
            $receiverPhone,
            $shippingAddress,
            $paymentMethod
        );

        if (!empty($res['success'])) {
            $orderId = (int)$res['order_id'];
            $totalVn = number_format((float)$res['total'], 0, ',', '.') . 'đ';
            $_SESSION['order_alert'] = [
                'type'    => 'success',
                'message' => "Đặt hàng thành công. Mã đơn #$orderId • Tổng $totalVn"
            ];
            $this->redirect("index.php?user=home");
        }

        $_SESSION['order_alert'] = [
            'type'    => 'error',
            'message' => 'Không thể tạo đơn' . (!empty($res['message']) ? (': ' . $res['message']) : '')
        ];
        $this->redirect('index.php?user=order');
    }


    public function actCheckoutDirect()
    {
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        $variantIds = isset($_POST['variant_id']) ? (array)$_POST['variant_id'] : [];
        $quantities = isset($_POST['quantity'])   ? (array)$_POST['quantity']   : [];

        $items = [];
        foreach ($variantIds as $i => $vid) {
            $v = (int)$vid;
            $q = isset($quantities[$i]) ? (int)$quantities[$i] : 1;
            if ($v > 0 && $q > 0) {
                $items[] = ['variant_id' => $v, 'quantity' => $q];
            }
        }

        $receiverName    = $this->postTrim('receiver_name');
        $receiverPhone   = $this->postTrim('receiver_phone');
        $shippingAddress = $this->postTrim('shipping_address');
        $paymentMethod   = $this->postTrim('payment_method', 'COD');

        if ($userId <= 0 || empty($items) || $receiverName === '' || $receiverPhone === '' || $shippingAddress === '') {
            $this->flashSwal('warning', 'Thiếu thông tin', 'Vui lòng kiểm tra lại sản phẩm và địa chỉ.');
            $this->redirect('index.php?user=order');
        }

        $res = $this->orderModel->createOrderDirect(
            $userId,
            $items,
            $receiverName,
            $receiverPhone,
            $shippingAddress,
            $paymentMethod
        );

        if (!empty($res['success'])) {
            $orderId = (int)$res['order_id'];
            $totalVn = number_format((float)$res['total'], 0, ',', '.') . 'đ';
            $this->flashSwal('success', 'Đặt hàng thành công', "Mã đơn #$orderId • Tổng $totalVn");
            $this->redirect("index.php?user=order&id={$orderId}");
        }

        $this->flashSwal('error', 'Không thể tạo đơn', (string)($res['message'] ?? ''));
        $this->redirect('index.php?user=order');
    }

    public function actCancelOrder()
    {
        $orderId = $this->getInt($_REQUEST, 'id', 0);
        if ($orderId <= 0) {
            $this->flashSwal('warning', 'Thiếu mã đơn', 'Không xác định được đơn hàng.');
            $this->redirect('index.php?user=order');
        }

        $res = $this->orderModel->cancelOrder($orderId);
        if (!empty($res['success'])) {
            $this->flashSwal('success', 'Đã hủy đơn', "Mã đơn #$orderId đã được hủy.");
        } else {
            $this->flashSwal('error', 'Hủy đơn thất bại', (string)($res['message'] ?? ''));
        }
        $this->redirect("index.php?user=order&id={$orderId}");
    }

    public function actUpdatePayment()
    {
        $orderId = $this->getInt($_POST, 'id', 0);
        $status  = $this->postTrim('payment_status', 'paid'); // paid | unpaid

        if ($orderId <= 0) {
            $this->flashSwal('warning', 'Thiếu mã đơn', '');
            $this->redirect('index.php?user=order');
        }

        $ok = $this->orderModel->updatePaymentStatus($orderId, $status);
        if ($ok) {
            $this->flashSwal('success', 'Cập nhật thanh toán', "Đơn #$orderId → {$status}");
        } else {
            $this->flashSwal('error', 'Cập nhật thất bại', '');
        }
        $this->redirect("index.php?user=order&id={$orderId}");
    }

    public function actUpdateStatus()
    {
        $orderId = $this->getInt($_POST, 'id', 0);
        $status  = $this->postTrim('status', 'processing'); // pending|processing|shipped|delivered|cancelled

        if ($orderId <= 0) {
            $this->flashSwal('warning', 'Thiếu mã đơn', '');
            $this->redirect('index.php?user=order');
        }

        $ok = $this->orderModel->updateOrderStatus($orderId, $status);
        if ($ok) {
            $this->flashSwal('success', 'Cập nhật trạng thái', "Đơn #$orderId → {$status}");
        } else {
            $this->flashSwal('error', 'Cập nhật thất bại', '');
        }
        $this->redirect("index.php?user=order&id={$orderId}");
    }

    public function actAddWishlist()
    {
        $userId    = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        $variantId = $this->getInt($_REQUEST, 'variant_id', 0);

        if ($userId <= 0 || $variantId <= 0) {
            $this->flashSwal('warning', 'Thiếu thông tin', '');
            $this->redirect('index.php?user=wishlist');
        }

        $ok = $this->orderModel->addToWishlist($userId, $variantId);
        if ($ok) {
            $this->flashSwal('success', 'Đã thêm vào yêu thích', '');
        } else {
            $this->flashSwal('error', 'Không thể thêm', '');
        }
        $this->redirect('index.php?user=wishlist');
    }

    public function actRemoveWishlist()
    {
        $userId    = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        $variantId = $this->getInt($_REQUEST, 'variant_id', 0);

        if ($userId <= 0 || $variantId <= 0) {
            $this->flashSwal('warning', 'Thiếu thông tin', '');
            $this->redirect('index.php?user=wishlist');
        }

        $ok = $this->orderModel->removeFromWishlist($userId, $variantId);
        if ($ok) {
            $this->flashSwal('success', 'Đã xóa khỏi yêu thích', '');
        } else {
            $this->flashSwal('error', 'Không thể xóa', '');
        }
        $this->redirect('index.php?user=wishlist');
    }

    /* ================== Enrich items cho trang chi tiết ================== */
    private function fetchOrderItemsEnriched($orderId)
    {
        // Lấy items + thông tin variant cơ bản (sku, image_url)
        $sql = "SELECT 
                    oi.variant_id,
                    oi.quantity,
                    oi.price,
                    pv.sku,
                    pv.image_url,
                    COALESCE(pv.sale_price, pv.price) AS current_price
                FROM orderitems oi
                LEFT JOIN productvariants pv ON pv.id = oi.variant_id
                WHERE oi.order_id = :order_id";
        $stmt = $this->orderModel->db->pdo->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // (Tuỳ schema) thêm màu/size nếu cần – bạn có thể mở comment và sửa id thuộc tính:
        /*
        $sqlAttr = "SELECT av.value AS attr_value, av.attribute_id
                    FROM productvariantvalues vv
                    INNER JOIN attributevalues av ON av.id = vv.value_id
                    WHERE vv.variant_id = :variant_id";
        $stmtAttr = $this->orderModel->db->pdo->prepare($sqlAttr);

        foreach ($items as &$it) {
            if (!empty($it['variant_id'])) {
                $vid = (int)$it['variant_id'];
                $stmtAttr->bindParam(':variant_id', $vid, PDO::PARAM_INT);
                $stmtAttr->execute();
                $attrs = $stmtAttr->fetchAll(PDO::FETCH_ASSOC);
                foreach ($attrs as $a) {
                    if ((int)$a['attribute_id'] === 1) $it['color'] = $a['attr_value'];
                    if ((int)$a['attribute_id'] === 2) $it['size']  = $a['attr_value'];
                }
            }
        }
        unset($it);
        */

        return $items;
    }
}
