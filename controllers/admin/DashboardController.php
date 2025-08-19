<?php
include_once "models/admin/DashboardModel.php";
class DashboardController
{
    /** @var DashboardModel */
    private $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    /**
     * Trang dashboard (render view + đẩy dữ liệu chart ban đầu)
     * URL ví dụ: ?admin=dashboard (có thể kèm from=YYYY-MM-DD&to=YYYY-MM-DD hoặc quick=this_month)
     */
    public function index()
    {
        [$from, $to] = $this->resolveDateRange($_GET ?? []);

        $to   = (new DateTime('today'))->format('Y-m-d');
        $from = (new DateTime('today'))->modify('-29 days')->format('Y-m-d');

        $kpis         = $this->model->getSimpleKpis($from, $to);
        $topCustomers = $this->model->getTopCustomers($from, $to, 6);

        $recentOrders = $this->model->getRecentOrders($from, $to, 10);

        $charts = [];
        $charts['revenueByDay'] = $this->model->getRevenueSeries($from, $to);

        $content = getContentPath('', 'dashboard');
        view('admin/master', [
            'content'      => $content,
            'recentOrders' => $recentOrders,
            'kpis'         => $kpis,
            'charts'  => $charts,
            'topCustomers' => $topCustomers,
            'from'         => $from,
            'to'           => $to,
        ]);
    }

    /**
     * API JSON cho AJAX khi người dùng đổi khoảng ngày / preset.
     * URL ví dụ: ?admin=dashboard&action=stats&quick=last7d
     */
    public function stats()
    {
        [$from, $to] = $this->resolveDateRange($_GET ?? []);

        // NHÁNH NHẸ cho widget doanh thu
        if (isset($_GET['only']) && $_GET['only'] === 'revenue') {
            $rev = $this->model->getRevenueByDay($from, $to);
            return $this->json([
                'ok'   => true,
                'from' => $from,
                'to'   => $to,
                'charts' => [
                    'revenueByDay' => [
                        'labels'   => $rev['labels'],
                        'datasets' => [['label' => 'Doanh thu', 'data' => $rev['data']]],
                    ]
                ]
            ]);
        }

        // --- Nhánh cũ của bạn (nếu cần các chart khác) ---
        $charts = $this->buildChartsData($from, $to);
        $this->json([
            'ok'     => true,
            'from'   => $from,
            'to'     => $to,
            'charts' => $charts,
        ]);
    }
    /* ===================== Helpers ===================== */

    private function buildChartsData(string $from, string $to): array
    {
        // Gọi các hàm trong DashboardModel đã làm
        $revByDay      = $this->model->getRevenueByDay($from, $to);
        $orderByDay    = $this->model->getOrderCountByDay($from, $to, true);
        $orderByStatus = $this->model->getOrderCountByStatus($from, $to, false);
        $payShare      = $this->model->getPaymentMethodShare($from, $to);
        $topProducts   = $this->model->getTopProducts($from, $to, 10, 'revenue');
        $revByCat      = $this->model->getRevenueByCategory($from, $to);
        $aov           = $this->model->getAOV($from, $to);
        $topVariants   = $this->model->getTopVariantsByQty($from, $to, 10);

        // Tổng nhanh cho “cards” (nếu cần)
        $totalRevenue = array_sum($revByDay['data']);
        $totalOrders  = array_sum($orderByDay['data']);

        // Trả về shape “sẵn sàng” cho Chart.js/ApexCharts
        return [
            // Line chart: doanh thu theo ngày
            'revenueByDay' => [
                'labels'   => $revByDay['labels'],
                'datasets' => [[
                    'label' => 'Doanh thu',
                    'data'  => $revByDay['data'],
                ]],
                // ApexCharts: series = [[ 'name'=>'Doanh thu', 'data'=>data ]], categories = labels
            ],

            // Line chart: số đơn theo ngày
            'ordersByDay' => [
                'labels'   => $orderByDay['labels'],
                'datasets' => [[
                    'label' => 'Số đơn (đã thanh toán)',
                    'data'  => $orderByDay['data'],
                ]],
            ],

            // Doughnut/Pie: số đơn theo trạng thái
            'ordersByStatus' => [
                'labels'   => $orderByStatus['labels'],
                'datasets' => [[
                    'label' => 'Số đơn',
                    'data'  => $orderByStatus['data'],
                ]],
            ],

            // Pie: tỉ trọng phương thức thanh toán
            'paymentShare' => [
                'labels'   => $payShare['labels'],
                'datasets' => [[
                    'label' => 'Số đơn (đã thanh toán)',
                    'data'  => $payShare['data'],
                ]],
            ],

            // Bar: top sản phẩm theo doanh thu
            'topProducts' => [
                'labels'   => $topProducts['labels'],
                'datasets' => [[
                    'label' => 'Doanh thu',
                    'data'  => $topProducts['data'],
                ]],
            ],

            // Bar: doanh thu theo danh mục
            'revenueByCategory' => [
                'labels'   => $revByCat['labels'],
                'datasets' => [[
                    'label' => 'Doanh thu',
                    'data'  => $revByCat['data'],
                ]],
            ],

            // Bar: top biến thể theo số lượng
            'topVariantsByQty' => [
                'labels'   => $topVariants['labels'],
                'datasets' => [[
                    'label' => 'Số lượng',
                    'data'  => $topVariants['data'],
                ]],
            ],

            // Cards tổng quan
            'summary' => [
                'totalRevenue' => $totalRevenue,
                'totalOrders'  => $totalOrders,
                'aov'          => $aov,
                'from'         => $from,
                'to'           => $to,
            ],
        ];
    }

    /**
     * Nhận from/to theo ưu tiên: ?from=YYYY-MM-DD&to=YYYY-MM-DD
     * hoặc preset ?quick=last7d/last30d/this_month/last_month/today/yesterday/this_year
     * Mặc định: last30d (bao gồm hôm nay).
     */
    private function resolveDateRange(array $q): array
    {
        // ưu tiên from/to trực tiếp
        if (
            !empty($q['from']) && !empty($q['to']) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $q['from']) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $q['to'])
        ) {
            return [$q['from'], $q['to']];
        }
        // quick (ví dụ this_month)
        $today = new DateTime('today');
        $quick = $q['quick'] ?? 'this_month';
        switch ($quick) {
            case 'today':
                $from = $today->format('Y-m-d');
                $to   = $today->format('Y-m-d');
                break;
            case 'this_year':
                $from = (new DateTime('first day of january ' . $today->format('Y')))->format('Y-m-d');
                $to   = $today->format('Y-m-d');
                break;
            case 'last_30d':
            default:
                $to   = $today->format('Y-m-d');
                $from = (clone $today)->modify('-29 days')->format('Y-m-d');
                break;
        }
        return [$from, $to];
    }

    private function isDate(string $val): bool
    {
        return (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $val);
    }

    private function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
