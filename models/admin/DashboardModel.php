<?php

class DashboardModel
{
    /** @var Database */
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // $this->db->pdo là PDO
    }



    /* ===================== Helper ===================== */

    /**
     * Tạo mảng ngày liên tục (Y-m-d) từ $from đến $to (bao gồm cả 2 đầu).
     */
    private function buildDateRange(string $from, string $to): array
    {
        $labels = [];
        $d = new DateTime($from);
        $end = new DateTime($to);
        while ($d <= $end) {
            $labels[] = $d->format('Y-m-d');
            $d->modify('+1 day');
        }
        return $labels;
    }


    /* ===================== Thống kê cho biểu đồ ===================== */

    /**
     * Doanh thu theo ngày (sum oi.quantity * oi.price), chỉ tính đơn đã thanh toán.
     * Trả về: ['labels'=>[Y-m-d...], 'data'=>[revenue...]]
     */
    // DashboardModel.php
    public function getRevenueByDay(string $fromDate, string $toDate): array
    {
        $sql = "
        SELECT DATE(o.created_at) AS d,
               SUM(oi.quantity * oi.price) AS revenue
        FROM orders o
        JOIN orderitems oi ON oi.order_id = o.id
        WHERE LOWER(TRIM(oi.status)) IN ('completed','delivered')
          AND o.created_at >= :from
          AND o.created_at <  DATE_ADD(:to, INTERVAL 1 DAY)
        GROUP BY DATE(o.created_at)
        ORDER BY DATE(o.created_at)
    ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        // đưa về mảng theo ngày liên tục, ngày không có dữ liệu = 0
        $map = [];
        foreach ($rows as $r) $map[$r['d']] = (float)$r['revenue'];

        $labels = $this->buildDateRange($fromDate, $toDate);
        $data   = [];
        foreach ($labels as $d) $data[] = $map[$d] ?? 0.0;

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Số đơn theo ngày (tất cả trạng thái, nhưng chỉ đơn đã thanh toán nếu $paidOnly=true).
     * Trả về: ['labels'=>[Y-m-d...], 'data'=>[count...]]
     */
    public function getOrderCountByDay(string $fromDate, string $toDate, bool $paidOnly = true): array
    {
        $condPaid = $paidOnly ? "AND o.payment_status='paid'" : "";
        $sql = "
            SELECT DATE(o.created_at) AS d, COUNT(*) AS cnt
            FROM orders o
            WHERE o.created_at BETWEEN :from AND :to
              $condPaid
            GROUP BY DATE(o.created_at)
            ORDER BY DATE(o.created_at)
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $byDay = [];
        foreach ($rows as $r) {
            $byDay[$r['d']] = (int)$r['cnt'];
        }
        $labels = $this->buildDateRange($fromDate, $toDate);
        $data   = [];
        foreach ($labels as $d) {
            $data[] = $byDay[$d] ?? 0;
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Số đơn theo trạng thái (pending/processing/shipped/delivered/completed/cancelled).
     * Nếu $paidOnly=true thì chỉ đếm đơn đã thanh toán.
     * Trả về: ['labels'=>[status...], 'data'=>[count...]]
     */
    public function getOrderCountByStatus(string $fromDate, string $toDate, bool $paidOnly = false): array
    {
        $condPaid = $paidOnly ? "AND o.payment_status='paid'" : "";
        $sql = "
            SELECT o.status, COUNT(*) AS cnt
            FROM orders o
            WHERE o.created_at BETWEEN :from AND :to
              $condPaid
            GROUP BY o.status
            ORDER BY o.status
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data   = [];
        foreach ($rows as $r) {
            $labels[] = $r['status'];
            $data[]   = (int)$r['cnt'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Tỉ trọng phương thức thanh toán (chỉ đơn đã thanh toán).
     * Trả về: ['labels'=>[method...], 'data'=>[count...]]
     */
    public function getPaymentMethodShare(string $fromDate, string $toDate): array
    {
        $sql = "
            SELECT o.payment_method, COUNT(*) AS cnt
            FROM orders o
            WHERE o.payment_status='paid'
              AND o.created_at BETWEEN :from AND :to
            GROUP BY o.payment_method
            ORDER BY cnt DESC
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data   = [];
        foreach ($rows as $r) {
            $labels[] = $r['payment_method'] ?: 'N/A';
            $data[]   = (int)$r['cnt'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Top sản phẩm bán chạy theo doanh thu hoặc số lượng.
     * $by = 'revenue' | 'quantity'
     * Trả về: ['labels'=>[product_name...], 'data'=>[value...]]
     */
    public function getTopProducts(string $fromDate, string $toDate, int $limit = 10, string $by = 'revenue'): array
    {
        $metric = ($by === 'quantity') ? "SUM(oi.quantity)" : "SUM(oi.quantity * oi.price)";
        $sql = "
            SELECT p.id, p.name,
                   $metric AS val
            FROM orders o
            JOIN orderitems oi ON oi.order_id = o.id
            JOIN productvariants v ON v.id = oi.variant_id
            JOIN products p ON p.id = v.product_id
            WHERE o.payment_status='paid'
              AND oi.status NOT IN ('cancelled','refunded')
              AND o.created_at BETWEEN :from AND :to
            GROUP BY p.id, p.name
            ORDER BY val DESC
            LIMIT :lim
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->bindValue(':from', $fromDate);
        $stm->bindValue(':to', $toDate);
        $stm->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data   = [];
        foreach ($rows as $r) {
            $labels[] = $r['name'];
            $data[]   = ($by === 'quantity') ? (int)$r['val'] : (float)$r['val'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Doanh thu theo danh mục.
     * Trả về: ['labels'=>[category...], 'data'=>[revenue...]]
     */
    public function getRevenueByCategory(string $fromDate, string $toDate): array
    {
        $sql = "
            SELECT c.id, c.name,
                   SUM(oi.quantity * oi.price) AS revenue
            FROM orders o
            JOIN orderitems oi ON oi.order_id = o.id
            JOIN productvariants v ON v.id = oi.variant_id
            JOIN products p ON p.id = v.product_id
            JOIN categories c ON c.id = p.category_id
            WHERE o.payment_status='paid'
              AND oi.status NOT IN ('cancelled','refunded')
              AND o.created_at BETWEEN :from AND :to
            GROUP BY c.id, c.name
            ORDER BY revenue DESC
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data   = [];
        foreach ($rows as $r) {
            $labels[] = $r['name'] ?: 'N/A';
            $data[]   = (float)$r['revenue'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Average Order Value (AOV) trong khoảng thời gian (chỉ đơn đã thanh toán).
     * Trả về: float
     */
    public function getAOV(string $fromDate, string $toDate): float
    {
        $sql = "
            SELECT AVG(o.total_amount) AS aov
            FROM orders o
            WHERE o.payment_status='paid'
              AND o.created_at BETWEEN :from AND :to
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $fromDate, ':to' => $toDate]);
        $val = $stm->fetchColumn();
        return (float)($val ?: 0.0);
    }

    /**
     * Top biến thể (màu/size) theo số lượng bán.
     * Trả về: ['labels'=>["Tên SP - (màu/size)"], 'data'=>[qty...]]
     * Lưu ý: hiển thị giá trị biến thể (màu/size) dựa trên attributevalues.
     */
    public function getTopVariantsByQty(string $fromDate, string $toDate, int $limit = 10): array
    {
        // Lấy text biến thể: gom value (ví dụ: Đen, M) theo variant
        $sql = "
            SELECT v.id AS variant_id,
                   p.name AS product_name,
                   GROUP_CONCAT(av.value ORDER BY av.attribute_id SEPARATOR ' / ') AS variant_text,
                   SUM(oi.quantity) AS qty
            FROM orders o
            JOIN orderitems oi ON oi.order_id = o.id
            JOIN productvariants v ON v.id = oi.variant_id
            JOIN products p ON p.id = v.product_id
            LEFT JOIN productvariantvalues pvv ON pvv.variant_id = v.id
            LEFT JOIN attributevalues av ON av.id = pvv.value_id
            WHERE o.payment_status='paid'
              AND oi.status NOT IN ('cancelled','refunded')
              AND o.created_at BETWEEN :from AND :to
            GROUP BY v.id, p.name
            ORDER BY qty DESC
            LIMIT :lim
        ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->bindValue(':from', $fromDate);
        $stm->bindValue(':to', $toDate);
        $stm->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data   = [];
        foreach ($rows as $r) {
            $label = $r['product_name'];
            if (!empty($r['variant_text'])) {
                $label .= ' — ' . $r['variant_text'];
            }
            $labels[] = $label;
            $data[]   = (int)$r['qty'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    public function getSimpleKpis(string $fromDate, string $toDate): array
    {
        $from = $fromDate . (strlen($fromDate) === 10 ? ' 00:00:00' : '');
        $to   = $toDate   . (strlen($toDate)   === 10 ? ' 23:59:59' : '');

        $sqlRevenueItems = "
        SELECT
            COALESCE(SUM(oi.quantity * oi.price), 0) AS revenue,
            COALESCE(SUM(oi.quantity), 0)           AS items
        FROM orders o
        JOIN orderitems oi ON oi.order_id = o.id
        WHERE o.created_at BETWEEN :from AND :to
          AND LOWER(TRIM(oi.status)) IN ('completed','delivered')
    ";
        $stm1 = $this->db->pdo->prepare($sqlRevenueItems);
        $stm1->execute([':from' => $from, ':to' => $to]);
        $ri = $stm1->fetch(PDO::FETCH_ASSOC) ?: ['revenue' => 0, 'items' => 0];

        $sqlOrdersCompleted = "
        SELECT COUNT(*) AS orders
        FROM orders o
        WHERE o.created_at BETWEEN :from AND :to
          AND NOT EXISTS (
            SELECT 1
            FROM orderitems oi
            WHERE oi.order_id = o.id
              AND LOWER(TRIM(oi.status)) NOT IN ('completed','delivered')
          )
    ";
        $stm2 = $this->db->pdo->prepare($sqlOrdersCompleted);
        $stm2->execute([':from' => $from, ':to' => $to]);
        $orders = (int)($stm2->fetchColumn() ?: 0);

        $revenue = (float)$ri['revenue'];
        $items   = (int)$ri['items'];
        $aov     = $orders > 0 ? $revenue / $orders : 0.0;
        $sqlPending = "
        SELECT COUNT(DISTINCT o.id) AS pending_orders
        FROM orders o
        WHERE o.created_at BETWEEN :from AND :to
          AND EXISTS (
            SELECT 1 FROM orderitems oi
            WHERE oi.order_id = o.id
              AND LOWER(TRIM(oi.status)) IN ('pending','processing')
          )
    ";
        $stm3 = $this->db->pdo->prepare($sqlPending);
        $stm3->execute([':from' => $from, ':to' => $to]);
        $pending = (int)($stm3->fetchColumn() ?: 0);

        return [
            'revenue'        => $revenue,
            'items'          => $items,
            'orders'         => $orders,
            'aov'            => $aov,
            'pending_orders' => $pending,
        ];
    }

    public function getTopCustomers(string $fromDate, string $toDate, int $limit = 6): array
    {
        // Top khách theo tổng tiền các item đã hoàn thành (completed/delivered)
        // Gom theo user_id; nếu user_id NULL => gom nhóm "Khách vãng lai"
        $sql = "
      SELECT
        IFNULL(o.user_id, 0)                                   AS uid,
        CASE
          WHEN o.user_id IS NULL THEN 'Khách vãng lai'
          WHEN u.full_name IS NOT NULL THEN u.full_name
          ELSE CONCAT('User #', o.user_id)
        END                                                   AS name,
        SUM(oi.quantity * oi.price)                           AS revenue,
        COUNT(DISTINCT o.id)                                  AS orders,
        SUM(oi.quantity)                                      AS items
      FROM orders o
      JOIN orderitems oi ON oi.order_id = o.id
      LEFT JOIN users u   ON u.id = o.user_id
      WHERE LOWER(TRIM(oi.status)) IN ('completed','delivered')
        AND o.created_at >= :from
        AND o.created_at <  DATE_ADD(:to, INTERVAL 1 DAY)
      GROUP BY uid, name
      ORDER BY revenue DESC
      LIMIT :limit
    ";

        $stm = $this->db->pdo->prepare($sql);
        $stm->bindValue(':from',  $fromDate);
        $stm->bindValue(':to',    $toDate);
        $stm->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function getRecentOrders(string $fromDate, string $toDate, int $limit = 10): array
    {
        // Lấy đơn mới nhất trong khoảng ngày, kèm 1 tên sản phẩm đại diện + số sản phẩm trong đơn
        // Amount dùng o.total_amount để ổn định (khỏi lệch do status item)
        $sql = "
      SELECT
        o.id,
        o.order_code,
        o.created_at,
        COALESCE(u.full_name, o.receiver_name) AS customer_name,
        o.status,
        o.total_amount AS amount,
        MIN(COALESCE(p.name, 'Sản phẩm')) AS first_product,
        COUNT(DISTINCT pv.product_id) AS product_count
      FROM orders o
      LEFT JOIN orderitems oi   ON oi.order_id = o.id
      LEFT JOIN productvariants pv ON pv.id = oi.variant_id
      LEFT JOIN products p      ON p.id = pv.product_id
      LEFT JOIN users u         ON u.id = o.user_id
      WHERE o.created_at >= :from
        AND o.created_at <  DATE_ADD(:to, INTERVAL 1 DAY)
      GROUP BY o.id, o.order_code, o.created_at, customer_name, o.status, o.total_amount
      ORDER BY o.created_at DESC
      LIMIT :limit
    ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->bindValue(':from',  $fromDate);
        $stm->bindValue(':to',    $toDate);
        $stm->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    private function dateRange(string $from, string $to): array
    {
        $out = [];
        $d = new DateTime($from);
        $end = new DateTime($to);
        while ($d <= $end) {
            $out[] = $d->format('Y-m-d');
            $d->modify('+1 day');
        }
        return $out;
    }

    public function getRevenueSeries(string $from, string $to): array
    {
        $sql = "
    SELECT DATE(o.created_at) d, SUM(oi.quantity*oi.price) revenue
    FROM orders o
    JOIN orderitems oi ON oi.order_id=o.id
    WHERE LOWER(TRIM(oi.status)) IN ('completed','delivered')   -- thêm 'done','success' nếu DB dùng
      AND o.created_at >= :from AND o.created_at < DATE_ADD(:to, INTERVAL 1 DAY)
    GROUP BY DATE(o.created_at) ORDER BY DATE(o.created_at)
  ";
        $stm = $this->db->pdo->prepare($sql);
        $stm->execute([':from' => $from, ':to' => $to]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        // lấp ngày trống = 0
        $labels = [];
        $d = new DateTime($from);
        $end = new DateTime($to);
        $map = [];
        foreach ($rows as $r) $map[$r['d']] = (float)$r['revenue'];
        while ($d <= $end) {
            $labels[] = $d->format('Y-m-d');
            $d->modify('+1 day');
        }
        $data = array_map(fn($x) => $map[$x] ?? 0, $labels);

        return ['labels' => $labels, 'datasets' => [['label' => 'Doanh thu', 'data' => $data]]];
    }
}
