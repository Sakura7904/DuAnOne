<?php

include_once "models/user/OrderModel.php";

class OrderController
{
    private $orderModel;

    // ==== MoMo Sandbox config ====
    private $momoEndpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
    private $momoPartnerCode;
    private $momoAccessKey;
    private $momoSecretKey;


    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->orderModel = new OrderModel();

        // Lấy từ ENV nếu có, không thì hardcode test key của bạn vào đây
        $this->momoPartnerCode = getenv('MOMO_PARTNER_CODE') ?: 'MOMOBKUN20180529';
        $this->momoAccessKey   = getenv('MOMO_ACCESS_KEY')   ?: 'klm05TvNBzhg7h7j';
        $this->momoSecretKey   = getenv('MOMO_SECRET_KEY')   ?: 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
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

    private function getInt($arr, $key, $default = 0)
    {
        return isset($arr[$key]) ? (int)$arr[$key] : $default;
    }

    private function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    private function postTrim($k, $d = '')
    {
        return isset($_POST[$k]) ? trim((string)$_POST[$k]) : $d;
    }

    private function httpPostJson($url, array $payload)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => 30,
        ]);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        return [$err ? null : json_decode($resp, true), $err ?: null];
    }

    // MoMo yêu cầu rawHash theo đúng thứ tự field như dưới (không sort)
    private function momoSignatureForCreate(array $p): string
    {
        $raw = "accessKey={$this->momoAccessKey}"
            . "&amount={$p['amount']}"
            . "&extraData={$p['extraData']}"
            . "&ipnUrl={$p['ipnUrl']}"
            . "&orderId={$p['orderId']}"
            . "&orderInfo={$p['orderInfo']}"
            . "&partnerCode={$this->momoPartnerCode}"
            . "&redirectUrl={$p['redirectUrl']}"
            . "&requestId={$p['requestId']}"
            . "&requestType={$p['requestType']}";
        return hash_hmac('sha256', $raw, $this->momoSecretKey);
    }

    // Tạo phiên MoMo cho THẺ: 'payWithATM' (Napas) / 'payWithCC' (Visa/Master/JCB)
    private function momoCreateCardPayment(int $orderId, int $amount, string $requestType, ?string $userEmail = null, ?string $phoneNumber = null)
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $base   = $scheme . '://' . $_SERVER['HTTP_HOST'];
        $redirectUrl = $base . '/index.php?user=actMomoReturn';
        $ipnUrl      = $base . '/index.php?user=actMomoIPN';

        // Min số tiền: ATM >= 10.000đ, CC >= 1.000đ
        if ($requestType === 'payWithATM' && $amount < 10000) $amount = 10000;
        if ($requestType === 'payWithCC'  && $amount < 1000) $amount = 1000;

        $requestId   = 'REQ_' . $orderId . '_' . time();
        $momoOrderId = 'ORD_' . $orderId . '_' . time();
        $orderInfo   = "Thanh toán đơn #$orderId";
        $extraData   = base64_encode(json_encode(['order_id' => $orderId], JSON_UNESCAPED_UNICODE));

        $payload = [
            'partnerCode' => $this->momoPartnerCode,
            'requestId'   => $requestId,
            'amount'      => (string)$amount,
            'orderId'     => $momoOrderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,         // 'payWithATM' | 'payWithCC'
        ];

        // userInfo: CC cần email; ATM nên truyền phone để issuer gửi OTP
        if ($requestType === 'payWithCC' && $userEmail)   $payload['userInfo'] = ['email' => $userEmail];
        if ($requestType === 'payWithATM' && $phoneNumber) $payload['userInfo'] = ['phoneNumber' => $phoneNumber];

        $payload['signature'] = $this->momoSignatureForCreate($payload);

        [$data, $err] = $this->httpPostJson($this->momoEndpoint, $payload);
        if ($err) {
            $_SESSION['order_alert'] = ['type' => 'error', 'message' => "MoMo lỗi: $err"];
            $this->redirect("index.php?user=order&id={$orderId}");
        }
        if (!empty($data['payUrl'])) {
            header('Location: ' . $data['payUrl']);
            exit;
        }
        $_SESSION['order_alert'] = ['type' => 'error', 'message' => 'Không tạo được payUrl MoMo (thẻ).'];
        $this->redirect("index.php?user=order&id={$orderId}");
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
        $paymentMethod   = strtoupper($this->postTrim('payment_method', 'COD')); // MOMO_ATM | MOMO_CC | COD
        $email           = $this->postTrim('customerEmail');

        if ($userId <= 0 || $receiverName === '' || $receiverPhone === '' || $shippingAddress === '') {
            $_SESSION['order_alert'] = ['type' => 'warning', 'message' => 'Vui lòng nhập đủ thông tin giao hàng.'];
            $this->redirect('index.php?user=order');
        }

        // Tạo đơn pending/unpaid trong DB
        $res = $this->orderModel->createOrderFromCart(
            $userId,
            $receiverName,
            $receiverPhone,
            $shippingAddress,
            $paymentMethod
        );
        if (empty($res['success'])) {
            $_SESSION['order_alert'] = ['type' => 'error', 'message' => 'Không thể tạo đơn' . (!empty($res['message']) ? ': ' . $res['message'] : '')];
            $this->redirect('index.php?user=order');
        }

        $orderId = (int)$res['order_id'];
        $total   = (int)round((float)$res['total']);

        if ($paymentMethod === 'MOMO_ATM') {
            // Napas – cần số điện thoại để issuer gửi OTP
            $this->momoCreateCardPayment($orderId, $total, 'payWithATM', null, $receiverPhone);
        } elseif ($paymentMethod === 'MOMO_CC') {
            // Visa/Master/JCB – nên truyền email
            $this->momoCreateCardPayment($orderId, $total, 'payWithCC', $email, null);
        } else {
            // COD (hoặc cổng khác)
            $_SESSION['order_alert'] = ['type' => 'success', 'message' => "Đặt hàng thành công. Mã đơn #$orderId • Tổng " . number_format($total, 0, ',', '.') . 'đ'];
            $this->redirect("index.php?user=order&id={$orderId}");
        }
    }

    // Người dùng quay về từ MoMo (trình duyệt)
    public function actMomoReturn()
    {
        $resultCode = isset($_GET['resultCode']) ? (int)$_GET['resultCode'] : -1;
        $amount     = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
        $transId    = $_GET['transId'] ?? '';
        $payType    = $_GET['payType'] ?? '';
        $extraData  = $_GET['extraData'] ?? '';

        $orderId = 0;
        if ($extraData) {
            $j = json_decode(base64_decode($extraData), true);
            if (!empty($j['order_id'])) $orderId = (int)$j['order_id'];
        }

        // ✅ Thành công => set paid ngay (idempotent, IPN vẫn chạy sau)
        if ($resultCode === 0 && $orderId > 0) {
            // dùng hàm có sẵn:
            $this->orderModel->updatePaymentStatus($orderId, 'paid');

            // (Nếu muốn lưu thêm transId/amount/payType, dùng hàm dưới trong Model)
            // $this->orderModel->markOrderPaid($orderId, $transId, $amount, $payType);
        }

        // Không tự động chuyển trang: render trang kết quả
        $content = getContentPathClient('', 'payment_result');
        view('user/index', [
            'content'   => $content,
            'isSuccess' => ($resultCode === 0),
            'orderId'   => $orderId,
            'amount'    => $amount,
            'orderInfo' => $_GET['orderInfo'] ?? '',
            'transId'   => $transId,
            'payType'   => $payType,
            'resultCode' => $resultCode,
        ]);
    }


    // IPN từ MoMo (server-to-server)
    public function actMomoIPN()
    {
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: [];

        $orderId = 0;
        if (!empty($data['extraData'])) {
            $j = json_decode(base64_decode($data['extraData']), true);
            if (!empty($j['order_id'])) $orderId = (int)$j['order_id'];
        }

        $resultCode = isset($data['resultCode']) ? (int)$data['resultCode'] : -1;

        if ($resultCode === 0 && $orderId > 0) {
            $this->orderModel->updatePaymentStatus($orderId, 'paid');
            http_response_code(200);
            echo 'OK';
            exit;
        }
        http_response_code(400);
        echo 'NOK';
        exit;
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
