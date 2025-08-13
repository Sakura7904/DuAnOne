<?php

class OrderAdminModel
{
    /** @var Database */
    public $db;

    // Map nhanh để validate
    private array $orderStatuses = ['pending', 'processing', 'shipped', 'completed', 'delivered', 'cancelled', 'refunded'];
    private array $itemStatuses  = ['pending', 'confirmed', 'shipped', 'completed', 'delivered', 'cancelled', 'refunded'];
    private array $paymentStatuses = ['unpaid', 'paid'];

    public function __construct()
    {
        $this->db = new Database(); // $this->db->pdo là PDO
    }

    /* ===================== LIST / SEARCH ===================== */

    /**
     * Liệt kê đơn hàng với filter & phân trang.
     * $filters = [
     *   'status' => 'pending|processing|shipped|delivered|cancelled|refunded' (orders.status),
     *   'payment_status' => 'unpaid|paid',
     *   'q' => 'từ khoá (order_code, receiver_name, phone, email)',
     *   'user_id' => int,
     *   'date_from' => 'Y-m-d', 'date_to' => 'Y-m-d'
     * ]
     */
    public function listOrders(array $filters = [], int $limit = 20, int $offset = 0, string $sort = 'o.created_at DESC'): array
    {
        $sql = "
            SELECT
                o.id, o.order_code, o.total_amount, o.status, o.payment_method, o.payment_status,
                o.receiver_name, o.receiver_phone, o.shipping_address,
                o.created_at, o.updated_at,
                u.full_name AS user_name, u.email AS user_email, u.phone_number AS user_phone
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id
            WHERE 1=1
        ";
        $bind = [];

        if (!empty($filters['status']) && in_array($filters['status'], $this->orderStatuses, true)) {
            $sql .= " AND o.status = :status ";
            $bind[':status'] = $filters['status'];
        }

        if (!empty($filters['payment_status']) && in_array($filters['payment_status'], $this->paymentStatuses, true)) {
            $sql .= " AND o.payment_status = :pstatus ";
            $bind[':pstatus'] = $filters['payment_status'];
        }

        if (!empty($filters['user_id'])) {
            $sql .= " AND o.user_id = :uid ";
            $bind[':uid'] = (int)$filters['user_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND o.created_at >= :date_from ";
            $bind[':date_from'] = $filters['date_from'] . ' 00:00:00';
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND o.created_at <= :date_to ";
            $bind[':date_to'] = $filters['date_to'] . ' 23:59:59';
        }

        if (!empty($filters['q'])) {
            $sql .= " AND (
                o.order_code LIKE :q
                OR o.receiver_name LIKE :q
                OR o.receiver_phone LIKE :q
                OR u.email LIKE :q
                OR u.full_name LIKE :q
            )";
            $bind[':q'] = '%' . $filters['q'] . '%';
        }

        // Count tổng
        $countSql = "SELECT COUNT(*) FROM ($sql) t";
        $stmt = $this->db->pdo->prepare($countSql);
        foreach ($bind as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        $total = (int)$stmt->fetchColumn();

        // Sort + limit
        $allowedSorts = ['o.created_at DESC', 'o.created_at ASC', 'o.total_amount DESC', 'o.total_amount ASC'];
        if (!in_array($sort, $allowedSorts, true)) $sort = 'o.created_at DESC';
        $sql .= " ORDER BY $sort LIMIT :limit OFFSET :offset";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($bind as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['orders' => $orders, 'total' => $total];
    }

    public function getItemsOfOrdersForSummary(array $orderIds): array
    {
        $orderIds = array_values(array_filter(array_map('intval', $orderIds)));
        if (empty($orderIds)) return [];

        // placeholder ?,?,?
        $in = implode(',', array_fill(0, count($orderIds), '?'));

        $sql = "
      SELECT
        oi.order_id,
        oi.id AS order_item_id,
        oi.quantity,
        oi.price,                       -- đơn giá tại thời điểm đặt
        p.name AS product_name,
        MAX(CASE WHEN av.attribute_id = 1 THEN av.value END) AS color_name,
        MAX(CASE WHEN av.attribute_id = 2 THEN av.value END) AS size_name
      FROM orderitems oi
      LEFT JOIN productvariants v ON v.id = oi.variant_id
      LEFT JOIN products p        ON p.id = v.product_id
      LEFT JOIN productvariantvalues vv ON vv.variant_id = v.id
      LEFT JOIN attributevalues av      ON av.id = vv.value_id
      WHERE oi.order_id IN ($in)
      GROUP BY oi.order_id, oi.id, oi.quantity, oi.price, p.name
      ORDER BY oi.order_id, oi.id
    ";
        $st = $this->db->pdo->prepare($sql);
        foreach ($orderIds as $i => $id) $st->bindValue($i + 1, $id, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        // chọn item đầu tiên + đếm tất cả item của từng đơn
        $by = [];
        foreach ($rows as $r) {
            $oid = (int)$r['order_id'];
            if (!isset($by[$oid])) {
                $by[$oid] = [
                    'first_product_name' => $r['product_name'],
                    'first_color'        => $r['color_name'] ?? null,
                    'first_size'         => $r['size_name'] ?? null,
                    'first_qty'          => (int)$r['quantity'],
                    'first_price'        => (float)$r['price'],  // đơn giá
                    'items_count'        => 0,
                ];
            }
            $by[$oid]['items_count']++;
        }
        return $by; // [order_id => summary...]
    }

    public function updateOrderStatusWithItems(int $orderId, string $orderStatus): bool
    {
        // admin không được set completed
        if ($orderStatus === 'completed') return false;

        $validOrder = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($orderStatus, $validOrder, true)) return false;

        $pdo = $this->db->pdo;
        $pdo->beginTransaction();
        try {
            // update order
            $uo = $pdo->prepare("UPDATE orders SET status = :st, updated_at = NOW() WHERE id = :oid LIMIT 1");
            $uo->execute([':st' => $orderStatus, ':oid' => $orderId]);

            // map xuống item (KHÔNG có completed)
            $targetItemStatus = null;
            $eligibleFrom = [];
            switch ($orderStatus) {
                case 'processing':
                    $targetItemStatus = 'processing';
                    $eligibleFrom = ['pending', 'confirmed'];
                    break;
                case 'shipped':
                    $targetItemStatus = 'shipped';
                    $eligibleFrom = ['pending', 'confirmed', 'processing'];
                    break;
                case 'delivered':
                    $targetItemStatus = 'delivered';
                    $eligibleFrom = ['pending', 'confirmed', 'processing', 'shipped'];
                    break;
                case 'cancelled':
                    $targetItemStatus = 'cancelled';
                    $eligibleFrom = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                    break;
                case 'pending':    /* không ép ngược item */
                    break;
            }

            if ($targetItemStatus && $eligibleFrom) {
                $in = implode(',', array_fill(0, count($eligibleFrom), '?'));
                $params = array_merge([$targetItemStatus, $orderId], $eligibleFrom);
                $this->db->pdo->prepare("UPDATE orderitems SET status = ? WHERE order_id = ? AND status IN ($in)")
                    ->execute($params);
            }

            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function computeStatusForOrders(array $orderIds): array
    {
        $orderIds = array_values(array_filter(array_map('intval', $orderIds)));
        if (!$orderIds) return [];

        $in  = implode(',', array_fill(0, count($orderIds), '?'));
        $sql = "
      SELECT 
        oi.order_id,
        SUM(oi.status = 'completed')  AS c_completed,
        SUM(oi.status = 'delivered')  AS c_delivered,
        SUM(oi.status = 'cancelled')  AS c_cancelled,
        SUM(oi.status = 'shipped')    AS c_shipped,
        SUM(oi.status = 'processing') AS c_processing,
        SUM(oi.status = 'confirmed')  AS c_confirmed,
        SUM(oi.status = 'pending')    AS c_pending,
        COUNT(*)                      AS total_items
      FROM orderitems oi
      WHERE oi.order_id IN ($in)
      GROUP BY oi.order_id
    ";
        $st = $this->db->pdo->prepare($sql);
        foreach ($orderIds as $i => $id) $st->bindValue($i + 1, $id, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $out = [];
        foreach ($rows as $r) {
            $oid   = (int)$r['order_id'];
            $total = (int)$r['total_items'];

            if ((int)$r['c_completed'] === $total)       $out[$oid] = 'completed';
            elseif ((int)$r['c_delivered'] === $total)   $out[$oid] = 'delivered';
            elseif ((int)$r['c_cancelled'] === $total)   $out[$oid] = 'cancelled';
            elseif ((int)$r['c_shipped'] > 0)            $out[$oid] = 'shipped';
            elseif ((int)$r['c_processing'] > 0 || (int)$r['c_confirmed'] > 0)
                $out[$oid] = 'processing';
            elseif ((int)$r['c_pending'] > 0)            $out[$oid] = 'pending';
            else                                         $out[$oid] = 'processing';
        }
        return $out; // [order_id => status_for_view]
    }

    /**
     * Đếm số đơn theo orders.status (cho tab/thống kê admin)
     */
    public function countOrdersByStatus(): array
    {
        $sql = "SELECT o.status, COUNT(*) AS cnt FROM orders o GROUP BY o.status";
        $rows = $this->db->pdo->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
        $result = [];
        foreach ($this->orderStatuses as $s) $result[$s] = (int)($rows[$s] ?? 0);
        $result['all'] = array_sum($result);
        return $result;
    }

    // ===================== APPLY STATUS TO ITEMS (ADMIN) =====================
    public function applyStatusToOrderItems(int $orderId, string $target): bool
    {
        // Admin KHÔNG được set 'completed' hay 'refunded'
        $allow = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if ($orderId <= 0 || !in_array($target, $allow, true)) return false;

        // Xác định các trạng thái item được phép chuyển từ
        switch ($target) {
            case 'processing':
                $eligibleFrom = ['pending', 'confirmed'];
                break;
            case 'shipped':
                $eligibleFrom = ['pending', 'confirmed', 'processing'];
                break;
            case 'delivered':
                $eligibleFrom = ['pending', 'confirmed', 'processing', 'shipped'];
                break;
            case 'cancelled':
                // nếu không muốn huỷ item đã 'delivered' thì bỏ 'delivered' ra khỏi mảng này
                $eligibleFrom = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                break;
            case 'pending':
            default:
                // Không ép ngược item về pending (tránh mâu thuẫn dữ liệu)
                $eligibleFrom = [];
                break;
        }

        $pdo = $this->db->pdo;
        $pdo->beginTransaction();
        try {
            if (!empty($eligibleFrom)) {
                $in = implode(',', array_fill(0, count($eligibleFrom), '?'));
                $sql = "UPDATE orderitems SET status = ? WHERE order_id = ? AND status IN ($in)";
                $params = array_merge([$target, $orderId], $eligibleFrom);
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
            }

            // Đồng bộ lại trạng thái đơn dựa trên toàn bộ items (có completed)
            $this->recomputeOrderStatus($orderId);

            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    /* ===================== DETAIL ===================== */

    /**
     * Lấy chi tiết đơn + items (kèm ảnh, màu/size)
     */
    public function getOrderWithItems(int $orderId): ?array
    {
        // Thông tin đơn
        $orderSql = "
            SELECT
                o.*, u.full_name AS user_name, u.email AS user_email, u.phone_number AS user_phone
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id
            WHERE o.id = :oid
            LIMIT 1
        ";
        $st = $this->db->pdo->prepare($orderSql);
        $st->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $st->execute();
        $order = $st->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        // Items của đơn
        $itemSql = "
            SELECT
                oi.id AS order_item_id, oi.order_id, oi.variant_id, oi.quantity, oi.price, oi.status AS item_status,
                p.id AS product_id, p.name AS product_name,
                COALESCE(v.image_url, p.image_thumbnail) AS image_url,
                MAX(CASE WHEN av.attribute_id = 1 THEN av.value END) AS color_name,
                MAX(CASE WHEN av.attribute_id = 2 THEN av.value END) AS size_name
            FROM orderitems oi
            LEFT JOIN productvariants v ON v.id = oi.variant_id
            LEFT JOIN products p ON p.id = v.product_id
            LEFT JOIN productvariantvalues vv ON vv.variant_id = v.id
            LEFT JOIN attributevalues av ON av.id = vv.value_id
            WHERE oi.order_id = :oid
            GROUP BY 
                oi.id, oi.order_id, oi.variant_id, oi.quantity, oi.price, oi.status,
                p.id, p.name, v.image_url, p.image_thumbnail
            ORDER BY oi.id
        ";
        $ist = $this->db->pdo->prepare($itemSql);
        $ist->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $ist->execute();
        $items = $ist->fetchAll(PDO::FETCH_ASSOC);

        $order['items'] = $items;
        return $order;
    }

    /* ===================== UPDATE STATUS ===================== */

    /**
     * Đổi trạng thái đơn (orders.status)
     */
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        if (!in_array($status, $this->orderStatuses, true)) return false;
        $sql = "UPDATE orders SET status = :st, updated_at = NOW() WHERE id = :oid LIMIT 1";
        $st = $this->db->pdo->prepare($sql);
        $st->bindParam(':st', $status, PDO::PARAM_STR);
        $st->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $st->execute();
        return $st->rowCount() === 1;
    }

    /**
     * Đổi trạng thái 1 item (orderitems.status) và đồng bộ trạng thái đơn nếu cần
     * - Nếu TẤT CẢ item đều delivered => set đơn delivered
     * - Nếu TẤT CẢ item đều cancelled/refunded => set đơn cancelled
     * - Nếu có mix (vài cancelled, vài confirmed/…): set đơn processing (tuỳ luật)
     */
    public function updateOrderItemStatus(int $orderItemId, string $status): bool
    {
        if (!in_array($status, $this->itemStatuses, true)) return false;

        $pdo = $this->db->pdo;
        $pdo->beginTransaction();
        try {
            // orderId của item
            $q = $pdo->prepare("SELECT order_id FROM orderitems WHERE id = :iid LIMIT 1");
            $q->bindParam(':iid', $orderItemId, PDO::PARAM_INT);
            $q->execute();
            $orderId = (int)$q->fetchColumn();
            if (!$orderId) {
                $pdo->rollBack();
                return false;
            }

            // Cập nhật item
            $u = $pdo->prepare("UPDATE orderitems SET status = :st WHERE id = :iid LIMIT 1");
            $u->bindParam(':st', $status, PDO::PARAM_STR);
            $u->bindParam(':iid', $orderItemId, PDO::PARAM_INT);
            $u->execute();

            if ($u->rowCount() !== 1) {
                $pdo->rollBack();
                return false;
            }

            // Lấy phân bố trạng thái item trong đơn
            $s = $pdo->prepare("
                SELECT status, COUNT(*) AS c
                FROM orderitems
                WHERE order_id = :oid
                GROUP BY status
            ");
            $s->bindParam(':oid', $orderId, PDO::PARAM_INT);
            $s->execute();
            $stats = $s->fetchAll(PDO::FETCH_KEY_PAIR);

            $allCnt = array_sum(array_map('intval', $stats));

            // Luật đồng bộ đơn
            $newOrderStatus = null;
            if (($stats['delivered'] ?? 0) == $allCnt) {
                $newOrderStatus = 'delivered';
            } elseif ((($stats['cancelled'] ?? 0) + ($stats['refunded'] ?? 0)) == $allCnt) {
                $newOrderStatus = 'cancelled';
            } elseif (!empty($stats['shipped'])) {
                $newOrderStatus = 'shipped';
            } elseif (!empty($stats['confirmed'])) {
                $newOrderStatus = 'processing';
            } elseif (!empty($stats['pending'])) {
                $newOrderStatus = 'pending';
            } else {
                // Nếu không rơi vào các case trên, mặc định giữ nguyên
                $newOrderStatus = null;
            }

            if ($newOrderStatus) {
                $uo = $pdo->prepare("UPDATE orders SET status = :os, updated_at = NOW() WHERE id = :oid LIMIT 1");
                $uo->bindParam(':os', $newOrderStatus, PDO::PARAM_STR);
                $uo->bindParam(':oid', $orderId, PDO::PARAM_INT);
                $uo->execute();
            }

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    /**
     * Bulk đổi trạng thái nhiều items một lúc (VD: xác nhận/hủy loạt)
     */
    public function bulkUpdateItemStatus(array $itemIds, string $status): int
    {
        $itemIds = array_values(array_filter(array_map('intval', $itemIds)));
        if (empty($itemIds) || !in_array($status, $this->itemStatuses, true)) return 0;

        $pdo = $this->db->pdo;
        $pdo->beginTransaction();
        try {
            // Cập nhật các item
            $in = implode(',', array_fill(0, count($itemIds), '?'));
            $u = $pdo->prepare("UPDATE orderitems SET status = ? WHERE id IN ($in)");
            $params = array_merge([$status], $itemIds);
            $u->execute($params);
            $affected = $u->rowCount();

            // Đồng bộ trạng thái từng đơn bị ảnh hưởng
            $getOrders = $pdo->prepare("SELECT DISTINCT order_id FROM orderitems WHERE id IN ($in)");
            $getOrders->execute($itemIds);
            $orderIds = $getOrders->fetchAll(PDO::FETCH_COLUMN, 0);

            foreach ($orderIds as $oid) $this->recomputeOrderStatus((int)$oid);

            $pdo->commit();
            return $affected;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return 0;
        }
    }

    /**
     * Tính lại orders.status dựa trên toàn bộ item của đơn (dùng lại luật ở trên)
     */
    public function recomputeOrderStatus(int $orderId): void
    {
        $pdo = $this->db->pdo;
        $s = $pdo->prepare("SELECT status, COUNT(*) c FROM orderitems WHERE order_id = :oid GROUP BY status");
        $s->execute([':oid' => $orderId]);
        $stats = $s->fetchAll(PDO::FETCH_KEY_PAIR);
        if (!$stats) return;

        $all = array_sum(array_map('intval', $stats));
        $new = null;

        if (($stats['completed'] ?? 0) == $all) {
            $new = 'completed';         // tất cả item đã Hoàn thành
        } elseif (($stats['delivered'] ?? 0) == $all) {
            $new = 'delivered';
        } elseif ((($stats['cancelled'] ?? 0) + 0) == $all) {
            $new = 'cancelled';
        } elseif (!empty($stats['shipped'])) {
            $new = 'shipped';
        } elseif (!empty($stats['processing']) || !empty($stats['confirmed'])) {
            $new = 'processing';
        } elseif (!empty($stats['pending'])) {
            $new = 'pending';
        }

        if ($new) {
            $pdo->prepare("UPDATE orders SET status = :st, updated_at = NOW() WHERE id = :oid LIMIT 1")
                ->execute([':st' => $new, ':oid' => $orderId]);
        }
    }

    /* ===================== PAYMENT ===================== */

    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool
    {
        if (!in_array($paymentStatus, $this->paymentStatuses, true)) return false;
        $sql = "UPDATE orders SET payment_status = :ps, updated_at = NOW() WHERE id = :oid LIMIT 1";
        $st = $this->db->pdo->prepare($sql);
        $st->bindParam(':ps', $paymentStatus, PDO::PARAM_STR);
        $st->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $st->execute();
        return $st->rowCount() === 1;
    }

    /* ===================== UTIL ===================== */

    /** Lấy danh sách item của một đơn (để render bảng admin) */
    public function getItemsOfOrder(int $orderId): array
    {
        $sql = "
            SELECT
                oi.id AS order_item_id, oi.order_id, oi.variant_id, oi.quantity, oi.price, oi.status AS item_status,
                p.id AS product_id, p.name AS product_name,
                COALESCE(v.image_url, p.image_thumbnail) AS image_url,
                MAX(CASE WHEN av.attribute_id = 1 THEN av.value END) AS color_name,
                MAX(CASE WHEN av.attribute_id = 2 THEN av.value END) AS size_name
            FROM orderitems oi
            LEFT JOIN productvariants v ON v.id = oi.variant_id
            LEFT JOIN products p ON p.id = v.product_id
            LEFT JOIN productvariantvalues vv ON vv.variant_id = v.id
            LEFT JOIN attributevalues av ON av.id = vv.value_id
            WHERE oi.order_id = :oid
            GROUP BY 
                oi.id, oi.order_id, oi.variant_id, oi.quantity, oi.price, oi.status,
                p.id, p.name, v.image_url, p.image_thumbnail
            ORDER BY oi.id
        ";
        $st = $this->db->pdo->prepare($sql);
        $st->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            if (!empty($r['image_url']) && $r['image_url'][0] !== '/') {
                $r['image_url'] = '/' . ltrim($r['image_url'], '/');
            }
        }
        return $rows;
    }
}
