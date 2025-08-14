<div>
    <div class="flex items-end justify-between mb-[25px]">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Orders</h2>
            <div class="flex items-center text-xs text-gray-500 gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon"><a class="capitalize" href="?admin=dashboard">home</a></div><img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon"><span class="capitalize text-color-brands">Orders List</span>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-between flex-wrap gap-5 mb-[27px]">
        <div class="flex items-center gap-3">
            <div class="dropdown dropdown-end">
                <label class="cursor-pointer dropdown-label flex items-center justify-between" tabindex="0">
                    <div class="flex items-center justify-between p-4 bg-neutral-bg border border-neutral rounded-lg w-[173px] dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Status</p><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-arrow-down.svg" alt="arrow icon">
                    </div>
                </label>
                <ul class="dropdown-content" tabindex="0">
                    <div class="relative menu rounded-box dropdown-shadow w-[173px] bg-neutral-bg pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                        <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Sales report</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Export report</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Profit manage</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Revenue report</span>
                            </div>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg p-[25px] mb-[25px]">
        <div class="flex items-center justify-between pb-4 border-neutral border-b mb-5 dark:border-dark-neutral-border">
            <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Danh sách đơn hàng</p>
        </div>
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Order ID</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tên người dùng</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tên sản phẩm</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Phương thức thanh toán</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Ngày mua</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Trạng thái</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tổng tiền</th>
                    <th class="font-normal text-normal text-gray-400 text-center pb-[15px] dark:text-gray-dark-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Helpers nhỏ cho view
                function h($s)
                {
                    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
                }
                function statusText(string $st): string
                {
                    return match ($st) {
                        'pending'    => 'Chờ thanh toán',
                        'confirmed'  => 'Đã xác nhận',
                        'processing' => 'Đang xử lý',
                        'shipped'    => 'Đang giao hàng',
                        'delivered'  => 'Đã giao',
                        'completed'  => 'Hoàn thành',
                        'cancelled'  => 'Đã hủy',
                        'refunded'   => 'Đã hoàn tiền',
                        default      => ucfirst($st),
                    };
                }
                function statusDotClass(string $st): string
                {
                    return match ($st) {
                        'pending'    => 'bg-yellow',
                        'processing' => 'bg-blue',
                        'shipped'    => 'bg-yellow',
                        'delivered'  => 'bg-green',
                        'completed'  => 'bg-green',
                        'cancelled'  => 'bg-red',
                        'refunded'   => 'bg-gray-400',
                        default      => 'bg-gray-300',
                    };
                }
                
                // Tóm tắt sản phẩm ưu tiên dùng “first_*” nếu controller có; fallback số sản phẩm
                function itemSummary(array $o): string
                {
                    if (!empty($o['first_product_name'])) {
                        $parts = [$o['first_product_name']];
                        if (!empty($o['first_color'])) $parts[] = '(' . $o['first_color'] . (!empty($o['first_size']) ? ' - ' . $o['first_size'] : '') . ')';
                        if (!empty($o['first_qty']))   $parts[] = '× ' . (int)$o['first_qty'];
                        return implode(' ', $parts);
                    }
                    if (!empty($o['items_count'])) return (int)$o['items_count'] . ' sản phẩm';
                    return '—';
                }
                function vnd($n)
                {
                    return number_format((float)$n, 0, ',', '.') . '₫';
                }
                function buildItemSummary(array $o): string
                {
                    if (!empty($o['first_product_name'])) {
                        $name = $o['first_product_name'];
                        $meta = [];
                        if (!empty($o['first_color'])) $meta[] = $o['first_color'];
                        if (!empty($o['first_size']))  $meta[] = $o['first_size'];
                        $attrs = $meta ? ' - (' . implode(' - ', $meta) . ')' : '';
                        $qty   = !empty($o['first_qty']) ? ' × ' . (int)$o['first_qty'] : '';
                        // thành tiền của dòng đầu (đơn giá * SL)
                        $lineTotal = '';
                        // Nếu còn nhiều item khác, thêm +N
                        $more = '';
                        if (!empty($o['items_count']) && (int)$o['items_count'] > 1) {
                            $more = ' + ' . ((int)$o['items_count'] - 1) . ' SP khác';
                        }
                        return $name . $attrs . $qty . $lineTotal . $more;
                    }
                    return !empty($o['items_count']) ? ((int)$o['items_count']) . ' sản phẩm' : '—';
                }
                ?>

                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>

                        <?php
                        $orderId    = (int)($o['id'] ?? 0);
                        $orderCode  = $o['order_code'] ?? ('#' . $orderId);
                        $customer   = $o['user_name'] ?? ($o['receiver_name'] ?? '—');
                        $payMethod  = $o['payment_method'] ?? '—';
                        $createdAt  = !empty($o['created_at']) ? date('d M, Y', strtotime($o['created_at'])) : '—';
                        $status     = (string)($o['status'] ?? '');
                        $totalAmt   = isset($o['total_amount']) ? number_format((float)$o['total_amount'], 2) : '0.00';
                        $dotClass   = statusDotClass($status);
                        $statusTxt  = statusText($status);
                        $summary    = itemSummary($o);
                        $displayStatus = $o['_status_for_view'] ?? ($o['status'] ?? 'pending');
                        ?>
                        <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                            <!-- Mã đơn -->
                            <td><span><?= h('#' . $orderCode) ?></span></td>

                            <!-- Khách hàng -->
                            <td class="py-[25px]">
                                <div class="flex items-center gap-2">
                                    <p class="text-normal text-gray-1100 dark:text-gray-dark-1100"><?= h($customer) ?></p>
                                </div>
                            </td>

                            <!-- Tóm tắt sản phẩm -->
                            <td><span><?= h(buildItemSummary($o)) ?></span></td>

                            <!-- Phương thức thanh toán -->
                            <td><span><?= h($payMethod) ?></span></td>

                            <!-- Ngày đặt -->
                            <td><span><?= h($createdAt) ?></span></td>

                            <!-- Trạng thái đơn -->
                            <td>
                                <div class="flex items-center gap-x-2">
                                    <div class="w-2 h-2 rounded-full <?= htmlspecialchars(statusDotClass($displayStatus)) ?>"></div>
                                    <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">
                                        <?= htmlspecialchars(statusText($displayStatus)) ?>
                                    </p>
                                </div>
                            </td>

                            <!-- Tổng tiền -->
                            <td><span><?= h(vnd($o['total_amount'] ?? 0)) ?></span></td>

                            <!-- Actions -->
                            <td>
                                <div class="dropdown dropdown-end w-full">
                                    <label class="cursor-pointer dropdown-label flex items-center justify-between p-3" tabindex="0">
                                        <img class="mx-auto cursor-pointer" src="./assets/admin/assets/images/icons/icon-more.svg" alt="more icon">
                                    </label>
                                    <ul class="dropdown-content" tabindex="0">
                                        <div class="relative menu rounded-box dropdown-shadow min-w-[160px] bg-neutral-bg mt-[10px] pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                                            <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>

                                            <!-- Đổi trạng thái nhanh -->
                                            <li class="text-normal mb-[7px]">
                                                <form action="?admin=order_items_apply_status" method="post" class="inline">
                                                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="flex items-center bg-transparent p-0 gap-[7px]">
                                                        <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Chờ thanh toán</span>
                                                    </button>
                                                </form>
                                            </li>
                                            <li class="text-normal mb-[7px]">
                                                <form action="?admin=order_items_apply_status" method="post" class="inline">
                                                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="flex items-center bg-transparent p-0 gap-[7px]">
                                                        <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Đang xử lý</span>
                                                    </button>
                                                </form>
                                            </li>
                                            <li class="text-normal mb-[7px]">
                                                <form action="?admin=order_items_apply_status" method="post" class="inline">
                                                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                                    <input type="hidden" name="status" value="shipped">
                                                    <button type="submit" class="flex items-center bg-transparent p-0 gap-[7px]">
                                                        <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Đang giao hàng</span>
                                                    </button>
                                                </form>
                                            </li>
                                            <li class="text-normal mb-[7px]">
                                                <form action="?admin=order_items_apply_status" method="post" class="inline">
                                                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                                    <input type="hidden" name="status" value="delivered">
                                                    <button type="submit" class="flex items-center bg-transparent p-0 gap-[7px]">
                                                        <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Đã giao</span>
                                                    </button>
                                                </form>
                                            </li>

                                            <!-- Hủy đơn -->
                                            <?php if (!in_array($status, ['cancelled', 'completed', 'delivered'])): ?>
                                                <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                                                <li class="text-normal mb-[7px]">
                                                    <form action="?admin=order_items_apply_status" method="post" class="inline cancel-order-form">
                                                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="button" class="flex items-center bg-transparent p-0 gap-[7px] cancel-order-btn">
                                                            <span class="text-red text-[11px] leading-4">Hủy đơn</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        </div>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500">Không có đơn nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
    <?php
    // ===== Pagination (dùng pg thay vì page để không đụng router) =====
    $cur    = max(1, (int)($_GET['pg'] ?? ($page ?? 1)));   // trang hiện tại
    $total  = max(1, (int)($pages ?? 1));                   // tổng số trang
    $window = 2;                                            // hiển thị 2 trang trước/sau

    $start = max(1, $cur - $window);
    $end   = min($total, $cur + $window);
    if ($end - $start < 4) {
        $start = max(1, min($start, $end - 4));
        $end   = min($total, max($end, $start + 4));
    }

    // build URL giữ filter, đổi sang pg
    function admin_page_url_pg(int $p): string
    {
        $qs = $_GET;
        $qs['admin'] = 'list-order'; // đúng route của bạn
        unset($qs['page']);          // tránh đụng router cũ
        $qs['pg'] = $p;
        return '?' . http_build_query($qs);
    }

    // classes
    $btnBase = 'btn text-sm h-fit min-h-fit capitalize leading-4 border-0 font-semibold py-[11px] px-[18px]';
    $btnAct  = $btnBase . ' bg-color-brands hover:bg-color-brands text-white';
    $btnNorm = $btnBase . ' bg-transparent text-gray-1100 hover:text-white hover:bg-color-brands dark:text-gray-dark-1100';

    $ghostBtn = 'items-center justify-center border rounded-lg border-neutral hidden gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border sm:flex';
    $ghostDis = ' opacity-50 pointer-events-none';
    $ghostTxt = 'text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400';

    // prev/next/first/last
    $isFirst = ($cur <= 1);
    $isLast  = ($cur >= $total);
    $prevUrl = admin_page_url_pg(max(1, $cur - 1));
    $nextUrl = admin_page_url_pg(min($total, $cur + 1));
    ?>

    <div class="flex items-center gap-x-10">

        <!-- First & Prev -->
        <div class="hidden sm:flex items-center gap-x-2">
            <a class="<?= $ghostBtn . ($isFirst ? $ghostDis : '') ?>" href="<?= htmlspecialchars(admin_page_url_pg(1)) ?>">
                <span class="<?= $ghostTxt ?>">« Trang đầu</span>
            </a>
            <a class="<?= $ghostBtn . ($isFirst ? $ghostDis : '') ?>" href="<?= htmlspecialchars($prevUrl) ?>">
                <span class="<?= $ghostTxt ?>">‹ Trước</span>
            </a>
        </div>

        <!-- Dãy số trang -->
        <div>
            <?php if ($start > 1): ?>
                <a class="<?= $cur === 1 ? $btnAct : $btnNorm ?>" href="<?= htmlspecialchars(admin_page_url_pg(1)) ?>">1</a>
                <?php if ($start > 2): ?><span class="px-2">…</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a class="<?= $cur === $i ? $btnAct : $btnNorm ?>" href="<?= htmlspecialchars(admin_page_url_pg($i)) ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($end < $total): ?>
                <?php if ($end < $total - 1): ?><span class="px-2">…</span><?php endif; ?>
                <a class="<?= $cur === $total ? $btnAct : $btnNorm ?>" href="<?= htmlspecialchars(admin_page_url_pg($total)) ?>"><?= $total ?></a>
            <?php endif; ?>
        </div>

        <!-- Next & Last -->
        <div class="hidden sm:flex items-center gap-x-2">
            <a class="<?= $ghostBtn . ($isLast ? $ghostDis : '') ?>" href="<?= htmlspecialchars($nextUrl) ?>">
                <span class="<?= $ghostTxt ?>">Sau ›</span>
            </a>
            <a class="<?= $ghostBtn . ($isLast ? $ghostDis : '') ?>" href="<?= htmlspecialchars(admin_page_url_pg($total)) ?>">
                <span class="<?= $ghostTxt ?>">Trang cuối »</span>
            </a>
        </div>
    </div>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '<?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>',
            timer: 1800,
            showConfirmButton: false
        });
    </script>
<?php unset($_SESSION['flash_success']);
endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '<?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>'
        });
    </script>
<?php unset($_SESSION['flash_error']);
endif; ?>
<script>
    document.addEventListener('click', function(e) {
        // Bắt đúng nút có class này (kể cả click vào span bên trong)
        const btn = e.target.closest('.cancel-order-btn');
        if (!btn) return;

        // Ngăn dropdown/label chặn sự kiện
        e.preventDefault();
        e.stopPropagation();

        const form = btn.closest('form');
        if (!form) return;

        Swal.fire({
            title: 'Bạn có chắc muốn hủy đơn này không?',
            text: 'Hành động này không thể hoàn tác.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hủy đơn',
            cancelButtonText: 'Không',
            reverseButtons: true,
            // màu nút
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            // nếu theme override, CSS dưới sẽ cưỡng ép
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<style>
    /* Ép màu nếu theme đang override (bạn nói hover mới có màu) */
    .swal2-confirm {
        background-color: #d33 !important;
        color: #fff !important;
    }

    .swal2-cancel {
        background-color: #6c757d !important;
        color: #fff !important;
    }
</style>