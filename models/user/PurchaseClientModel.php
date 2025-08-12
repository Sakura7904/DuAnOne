<?php

class PurchaseClientModel
{
    /** @var Database */
    public $db;

    /** Các trạng thái hợp lệ của đơn hàng (bám enum trong DB) */
    private array $allowedStatuses = [
        'pending',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
        'refunded'
    ];

    public function __construct()
    {
        // $this->db->pdo là PDO đã cấu hình
        $this->db = new Database();
    }

    /**
     * Lấy danh sách đơn của user đăng nhập, có thể lọc theo trạng thái.
     * - $status: null => tất cả; hoặc 1 trong các trạng thái hợp lệ.
     * - Trả về mảng gồm 'orders' và 'total' (phục vụ phân trang).
     */
    public function listOrdersByUser(
        int $userId,
        ?string $status = null,
        int $limit = 10,
        int $offset = 0
    ): array {
        // Đếm tổng
        if ($status && in_array($status, $this->allowedStatuses, true)) {
            $countSql = "SELECT COUNT(*) FROM orders WHERE user_id = :uid AND status = :status";
            $countStmt = $this->db->pdo->prepare($countSql);
            $countStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
            $countStmt->bindParam(':status', $status, PDO::PARAM_STR);
        } else {
            $countSql = "SELECT COUNT(*) FROM orders WHERE user_id = :uid";
            $countStmt = $this->db->pdo->prepare($countSql);
            $countStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        // Lấy danh sách đơn + tổng số lượng item trong đơn
        $sql = "
            SELECT 
                o.id,
                o.order_code,
                o.status,
                o.payment_status,
                o.payment_method,
                o.total_amount,
                o.created_at,
                o.updated_at,
                COALESCE(SUM(oi.quantity), 0) AS total_qty
            FROM orders o
            LEFT JOIN orderitems oi ON oi.order_id = o.id
            WHERE o.user_id = :uid" .
            ($status && in_array($status, $this->allowedStatuses, true) ? " AND o.status = :status" : "") . "
            GROUP BY o.id
            ORDER BY o.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        if ($status && in_array($status, $this->allowedStatuses, true)) {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'orders' => $orders,
            'total'  => $total,
        ];
    }


    /**
     * Thống kê số lượng đơn theo trạng thái cho 1 user.
     * Trả về mảng: ['pending'=>x, 'processing'=>y, ..., 'all'=>tổng].
     */
    public function getOrderStatusSummary(int $userId): array
    {
        $sql = "
            SELECT status, COUNT(*) AS cnt
            FROM orders
            WHERE user_id = :uid
            GROUP BY status
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [status => cnt]

        // Bảo đảm có đủ key cho mọi trạng thái
        $summary = [];
        foreach ($this->allowedStatuses as $st) {
            $summary[$st] = isset($rows[$st]) ? (int)$rows[$st] : 0;
        }
        $summary['all'] = array_sum($summary);

        return $summary;
    }

    /**
     * Lấy chi tiết item của 1 đơn (đảm bảo đơn thuộc về user).
     */
    public function getOrderItemsForUser(int $orderId, int $userId): array
    {
        $sql = "
            SELECT 
                oi.id AS order_item_id,
                oi.variant_id,
                oi.quantity,
                oi.price,                              -- giá chốt lúc đặt
                v.product_id,
                v.image_url AS variant_image,
                v.price       AS current_price,
                v.sale_price  AS current_sale_price,
                p.name        AS product_name,
                p.image_thumbnail AS product_thumbnail
            FROM orders o
            INNER JOIN orderitems oi ON oi.order_id = o.id
            LEFT JOIN productvariants v ON v.id = oi.variant_id
            LEFT JOIN products p ON p.id = v.product_id
            WHERE o.id = :oid AND o.user_id = :uid
            ORDER BY oi.id ASC
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tóm tắt 1 đơn (đảm bảo thuộc về user).
     */
    public function getOrderBriefForUser(int $orderId, int $userId): ?array
    {
        $sql = "
            SELECT 
                id, order_code, status, payment_status, payment_method,
                total_amount, created_at, updated_at
            FROM orders
            WHERE id = :oid AND user_id = :uid
            LIMIT 1
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Cho phép user hủy đơn khi trạng thái còn 'pending'.
     */
    public function cancelOrderItemByUser(int $orderItemId, int $userId): array
    {
        // Trạng thái item được phép hủy
        $cancelableStatuses = ['pending', 'confirmed']; // confirmed ~ đang xử lý

        $pdo = $this->db->pdo;
        $pdo->beginTransaction();
        try {
            // 1) Xác thực item thuộc đơn của user + kiểm tra trạng thái item
            $sql = "
            SELECT oi.id AS order_item_id, oi.order_id, oi.status AS item_status, o.user_id, o.status AS order_status
            FROM orderitems oi
            INNER JOIN orders o ON o.id = oi.order_id
            WHERE oi.id = :oiid AND o.user_id = :uid
            LIMIT 1
        ";
            $st = $pdo->prepare($sql);
            $st->bindParam(':oiid', $orderItemId, PDO::PARAM_INT);
            $st->bindParam(':uid', $userId, PDO::PARAM_INT);
            $st->execute();
            $row = $st->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $pdo->rollBack();
                return ['ok' => false, 'reason' => 'not_found'];
            }

            $orderId = (int)$row['order_id'];
            $itemStatus = (string)$row['item_status'];

            if (!in_array($itemStatus, $cancelableStatuses, true)) {
                $pdo->rollBack();
                return ['ok' => false, 'reason' => 'not_cancelable_item_status'];
            }

            // 2) Hủy item
            $upd = $pdo->prepare("UPDATE orderitems SET status = 'cancelled' WHERE id = :oiid LIMIT 1");
            $upd->bindParam(':oiid', $orderItemId, PDO::PARAM_INT);
            $upd->execute();

            if ($upd->rowCount() !== 1) {
                $pdo->rollBack();
                return ['ok' => false, 'reason' => 'update_failed'];
            }

            // 3) Kiểm tra còn item nào CHƯA hủy/refund trong đơn không
            $stillActive = $pdo->prepare("
            SELECT COUNT(*) 
            FROM orderitems 
            WHERE order_id = :oid 
              AND status NOT IN ('cancelled','refunded')
        ");
            $stillActive->bindParam(':oid', $orderId, PDO::PARAM_INT);
            $stillActive->execute();
            $remain = (int)$stillActive->fetchColumn();

            $orderCancelled = false;
            if ($remain === 0) {
                // 4) Tất cả item đã hủy/refund => cập nhật orders.status = cancelled
                $updOrder = $pdo->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = :oid LIMIT 1");
                $updOrder->bindParam(':oid', $orderId, PDO::PARAM_INT);
                $updOrder->execute();
                $orderCancelled = $updOrder->rowCount() === 1;
            }

            $pdo->commit();
            return ['ok' => true, 'order_id' => $orderId, 'order_cancelled' => $orderCancelled];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return ['ok' => false, 'reason' => 'exception', 'error' => $e->getMessage()];
        }
    }

    // Lấy items của nhiều đơn, có thể lọc theo trạng thái item
    public function getOrderItemsByOrderIds(
        int $userId,
        array $orderIds,
        ?array $itemStatuses = null // ví dụ ['pending'] hoặc ['pending','confirmed']
    ): array {
        $orderIds = array_values(array_filter(array_map('intval', $orderIds)));
        if (empty($orderIds)) return [];

        // placeholders :o0,:o1,...
        $ph = [];
        foreach ($orderIds as $i => $id) $ph[] = ":o{$i}";
        $inOrders = implode(',', $ph);

        $whereItem = '';
        if (!empty($itemStatuses)) {
            // :s0,:s1,...
            $sh = [];
            foreach ($itemStatuses as $i => $s) $sh[] = ":s{$i}";
            $inStatuses = implode(',', $sh);
            $whereItem = " AND oi.status IN ($inStatuses) ";
        }

        $sql = "
        SELECT
            o.id AS order_id,
            oi.id AS order_item_id,
            oi.quantity,
            oi.price,
            oi.status AS item_status,
            p.id   AS product_id,
            p.name AS product_name,
            COALESCE(p.image_thumbnail) AS image_url,
            MAX(CASE WHEN av.attribute_id = 1 THEN av.value END) AS color_name,
            MAX(CASE WHEN av.attribute_id = 2 THEN av.value END) AS size_name
        FROM orders o
        INNER JOIN orderitems oi ON oi.order_id = o.id
        LEFT JOIN productvariants v ON v.id = oi.variant_id
        LEFT JOIN products p        ON p.id = v.product_id
        LEFT JOIN productvariantvalues vv ON vv.variant_id = v.id
        LEFT JOIN attributevalues    av ON av.id = vv.value_id
        WHERE o.user_id = :uid
          AND o.id IN ($inOrders)
          $whereItem
        GROUP BY 
            o.id, oi.id, oi.quantity, oi.price, oi.status,
            p.id, p.name, v.image_url, p.image_thumbnail
        ORDER BY o.id, oi.id
    ";

        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':uid', $userId, PDO::PARAM_INT);
        foreach ($orderIds as $i => $id) $st->bindValue(":o{$i}", $id, PDO::PARAM_INT);
        if (!empty($itemStatuses)) {
            foreach ($itemStatuses as $i => $s) $st->bindValue(":s{$i}", $s, PDO::PARAM_STR);
        }
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        // group by order_id
        $by = [];
        foreach ($rows as $r) {
            // chuẩn hoá đường dẫn ảnh tương đối
            if (!empty($r['image_url']) && $r['image_url'][0] !== '/') {
                $r['image_url'] = '/' . ltrim($r['image_url'], '/');
            }
            $by[(int)$r['order_id']][] = $r;
        }
        return $by; // [order_id => [items...]]
    }


    /** Đổi status -> nhãn hiển thị tiếng Việt cho UI. */
    public function statusLabel(string $status): string
    {
        switch ($status) {
            case 'pending':
                return 'Chờ xác nhận';
            case 'processing':
                return 'Đang xử lý';
            case 'shipped':
                return 'Đang giao';
            case 'delivered':
                return 'Đã giao';
            case 'cancelled':
                return 'Đã hủy';
            case 'refunded':
                return 'Đã hoàn tiền';
            default:
                return ucfirst($status);
        }
    }

    public function getCustomerById(int $userId): ?array
    {
        $sql = "SELECT id, full_name, email, phone_number
            FROM users
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
