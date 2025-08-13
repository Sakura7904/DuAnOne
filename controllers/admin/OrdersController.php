<?php
include_once "models/admin/OrderAdminModel.php";

class OrdersController
{
    /** @var OrderAdminModel */
    private $model;

    public function __construct()
    {
        $this->model = new OrderAdminModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // ======================= LIST =======================

    /**
     * Danh sách đơn hàng (lọc & phân trang)
     * URL ví dụ:
     * ?admin=orders&status=pending&payment_status=unpaid&q=abc&page=1&limit=20&date_from=2025-08-01&date_to=2025-08-12
     */
    public function order(): void
    {
        $status        = $_GET['status'] ?? null;
        $paymentStatus = $_GET['payment_status'] ?? null;
        $q             = $_GET['q'] ?? null;
        $userId        = (int)($_GET['user_id'] ?? 0);
        $dateFrom      = $_GET['date_from'] ?? null;
        $dateTo        = $_GET['date_to'] ?? null;

        // >>> DÙNG 'pg' THAY VÌ 'page'
        $page  = max(1, (int)($_GET['pg'] ?? 1));
        $limit = max(1, min(100, (int)($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        $filters = [
            'status'         => $status ?: null,
            'payment_status' => $paymentStatus ?: null,
            'q'              => $q ?: null,
            'user_id'        => $userId ?: null,
            'date_from'      => $dateFrom ?: null,
            'date_to'        => $dateTo ?: null,
        ];

        $sort = $_GET['sort'] ?? 'o.created_at DESC';

        // >>> NHỚ TRUYỀN $limit, $offset VÀO MODEL
        $list   = $this->model->listOrders($filters, $limit, $offset, $sort);
        $orders = $list['orders'];
        $total  = (int)$list['total'];
        $pages  = (int)ceil($total / $limit);

        $counts = $this->model->countOrdersByStatus();

        $orderIds = array_column($orders, 'id');
        $summaryByOrder = $this->model->getItemsOfOrdersForSummary($orderIds);

        foreach ($orders as &$o) {
            $oid = (int)$o['id'];
            if (isset($summaryByOrder[$oid])) {
                $o = array_merge($o, $summaryByOrder[$oid]);
            }
        }
        unset($o);

        $itemStatusMap = $this->model->computeStatusForOrders($orderIds);
        foreach ($orders as &$o) {
            $oid = (int)$o['id'];
            $o['_status_for_view'] = $itemStatusMap[$oid] ?? ($o['status'] ?? 'pending');
        }
        unset($o);

        // >>> Đúng view của bạn: list-order
        $content = getContentPath('Orders', 'orderList');

        view('admin/master', [
            'content' => $content,
            'orders'  => $orders,
            'total'   => $total,
            'page'    => $page,
            'pages'   => $pages,
            'limit'   => $limit,
            'counts'  => $counts,
            'filters' => $filters,
            'sort'    => $sort,
        ]);
    }
    // Đổi trạng thái theo OrderItems (không cho set 'completed' từ admin)
    public function applyStatusByItems(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $_SESSION['flash_error'] = 'Phương thức không hợp lệ.';
            header('Location: ?admin=list-order');
            exit;
        }
        $orderId = (int)($_POST['order_id'] ?? 0);
        $status  = trim($_POST['status'] ?? '');

        if ($orderId <= 0 || $status === '') {
            $_SESSION['flash_error'] = 'Thiếu dữ liệu.';
            header('Location: ?admin=list-order');
            exit;
        }

        // dùng model applyStatusToOrderItems -> chỉ đổi ở bảng orderitems + recompute orders
        $ok = $this->model->applyStatusToOrderItems($orderId, $status);

        $_SESSION['flash_' . ($ok ? 'success' : 'error')] = $ok
            ? 'Đã cập nhật trạng thái các sản phẩm trong đơn.'
            : 'Cập nhật thất bại.';
        $ref = $_SERVER['HTTP_REFERER'] ?? '?admin=list-order';
        header('Location: ' . $ref);
        exit;
    }


    // =================== UPDATE ORDER STATUS ===================

    /**
     * POST: đổi trạng thái đơn (orders.status)
     * Form: order_id, status
     */
    public function updateOrderStatus(): void
    {
        $this->ensurePost();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $status  = trim($_POST['status'] ?? '');

        if ($orderId <= 0 || $status === '') {
            $_SESSION['flash_error'] = 'Thiếu dữ liệu.';
            $this->back();
        }

        // GỌI HÀM MỚI: đổi đơn + đẩy xuống item
        $ok = $this->model->updateOrderStatusWithItems($orderId, $status);

        $_SESSION['flash_success'] = $ok ? 'Cập nhật trạng thái đơn và sản phẩm trong đơn thành công.' : 'Cập nhật thất bại.';
        $this->back();
    }

    // =================== UPDATE ITEM STATUS ===================

    /**
     * POST: đổi trạng thái 1 item (orderitems.status)
     * Form: order_item_id, status
     * Tự đồng bộ lại orders.status theo luật trong model.
     */
    public function updateItemStatus(): void
    {
        $this->ensurePost();
        $itemId = (int)($_POST['order_item_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($itemId <= 0 || $status === '') {
            $_SESSION['flash_error'] = 'Thiếu dữ liệu.';
            $this->back();
        }

        $ok = $this->model->updateOrderItemStatus($itemId, $status);
        $_SESSION['flash_success'] = $ok ? 'Cập nhật trạng thái sản phẩm trong đơn thành công.' : 'Cập nhật trạng thái sản phẩm thất bại.';
        $this->back();
    }

    /**
     * POST: đổi trạng thái nhiều item (bulk)
     * Form: item_ids[] (array), status
     */
    public function bulkUpdateItemStatus(): void
    {
        $this->ensurePost();
        $itemIds = $_POST['item_ids'] ?? [];
        $status  = trim($_POST['status'] ?? '');

        if (!is_array($itemIds) || empty($itemIds) || $status === '') {
            $_SESSION['flash_error'] = 'Thiếu dữ liệu.';
            $this->back();
        }

        $affected = $this->model->bulkUpdateItemStatus($itemIds, $status);
        $_SESSION['flash_success'] = $affected > 0 ? "Đã cập nhật {$affected} sản phẩm trong đơn." : 'Không có sản phẩm nào được cập nhật.';
        $this->back();
    }

    // =================== PAYMENT ===================

    /**
     * POST: đổi trạng thái thanh toán của đơn
     * Form: order_id, payment_status (unpaid|paid)
     */
    public function updatePaymentStatus(): void
    {
        $this->ensurePost();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $pstatus = trim($_POST['payment_status'] ?? '');

        if ($orderId <= 0 || $pstatus === '') {
            $_SESSION['flash_error'] = 'Thiếu dữ liệu.';
            $this->back();
        }

        $ok = $this->model->updatePaymentStatus($orderId, $pstatus);
        $_SESSION['flash_success'] = $ok ? 'Đã cập nhật trạng thái thanh toán.' : 'Cập nhật trạng thái thanh toán thất bại.';
        $this->back();
    }

    // =================== UTIL ===================

    private function ensurePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $_SESSION['flash_error'] = 'Phương thức không hợp lệ.';
            $this->back();
        }
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '?admin=orders';
        header('Location: ' . $ref);
        exit;
    }
}
