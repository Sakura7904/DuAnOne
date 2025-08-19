<?php
$k = $kpis ?? ['revenue' => 0, 'items' => 0, 'aov' => 0, 'pending_orders' => 0];
$vn = fn($n) => number_format((float)$n, 0, ',', '.');

$rows = $topCustomers ?? [];
$vnMoney = fn($n) => number_format((float)$n, 0, ',', '.') . 'đ';

$rowss = $recentOrders ?? [];
$vnMoney = fn($n) => number_format((float)$n, 0, ',', '.');
$vnDate  = fn($dt) => date('d/m/Y', strtotime($dt));
$vnTime = fn($dt) => date('H:i:s',    strtotime($dt));
$vnStatusText = function ($s) {
    $s = strtolower(trim((string)$s));
    return [
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped'    => 'Đã gửi',
        'delivered'  => 'Đã giao',
        'completed'  => 'Hoàn thành',
        'cancelled'  => 'Đã hủy',
        'refunded'   => 'Hoàn tiền',
    ][$s] ?? ucfirst($s);
};

$vnStatusDotClass = function ($s) {
    $s = strtolower(trim((string)$s));
    return [
        'pending'    => 'bg-yellow',
        'processing' => 'bg-blue',
        'shipped'    => 'bg-blue',
        'delivered'  => 'bg-green',
        'completed'  => 'bg-green',
        'cancelled'  => 'bg-red',
        'refunded'   => 'bg-pink',
    ][$s] ?? 'bg-gray';
};
?>

<style>
    /* Scope nội bộ để không va chạm template cũ */
    #rev-standalone {
        max-width: 100%;
        box-sizing: border-box;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial
    }

    #rev-standalone .card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04)
    }

    #rev-standalone .head {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-bottom: 1px solid #eef2f7
    }

    #rev-standalone .title {
        font-weight: 700;
        color: #111827
    }

    #rev-standalone .controls {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center
    }

    #rev-standalone .btn {
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        color: #374151;
        cursor: pointer
    }

    #rev-standalone .btn.active {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8
    }

    #rev-standalone .inp {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 8px;
        font-size: 12px
    }

    #rev-standalone .body {
        padding: 12px 14px
    }

    #rev-standalone .foot {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        padding: 0 14px 14px
    }

    #rev-standalone .kpi {
        font-size: 12px;
        color: #6b7280
    }

    #rev-standalone .kpi b {
        font-size: 14px;
        color: #111827
    }

    @media (prefers-color-scheme: dark) {
        #rev-standalone .card {
            background: #0b0f14;
            border-color: #1f2937
        }

        #rev-standalone .head {
            border-color: #1f2937
        }

        #rev-standalone .title {
            color: #e5e7eb
        }

        #rev-standalone .btn {
            background: #0f1720;
            border-color: #1f2937;
            color: #94a3b8
        }

        #rev-standalone .btn.active {
            background: #0b2a5d;
            border-color: #1d4ed8;
            color: #cfe0ff
        }

        #rev-standalone .inp {
            background: #0f1720;
            border-color: #1f2937;
            color: #e5e7eb
        }

        #rev-standalone .kpi {
            color: #94a3b8
        }

        #rev-standalone .kpi b {
            color: #e5e7eb
        }
    }
</style>

<div>
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Dashboard</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
        <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon"><a class="capitalize" href="index.html">home</a></div><img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon"><span class="capitalize text-color-brands">Dashboard</span>
    </div>
    <div class="grid grid-cols-1 gap-6 mb-[26px] lg:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg py-4 flex-1 px-[19px]">
            <div class="flex items-center justify-between mb-4">
                <p class="text-desc text-gray-500 dark:text-gray-dark-500">Tổng doanh thu</p>
            </div>
            <div class="flex items-center justify-between mb-[2px]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg grid place-items-center bg-green"><img src="./assets/admin/assets/images/icons/icon-bag-happy.svg" alt=""></div>
                    <p class="text-btn-label font-bold text-gray-1100 dark:text-gray-dark-1100"><?= $vn($k['revenue']) ?>đ</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg py-4 flex-1 px-[19px]">
            <div class="flex items-center justify-between mb-4">
                <p class="text-desc text-gray-500 dark:text-gray-dark-500">Số đơn đã hoàn thành</p>
            </div>
            <div class="flex items-center justify-between mb-[2px]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg grid place-items-center bg-blue"><img src="./assets/admin/assets/images/icons/icon-bag-happy.svg" alt=""></div>
                    <p class="text-btn-label font-bold text-gray-1100 dark:text-gray-dark-1100">
                        <?= $vn($k['orders']) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg py-4 flex-1 px-[19px]">
            <div class="flex items-center justify-between mb-4">
                <p class="text-desc text-gray-500 dark:text-gray-dark-500">Giá trị đơn trung bình</p>
            </div>
            <div class="flex items-center justify-between mb-[2px]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg grid place-items-center bg-violet"><img src="./assets/admin/assets/images/icons/icon-bag-happy.svg" alt=""></div>
                    <p class="text-btn-label font-bold text-gray-1100 dark:text-gray-dark-1100"><?= $vn($k['aov']) ?>đ</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg py-4 flex-1 px-[19px]">
            <div class="flex items-center justify-between mb-4">
                <p class="text-desc text-gray-500 dark:text-gray-dark-500">Số đơn đang chờ xử lý</p>
            </div>
            <div class="flex items-center justify-between mb-[2px]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg grid place-items-center bg-pink"><img src="./assets/admin/assets/images/icons/icon-bag-happy.svg" alt=""></div>
                    <p class="text-btn-label font-bold text-gray-1100 dark:text-gray-dark-1100"><?= $vn($k['pending_orders']) ?> đơn hàng</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 items-center mb-6 gap-[18px] xl:grid-cols-[1fr,364px]">
        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg flex-1 p-[25px]">
            <div class="flex items-center justify-between pb-3 border-neutral border-b mb-5 dark:border-dark-neutral-border">
                <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Thống kê doanh thu</p>
            </div>
            <div>
                <div>
                    <canvas class="max-h-[240px] lg:max-h-[123px] xl:max-h-[200px]" id="bieuDo"></canvas>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg flex-1 self-stretch">
            <div class="flex items-center justify-between px-6 py-[18px]">
                <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Khách hàng tiềm năng</p>
            </div>
            <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
            <div class="pt-5 flex flex-col gap-5 px-[26px] pb-[22px]">
                <?php if (empty($rows)): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start gap-3">
                            <div>
                                <p class="text-normal text-gray-1100 dark:text-gray-dark-1100 mb-4">Chưa có dữ liệu</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-normal font-semibold text-gray-1100 mb-4 dark:text-gray-dark-1100">0đ</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start gap-3">
                                <div>
                                    <p class="text-normal text-gray-1100 dark:text-gray-dark-1100 mb-4">
                                        <?= htmlspecialchars($r['name'] ?: 'Khách hàng') ?>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="text-normal font-semibold text-gray-1100 mb-4 dark:text-gray-dark-1100">
                                    <?= $vnMoney($r['revenue'] ?? 0) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-scroll scrollbar-hide p-[25px] mb-[33px]">
        <div class="flex items-center justify-between pb-4 border-neutral border-b mb-5 dark:border-dark-neutral-border">
            <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Đơn hàng gần đây</p>
        </div>
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Products</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Order ID</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Date</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Customer name</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Status</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rowss)): ?>
                    <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                        <td class="py-[17px]" colspan="6"><span>Chưa có đơn hàng trong khoảng thời gian</span></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rowss as $r): ?>
                        <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                            <td class="py-[17px]">
                                <?php
                                $label = $r['first_product'] ?? 'Sản phẩm';
                                $extra = (int)$r['product_count'] > 1 ? ' +' . ((int)$r['product_count'] - 1) . ' SP' : '';
                                ?>
                                <span><?= htmlspecialchars($label . $extra) ?></span>
                            </td>
                            <td><span><?= htmlspecialchars($r['order_code'] ?? ('#' . $r['id'])) ?></span></td>
                            <td>
                                <span><?= $vnDate($r['created_at']) ?></span> <br>
                                <small><?= $vnTime($r['created_at']) ?></small>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">
                                        <?= htmlspecialchars($r['customer_name'] ?: 'Khách') ?>
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-x-2">
                                    <div class="w-2 h-2 rounded-full <?= $vnStatusDotClass($r['status']) ?>"></div>
                                    <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">
                                        <?= htmlspecialchars($vnStatusText($r['status'])) ?>
                                    </p>
                                </div>
                            </td>

                            <td><span><?= $vnMoney($r['amount']) ?>đ</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-scroll scrollbar-hide p-[25px] mb-[33px]">
        <div class="flex items-center justify-between pb-4 border-neutral border-b mb-5 dark:border-dark-neutral-border">
            <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Top sản phẩm bán chạy</p>
        </div>
        <table class="w-full min-w-[900px]">
            <tr>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Image</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Name</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Category</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">QTY</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Đã bán</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex text-center justify-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Tổng doanh thu</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <img class="img-thumbnail product-thumbnail border border-neutral rounded-lg dark:border-dark-neutral-border w-[50px]"
                            src="<?= $product['thumbnail_display'] ?>">
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div class="flex flex-col gap-y-1 max-w-[250px]">
                            <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100"><?= $product['name'] ?></p>
                            <?php if ($product['variant_count'] > 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">Có <?= $product['variant_count'] ?> biến thể</p>
                            <?php endif; ?>

                            <?php if ($product['variant_count'] == 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">Chưa có biến thể cho sản phẩm này</p>
                            <?php endif; ?>

                            <p class="text-xs text-gray-500 dark:text-gray-dark-500">
                                <?php if (!empty($product['description'])): ?>
                                    <small class="text-muted d-block mt-1">
                                        <?= htmlspecialchars(substr($product['description'], 0, 60)) ?>
                                        <?= strlen($product['description']) > 60 ? '...' : '' ?>
                                    </small>
                                <?php endif; ?>
                            </p>
                        </div>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500"><?= $product['category_name'] ?? 'Chưa phân loại' ?></p>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <?php
                        $stockClass = 'badge-danger';
                        $stockText = 'Hết hàng';

                        if ($product['total_quantity'] > 50) {
                            $stockClass = 'badge-success';
                            $stockText = 'Còn nhiều';
                        } elseif ($product['total_quantity'] > 10) {
                            $stockClass = 'badge-warning';
                            $stockText = 'Còn ít';
                        } elseif ($product['total_quantity'] > 0) {
                            $stockClass = 'badge-danger';
                            $stockText = 'Sắp hết';
                        }
                        ?>
                        <div>
                            <span class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100 badge <?= $stockClass ?> badge-lg">
                                <?= $product['total_quantity'] ?>
                            </span>
                            <br>
                            <small class="<?= str_replace('badge-', 'text-', $stockClass) ?>">
                                <?= $stockText ?>
                            </small>
                        </div>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">
                            <?= $product['sold_qty_display'] ?? number_format((int)($product['sold_qty'] ?? $product['total_quantity'] ?? 0), 0, ',', '.') ?>
                        </p>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm text-center leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">
                            <?= $product['sold_revenue_display'] ?? (number_format((float)($product['sold_revenue'] ?? 0), 0, ',', '.') . 'đ') ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>
    // LẤY DỮ LIỆU TỪ PHP
    const revenueSeries = <?=
                            json_encode($charts['revenueByDay'] ?? ['labels' => [], 'datasets' => [['data' => []]]], JSON_UNESCAPED_UNICODE);
                            ?>;

    (function() {
        const el = document.getElementById('bieuDo');
        if (!el) return;

        // Hủy chart cũ nếu có (tránh lỗi canvas-in-use)
        const old = window.Chart?.getChart(el);
        if (old) old.destroy();

        const labels = revenueSeries?.labels ?? [];
        const data = (revenueSeries?.datasets?.[0]?.data ?? []).map(Number);

        // VẼ BIỂU ĐỒ DOANH THU (LINE)
        window._bieuDo = new Chart(el, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Doanh thu theo ngày',
                    data,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) =>
                                'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(+ctx.parsed.y || 0) + 'đ'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 0
                        }
                    },
                    y: {
                        ticks: {
                            callback: v => new Intl.NumberFormat('vi-VN').format(v) + 'đ'
                        }
                    }
                }
            }
        });
    })();
</script>

<style>
    .product-thumbnail {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
    }

    .badge-outline-primary {
        color: #007bff;
        border: 1px solid #007bff;
        background-color: transparent;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5em 0.75em;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group-vertical .btn {
        margin-bottom: 2px;
    }

    .badge {
        font-size: 0.75em;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>