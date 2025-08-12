<script defer type="text/javascript" src="https://web.nvnstatic.net/tp/T0356/js/ratingOrder.js?v=2"></script>
<link rel="stylesheet" href="https://web.nvnstatic.net/tp/T0356/css/pview.css?v=4" type="text/css">
<?php
// View: views/user/myPurchase.php
// Nhận từ controller: $orders, $itemsByOrder, $counts, $activeTab
// $counts là đếm THEO ITEM: ['pending','confirmed','shipped','delivered','cancelled','refunded','all']

// Helpers nhỏ
function h($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function asset_url($p)
{
    if (!$p) return '';
    return (stripos($p, 'http') === 0) ? $p : ('/' . ltrim($p, '/'));
}
function vnItemStatus(string $s): string
{
    return match ($s) {
        'pending'   => 'Chờ thanh toán',
        'confirmed' => 'Đang xử lý',
        'shipped'   => 'Chờ giao hàng',
        'delivered' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
        'refunded'  => 'Đã hoàn tiền',
        default     => ucfirst($s),
    };
}
?>
<div role="main" id="MainContent" class="main-content">
    <div class="page-width clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-main-acount">
            <div id="parent" class="row">
                <div id="a" class="col-xs-12 col-sm-12 col-lg-9 col-left-account">
                    <div class="page-title m992">
                        <h3 class="title-head margin-top-0"><a href="#">Đơn hàng</a></h3>
                    </div>
                    <nav class="order-nav" aria-label="Trạng thái đơn hàng">
                        <a class="tab <?= ($activeTab === 'all' ? 'active' : '') ?>"
                            href="?user=purchase&status=all">Tất cả</a>

                        <a class="tab <?= ($activeTab === 'pending' ? 'active' : '') ?>"
                            href="?user=purchase&status=pending">Chờ thanh toán</a>

                        <a class="tab <?= ($activeTab === 'processing' ? 'active' : '') ?>"
                            href="?user=purchase&status=processing">Đang xử lý</a>

                        <a class="tab <?= ($activeTab === 'shipping' ? 'active' : '') ?>"
                            href="?user=purchase&status=shipping">Chờ giao hàng</a>

                        <a class="tab <?= ($activeTab === 'done' ? 'active' : '') ?>"
                            href="?user=purchase&status=done">Hoàn thành</a>

                        <a class="tab <?= ($activeTab === 'cancelled' ? 'active' : '') ?>"
                            href="?user=purchase&status=cancelled">Đã hủy</a>

                        <a class="tab <?= ($activeTab === 'refund' ? 'active' : '') ?>"
                            href="?user=purchase&status=refund">Trả hàng/Hoàn tiền</a>
                    </nav>
                    <div class="col-xs-12 col-sm-12 col-lg-12 noPadding mt-3 mb-3">
                        <div class="page-content orderIndex">
                            <div class="cart_header_labels hidden-xs clearfix">
                                <div class="row">
                                    <div class="label_item col-xs-12 col-sm-2 col-md-2">
                                        <div class="cart_product first_item">
                                            Mã đơn hàng </div>
                                    </div>
                                    <div class="label_item col-xs-12 col-sm-2 col-md-2">
                                        <div class="cart_description item">
                                            Ngày mua </div>
                                    </div>
                                    <div class="label_item col-xs-12 col-sm-1 col-md-1">
                                        <div class="cart_quantity item">
                                            Hình ảnh </div>
                                    </div>
                                    <div class="label_item col-xs-12 col-sm-4 col-md-4 product">
                                        <div class="cart_quantity item">
                                            Sản phẩm </div>
                                    </div>
                                    <div class="label_item col-xs-12 col-sm-2 col-md-2 totalPrice">
                                        <div class="cart_total item">
                                            Tổng tiền </div>
                                    </div>
                                    <div class="label_item col-xs-12 col-sm-1 col-md-1">
                                        <div class="cart_delete last_item">
                                            Trạng thái </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ajax_content_cart">
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $o): ?>
                                        <?php $items = $itemsByOrder[$o['id']] ?? []; ?>
                                        <?php if (empty($items)): ?>
                                            <div class="row">
                                                <div class="list_product_cart clearfix">
                                                    <div class="col-xs-12" style="padding:10px 15px;color:#7d7d7d">
                                                        Đơn #<?= h($o['order_code']) ?> không có sản phẩm.
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($items as $it): ?>
                                                <?php
                                                $img   = asset_url($it['image_url'] ?? '');
                                                $pname = $it['product_name'] ?? '';
                                                $pid   = (int)($it['product_id'] ?? 0);
                                                $color = $it['color_name'] ?? '';
                                                $size  = $it['size_name'] ?? '';
                                                $qty   = (int)($it['quantity'] ?? 0);
                                                $price = (float)($it['price'] ?? 0);
                                                $lineTotal   = $qty * $price;
                                                $itemStatus  = (string)($it['item_status'] ?? ($it['status'] ?? ''));
                                                $orderItemId = (int)($it['order_item_id'] ?? 0);
                                                ?>
                                                <div class="row">
                                                    <div class="list_product_cart clearfix">
                                                        <!-- Mã đơn -->
                                                        <div class="cpro_item image col-xs-6 col-sm-6 col-md-2">
                                                            <div class="cpro_item_inner">
                                                                <span>
                                                                    <b class="hidden-lg hidden-md" style="color:#7d7d7dee">Đơn hàng :</b>
                                                                    <b style="color:#000"><?= h($o['order_code']) ?></b>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Ngày mua -->
                                                        <div class="cpro_item text-left title col-xs-6 col-sm-6 col-md-2">
                                                            <div class="cpro_item_inner">
                                                                <span>
                                                                    <b class="hidden-lg hidden-md" style="color:#7d7d7dee">Ngày mua :</b>
                                                                    <b>
                                                                        <?= !empty($o['created_at']) ? date('d/m/Y H:i:s', strtotime($o['created_at'])) : '' ?>
                                                                    </b>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Hình ảnh -->
                                                        <div class="cpro_item qty productOrder text-center hidden-xs hidden-sm col-md-1">
                                                            <div class="cpro_item_inner" style="text-align:left;">
                                                                <?php if ($img): ?>
                                                                    <img src="<?=$img ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px">
                                                                <?php else: ?>
                                                                    <div style="width:50px;height:50px;background:#f2f2f2;border-radius:6px;"></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <!-- Sản phẩm -->
                                                        <div class="cpro_item qty productOrder text-center col-xs-12 col-sm-12 col-md-4 product">
                                                            <div class="cpro_item_inner" style="text-align:left">
                                                                <span class="clearfix" style="display:block;margin-bottom:5px;line-height:34px;min-height:34px;">
                                                                    <b class="tp_product_name">-
                                                                        <a href="?user=detailProduct&id=<?= $pid ?>"><?= h($pname) ?></a>
                                                                        <?php
                                                                        $attrs = [];
                                                                        if ($color) $attrs[] = h($color);
                                                                        if ($size)  $attrs[] = h($size);
                                                                        echo $attrs ? ' - (' . implode(' - ', $attrs) . ')' : '';
                                                                        ?>
                                                                        &nbsp;× <?= $qty ?>
                                                                    </b>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Thành tiền dòng + Tổng đơn -->
                                                        <div class="cpro_item line-price col-xs-6 col-sm-6 col-md-2">
                                                            <div class="cpro_item_inner">
                                                                <span class="price product-price money_line">
                                                                    <span class="money">
                                                                        <b class="hidden-lg hidden-md" style="color:#7d7d7dee">Tổng tiền :</b>
                                                                        <b>
                                                                            <?= number_format($lineTotal) ?>₫
                                                                            <?php if (isset($o['total_amount'])): ?>
                                                                                <div style="font-size:12px;color:#7d7d7d">Tổng đơn:
                                                                                    <strong><?= number_format((float)$o['total_amount']) ?>₫</strong>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </b>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Trạng thái ITEM + Hủy SP -->
                                                        <div class="cpro_item remove col-xs-6 col-sm-6 col-md-1">
                                                            <div class="cpro_item_inner">
                                                                <span>
                                                                    <b class="hidden-lg hidden-md" style="color:#7d7d7dee">Trạng thái :</b>
                                                                    <b><?= vnItemStatus($itemStatus) ?></b>
                                                                </span>

                                                                <?php if (in_array($itemStatus, ['pending', 'confirmed'], true) && $orderItemId > 0): ?>
                                                                    <form action="?user=cancelOrderItem<?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?>"
                                                                        method="post" class="cancel-item-form" style="margin-top:6px;">
                                                                        <input type="hidden" name="order_item_id" value="<?= $orderItemId ?>">
                                                                        <button type="button" class="btn tab btn-sm btn-danger btn-cancel-item">Hủy đơn</button>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-xs-12" style="padding:20px;text-align:center;">Không có đơn hàng nào</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
                <div id="b" class="col-xs-12 col-sm-12 col-lg-3 col-right-account margin-top-20">
                    <div class="block-account">
                        <div class="block-title-account">
                            <h5>Tài khoản của tôi</h5>
                        </div>
                        <div class="block-content form-signup">
                            <p>Tên tài khoản: <strong style="line-height: 20px;"> <?= $customer['full_name'] ?> !</strong></p>
                            <p><i class="fa fa-mobile font-some" aria-hidden="true"></i>
                                <span>Điện thoại: <?= $customer['phone_number'] ?></span>
                            </p>
                            <p><i class="fa fa-envelope font-some" aria-hidden="true"></i>
                                <span> Email: <?= $customer['email'] ?></span>
                            </p>
                            <p style="margin-top:20px;"><a href="?user=profile" class="btn btn-full btn-primary">Sửa</a></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --bg: #ffffff;
        --text: #2d2d2d;
        --muted: #6b6b6b;
        --accent: #d62828;
        --accent-light: #fef2f2;
        --ring: rgba(214, 40, 40, 0.35);
        --border: #e5e5e5;
    }

    .order-nav {
        display: flex;
        justify-content: center;
        /* Căn giữa toàn bộ thanh nav */
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 16px;
    }


    .order-nav .tab {
        appearance: none;
        border: 0;
        background: transparent;
        color: var(--muted);
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        font: 500 15px/1.2 ui-sans-serif, system-ui, Segoe UI, Roboto, Helvetica, Arial;
        cursor: pointer;
        transition: color 0.2s ease, background 0.2s ease, transform 0.05s ease;

        /* căn giữa chữ */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Hover */
    .order-nav .tab:hover {
        background: var(--accent-light);
        color: var(--accent);
    }

    /* Active */
    .order-nav .tab.active {
        color: #fff;
        background: var(--accent);
        box-shadow: 0 6px 20px -8px var(--accent);
    }

    /* Focus */
    .order-nav .tab:focus-visible {
        box-shadow: 0 0 0 3px var(--ring);
    }

    /* Nhấn xuống */
    .order-nav .tab:active {
        transform: translateY(1px);
    }

    @media (max-width: 480px) {
        .order-nav {
            flex-wrap: wrap;
        }

        .order-nav .tab {
            flex: 1 1 auto;
            text-align: center;
        }
    }
</style>

<script>
    document.querySelectorAll('.order-nav .tab').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.order-nav .tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-cancel-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = btn.closest('form');
                Swal.fire({
                    title: 'Hủy sản phẩm này?',
                    text: 'Sản phẩm sẽ được đánh dấu đã hủy trong đơn.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Có, hủy ngay',
                    cancelButtonText: 'Không',
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });

        // Flash
        <?php if (!empty($_SESSION['flash_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '<?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>',
                timer: 2500,
                showConfirmButton: false
            });
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Thất bại',
                text: '<?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>'
            });
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
    });
</script>