<?php
include_once "models/user/PurchaseClientModel.php";

class PurchaseController
{
    private array $statusMap = [
        'all'       => null,
        'pending'   => 'pending',
        'processing' => 'processing',
        'shipping'  => 'shipped',     // map “Chờ giao hàng” sang shipped
        'done'      => 'delivered',   // map “Hoàn thành” sang delivered
        'cancelled' => 'cancelled',
        'refund'    => 'refunded',
    ];

    private array $tabToItemStatuses = [
        'all'       => null,                 // không lọc
        'pending'   => ['pending'],          // Chờ thanh toán (item)
        'processing' => ['confirmed'],        // Đang xử lý (item)
        'shipping'  => ['shipped'],          // Chờ giao hàng (item)
        'done'      => ['delivered'],        // Hoàn thành (item)
        'cancelled' => ['cancelled'],        // Đã hủy (item)
        'refund'    => ['refunded'],         // Trả hàng/Hoàn tiền (item)
    ];

    public function purchase()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ?user=login');
            exit;
        }
        $userId = (int)$_SESSION['user_id'];

        $tab = $_GET['status'] ?? 'all';
        if (!array_key_exists($tab, $this->tabToItemStatuses)) $tab = 'all';
        $itemStatuses = $this->tabToItemStatuses[$tab]; // dùng status của ITEM

        // Lấy tất cả đơn của user (đừng lọc theo orders.status nữa)
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $model = new PurchaseClientModel();

        // lấy order list (không filter theo orders.status)
        $list   = $model->listOrdersByUser($userId, null, $limit, $offset);
        $orders = $list['orders'];

        // lấy items theo filter item status
        $orderIds     = array_column($orders, 'id');
        $itemsByOrder = $model->getOrderItemsByOrderIds($userId, $orderIds, $itemStatuses);

        // loại các đơn không còn item sau khi filter
        $orders = array_values(array_filter($orders, function ($o) use ($itemsByOrder) {
            return !empty($itemsByOrder[$o['id']]);
        }));

        // (tuỳ bạn) đếm lại số lượng theo item-status cho tabs
        $counts = $model->getOrderStatusSummary($userId); // nếu cần đếm theo item, ta viết hàm khác

        $customer = $model->getCustomerById($userId);

        $content = getContentPathClient('', 'myPurchase');
        view('user/index', [
            'content'      => $content,
            'orders'       => $orders,
            'total'        => count($orders),  
            'counts'       => $counts,          
            'activeTab'    => $tab,
            'itemsByOrder' => $itemsByOrder,
            'customer'     => $customer, 
        ]);
    }

    public function cancelOrderItem(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ?user=login');
            exit;
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ?user=purchase');
            exit;
        }

        $userId      = (int)$_SESSION['user_id'];
        $orderItemId = (int)($_POST['order_item_id'] ?? 0);
        $tab         = $_GET['status'] ?? 'all'; // giữ tab hiện tại nếu bạn muốn

        if ($orderItemId <= 0) {
            $_SESSION['flash_error'] = 'Thiếu dòng sản phẩm cần hủy.';
            header('Location: ?user=purchase&status=' . $tab);
            exit;
        }

        $model = new PurchaseClientModel();
        $res = $model->cancelOrderItemByUser($orderItemId, $userId);

        if (!empty($res['ok'])) {
            if (!empty($res['order_cancelled'])) {
                $_SESSION['flash_success'] = 'Đã hủy đơn thành công.';
                header('Location: ?user=purchase&status=cancelled'); // chuyển sang tab Hủy
            } else {
                $_SESSION['flash_success'] = 'Đã hủy sản phẩm trong đơn.';
                header('Location: ?user=purchase&status=' . $tab);
            }
        } else {
            // map lý do
            $msg = match ($res['reason'] ?? '') {
                'not_found' => 'Không tìm thấy sản phẩm trong đơn của bạn.',
                'not_cancelable_item_status' => 'Dòng sản phẩm không còn ở trạng thái cho phép hủy.',
                default => 'Hủy sản phẩm thất bại.',
            };
            $_SESSION['flash_error'] = $msg;
            header('Location: ?user=purchase&status=' . $tab);
        }
        exit;
    }
}
