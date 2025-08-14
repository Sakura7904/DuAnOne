<?php
// ========== Helpers ==========
function vnd($n)
{
    if ($n === null || $n === '') return 'Liên hệ';
    return number_format((float)$n, 0, ',', '.') . 'đ';
}
function product_link(array $row): string
{
    if (!empty($row['product_url'])) return $row['product_url'];
    return 'index.php?user=detailProduct&id=' . (int)($row['product_id'] ?? 0);
}

$hasItems  = ($total > 0);
$startItem = $hasItems ? (($currentPage - 1) * $perPage + 1) : 0;
$endItem   = $hasItems ? min($startItem + count($wishlist) - 1, $total) : 0;

// Tổng số item (fallback nếu controller chưa truyền $total)
$total = isset($total) ? (int)$total : (is_array($wishlist ?? null) ? count($wishlist) : 0);
?>
<section class="main-wrapper">
    <section class="signup page_customer_account">
        <div class="container noPadding">
            <div class="headCategory hidden-xs hidden-sm"
                style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif?v=1719919543);">
                <ul class="breadcrumbCate">
                    <li><a href="index.php?user=home">Trang chủ</a></li>
                    <li><a href="index.php?user=wishlist">Danh sách yêu thích</a></li>
                </ul>
            </div>

            <div class="col-main-acount">
                <div class="col-left-account">
                    <div class="page-title">
                        <h1 class="title-head">Sản phẩm yêu thích</h1>
                        <p>Có <span style="font-weight: 600"><?= $total ?></span> sản phẩm trong mục ưa thích của bạn</p>
                    </div>

                    <div class="my-account">
                        <div class="dashboard">

                            <?php if (empty($wishlist)): ?>
                                <p>Danh sách yêu thích của bạn đang trống. <a href="index.php?user=home" style="color: aqua;">Tiếp tục mua sắm</a></p>
                            <?php else: ?>

                                <!-- Desktop -->
                                <div class="recent-orders hidden-sm hidden-xs">
                                    <div class="table-responsive tab-all" style="overflow-x:auto;">
                                        <table class="table">
                                            <thead>
                                                <tr class="tt">
                                                    <td class="image">Hình ảnh</td>
                                                    <td>Thông tin</td>
                                                    <td>Giá tiền</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody id="wishlist-row40">
                                                <?php foreach ($wishlist as $row): ?>
                                                    <?php
                                                    $vid   = (int)($row['variant_id'] ?? 0);
                                                    $href  = product_link($row);
                                                    $name  = htmlspecialchars($row['product_name'] ?? 'Sản phẩm');
                                                    $img   = htmlspecialchars($row['image_url'] ?? './assets/no-image.png');

                                                    $price       = $row['price']          ?? null;
                                                    $sale_price  = $row['sale_price']     ?? null;
                                                    $display     = $row['display_price']  ?? null;

                                                    $display_str = $row['display_price_str'] ?? vnd($display);
                                                    $price_str   = $row['price_str']         ?? vnd($price);
                                                    $sale_str    = $row['sale_price_str']    ?? vnd($sale_price);
                                                    $isSale      = !is_null($sale_price) && (float)$sale_price < (float)$price;

                                                    $in_stock = !empty($row['in_stock']);
                                                    ?>
                                                    <tr class="idProduct" data-id="<?= $vid ?>">
                                                        <td class="imageWislist">
                                                            <a href="<?= $href ?>">
                                                                <img src="<?= $img ?>" alt="<?= $name ?>" />
                                                            </a>
                                                        </td>
                                                        <td class="nameWislist">
                                                            <a href="<?= $href ?>"><?= $name ?></a>
                                                            <div class="statusWislist" style="font-size:12px;opacity:.8;margin-top:4px;">
                                                                <?= $in_stock ? 'Còn hàng' : 'Hết hàng' ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="priceWislist">
                                                                <?php if ($isSale): ?>
                                                                    <span class="priceNew onlyPrice tp_product_price"><?= $sale_str ?></span>
                                                                    <span class="oldPrice" style="text-decoration:line-through;margin-left:6px;"><?= $price_str ?></span>
                                                                <?php else: ?>
                                                                    <span class="priceNew onlyPrice tp_product_price"><?= $display_str ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="actitonWislist">
                                                            <a class="removeFav remove-link"
                                                                href="index.php?user=removeFromWishlist&variant_id=<?= $vid ?>"
                                                                title="Xóa khỏi yêu thích">
                                                                <i class="fal fa-times"></i>
                                                                <span class="txt">Xóa</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <ul class="pagination col-lg-12 col-md-12 hidden-sm hidden-xs">
                                <div class="paginator">
                                    <span class="labelPages"><?= $startItem ?> - <?= $endItem ?> / <?= (int)$total ?></span>
                                    <span class="titlePages">&nbsp;&nbsp;Trang: </span>

                                    <?php if ($hasItems && $currentPage > 1): ?>
                                        <a
                                            rel="nofollow, noindex"
                                            class="paging-previous ico"
                                            title="Trang trước"
                                            href="index.php?user=wishlist&sort=<?= htmlspecialchars($sort) ?>&pg=<?= $currentPage - 1 ?>">
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($hasItems): ?>
                                        <?php for ($i = 1; $i <= (int)$totalPages; $i++): ?>
                                            <?php if ($i == (int)$currentPage): ?>
                                                <span class="currentPage"><?= $i ?></span>
                                            <?php else: ?>
                                                <a
                                                    rel="nofollow, noindex"
                                                    href="index.php?user=wishlist&sort=<?= htmlspecialchars($sort) ?>&pg=<?= $i ?>">
                                                    <?= $i ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    <?php endif; ?>

                                    <?php if ($hasItems && $currentPage < (int)$totalPages): ?>
                                        <a
                                            rel="nofollow, noindex"
                                            class="paging-next ico"
                                            title="Trang sau"
                                            href="index.php?user=wishlist&sort=<?= htmlspecialchars($sort) ?>&pg=<?= $currentPage + 1 ?>">
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Styles: thu nhỏ ảnh + style nút xóa -->
<style>
    /* ===== Thu nhỏ ảnh (desktop) ===== */
    .page_customer_account .recent-orders .imageWislist {
        width: 120px;
    }

    .page_customer_account .recent-orders .imageWislist img {
        width: 110px;
        height: 146px;
        /* ~ tỉ lệ 3:4 */
        object-fit: cover;
        border-radius: 6px;
    }

    /* Cột ảnh cố định chiều rộng vừa mắt */
    .page_customer_account .recent-orders table.table thead .image {
        width: 140px;
    }

    /* Gọn khoảng cách hàng */
    .page_customer_account .recent-orders table.table>tbody>tr>td {
        padding-top: 10px;
        padding-bottom: 10px;
    }


    /* ===== Nút xóa: icon + chữ ===== */
    .remove-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #999;
        text-decoration: none;
    }

    .remove-link .txt {
        font-size: 13px;
        line-height: 1;
    }

    .remove-link i {
        font-size: 14px;
    }

    .remove-link:hover {
        color: #e74c3c;
    }
</style>