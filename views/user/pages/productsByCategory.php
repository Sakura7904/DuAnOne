<?php
$hasProducts = ($totalProducts > 0);
$startItem   = $hasProducts ? (($currentPage - 1) * $perPage + 1) : 0;
$endItem     = $hasProducts ? min($startItem + count($products) - 1, $totalProducts) : 0;

// giữ lại keyword (nếu có) khi click phân trang
$qKeyword = ($keyword ?? '') !== '' ? '&keyword=' . urlencode($keyword) : '';

// ===== Map sản phẩm đã có trong wishlist (để hiển thị icon tim đúng trạng thái) =====
$wishlistProductMap = [];
if (!empty($_SESSION['user_id'])) {
    include_once 'models/user/WishlistModel.php';
    $wl = new WishlistModel();
    $wishlistProductMap = $wl->getProductIdsByUser((int)$_SESSION['user_id']);
}
?>
<div class="container">
    <div class="headCategory hidden-xs hidden-sm"
        style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif);">
    </div>
    <div class="wrapBoxSearch">
        <form class="bigSearchBar" action="index.php" method="get">
            <input type="hidden" name="user" value="productsByCategory">
            <input type="hidden" name="category_id" value="<?= (int)($_GET['category_id'] ?? 0) ?>">
            <input type="text" class="search-box" name="keyword"
                placeholder="Bạn muốn tìm sản phẩm gì ?"
                value="<?= htmlspecialchars($keyword ?? ($_GET['keyword'] ?? ''), ENT_QUOTES) ?>">
            <button type="submit" class="search__btn">Tìm kiếm ngay</button>
        </form>
    </div>

    <ul class="breadcrumbCate">
        <li><a href="index.php?user=home">Trang chủ</a></li>
        <li>
            <a class="<?= $currentCategory['id'] ?? '' ?>"
                href="index.php?user=productsByCategory&category_id=<?= $currentCategory['id'] ?? 0 ?>">
                <?= htmlspecialchars($currentCategory['name'] ?? 'Danh mục') ?>
            </a>
        </li>
    </ul>

    <h3 class="titleCategory">
        <div class="mobile visible-sm visible-xs">
            <span>Danh mục</span>
            <span><?php echo count($products); ?> sản phẩm</span>
        </div>
    </h3>

    <div class="contentCategoyPage clearfix">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="contentCategoyPage clearfix">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="midCategory clearfix hidden-xs hidden-sm">
                        <div class="rightButtonCat col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="field-wrapper">
                                <label for="grid-sort-header">Xem theo</label>
                                <select class="sort-by custom-dropdown__select" id="grid-sort-header" onchange="window.location.href=this.value;">
                                    <option
                                        value="index.php?user=productsByCategory&category_id=<?= $categoryId ?>&sort=newest"
                                        <?= $sort === 'newest' ? 'selected' : '' ?>>
                                        Mới nhất
                                    </option>

                                    <option
                                        value="index.php?user=productsByCategory&category_id=<?= $categoryId ?>&sort=low_to_high"
                                        <?= $sort === 'low_to_high' ? 'selected' : '' ?>>
                                        Từ thấp đến cao
                                    </option>

                                    <option
                                        value="index.php?user=productsByCategory&category_id=<?= $categoryId ?>&sort=high_to_low"
                                        <?= $sort === 'high_to_low' ? 'selected' : '' ?>>
                                        Từ cao đến thấp
                                    </option>
                                </select>
                            </div>
                            <div class="paginationControl">
                                Có:
                                <div class="paginator">
                                    <span class="labelPages"><?php echo count($products); ?> / <?php echo count($products); ?></span>
                                </div> sản phẩm
                            </div>
                        </div>
                    </div>

                    <div class="listProductCategory clearfix">
                        <input type="hidden" class="auto-paginator">
                        <?php foreach ($products as $product): ?>
                            <div class="productItem mb-4 col-lg-3 col-md-3 col-xs-6 col-sm-6"
                                data-id="<?= (int)$product['id']; ?>">

                                <div class="productImage">
                                    <a href="index.php?user=detailProduct&id=<?= (int)$product['id']; ?>">
                                        <img loading="lazy"
                                            src="<?= htmlspecialchars($product['image_thumbnail'] ?? $product['image_url'] ?? '') ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </a>
                                </div>

                                <div class="productInfo">
                                    <a href="index.php?user=detailProduct&id=<?= (int)$product['id']; ?>">
                                        <h3 class="productName tp_product_name"><?= htmlspecialchars($product['name']) ?></h3>
                                    </a>

                                    <div class="wrappMidInfo">
                                        <div class="pro-color-selector">
                                            <div class="frameImageChilds">
                                                <?php
                                                $colors = $product['color_options'] ?? [];
                                                $maxColors = 3;
                                                $totalColors = is_array($colors) ? count($colors) : 0;
                                                $remainingColors = max(0, $totalColors - $maxColors);
                                                ?>

                                                <?php if ($totalColors > 0): ?>
                                                    <ul class="color-swatches text-center">
                                                        <?php for ($i = 0; $i < min($maxColors, $totalColors); $i++):
                                                            $color = $colors[$i];
                                                        ?>
                                                            <li class="color-item">
                                                                <span style="background-color: <?= htmlspecialchars($color['color_code'] ?? '#ccc') ?>"></span>
                                                            </li>
                                                        <?php endfor; ?>
                                                    </ul>
                                                    <?php if ($remainingColors > 0): ?>
                                                        <a class="numberColor">+<?= $remainingColors ?></a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>


                                        <!-- ===== WISHLIST ===== -->
                                        <?php
                                        $pid   = (int)$product['id'];
                                        $liked = !empty($wishlistProductMap[$pid]);
                                        ?>
                                        <form action="index.php?user=toggleWishlist" method="post"
                                            class="wishlistAdd wishlistItems" style="display:inline">
                                            <input type="hidden" name="product_id" value="<?= $pid ?>">
                                            <button type="submit"
                                                aria-label="<?= $liked ? 'Bỏ khỏi yêu thích' : 'Thêm vào yêu thích' ?>"
                                                class="wishlist-btn<?= $liked ? ' active' : '' ?>"
                                                style="background:none;border:none;padding:0;cursor:pointer;">
                                                <i class="<?= $liked ? 'fas' : 'far' ?> fa-heart"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="productPrice">
                                        <span class="priceNew onlyPrice tp_product_price">
                                            <?= number_format($product['sale_price'] ?? $product['price'], 0, ',', '.') ?>đ
                                        </span>
                                        <?php if (!empty($product['sale_price'])): ?>
                                            <del class="product-price-old tp_product_detail_price_old">
                                                <?= number_format($product['price'], 0, ',', '.') ?>đ
                                            </del>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php $sold = (int)($product['sold_count'] ?? 0); ?>
                                <?php if ($sold > 0): ?>
                                    <div class="hook-reviews">
                                        <span class="number-purchase">(<?= number_format($sold, 0, ',', '.') ?> đã bán)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>


                        <ul class="pagination col-lg-12 col-md-12 hidden-sm hidden-xs">
                            <div class="paginator">
                                <span class="labelPages"><?= $startItem ?> - <?= $endItem ?> / <?= $totalProducts ?></span>
                                <span class="titlePages">&nbsp;&nbsp;Trang: </span>

                                <?php if ($hasProducts && $currentPage > 1): ?>
                                    <a
                                        rel="nofollow, noindex"
                                        class="paging-previous ico"
                                        title="Trang trước"
                                        href="index.php?user=productsByCategory&category_id=<?= (int)$categoryId ?>&sort=<?= htmlspecialchars($sort) ?><?= $qKeyword ?>&pg=<?= $currentPage - 1 ?>">
                                    </a>
                                <?php endif; ?>

                                <?php if ($hasProducts): ?>
                                    <?php for ($i = 1; $i <= (int)$totalPages; $i++): ?>
                                        <?php if ($i == (int)$currentPage): ?>
                                            <span class="currentPage"><?= $i ?></span>
                                        <?php else: ?>
                                            <a
                                                rel="nofollow, noindex"
                                                href="index.php?user=productsByCategory&category_id=<?= (int)$categoryId ?>&sort=<?= htmlspecialchars($sort) ?><?= $qKeyword ?>&pg=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                <?php endif; ?>

                                <?php if ($hasProducts && $currentPage < (int)$totalPages): ?>
                                    <a
                                        rel="nofollow, noindex"
                                        class="paging-next ico"
                                        title="Trang sau"
                                        href="index.php?user=productsByCategory&category_id=<?= (int)$categoryId ?>&sort=<?= htmlspecialchars($sort) ?><?= $qKeyword ?>&pg=<?= $currentPage + 1 ?>">
                                    </a>
                                <?php endif; ?>
                            </div>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- (tuỳ chọn) Giữ icon tim gọn nhẹ nếu theme đang ẩn -->
<style>
    .wishlist-btn .fa-heart {
        font-size: 16px;
        line-height: 1
    }
</style>