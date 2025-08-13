<?php
$hasProducts = ($totalProducts > 0);
$startItem   = $hasProducts ? (($currentPage - 1) * $perPage + 1) : 0;
$endItem     = $hasProducts ? min($startItem + count($products) - 1, $totalProducts) : 0;

// giữ lại keyword (nếu có) khi click phân trang
$qKeyword = ($keyword ?? '') !== '' ? '&keyword=' . urlencode($keyword) : '';
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
                            <div class="productItem mb-4 col-lg-3 col-md-3 col-xs-6 col-sm-6" data-id="<?php echo $product['id']; ?>">
                                <div class="productImage">
                                    <a href="index.php?user=detailProduct&id=<?php echo $product['id']; ?>">
                                        <img loading="lazy" src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                    </a>
                                </div>
                                <div class="productInfo">
                                    <a href="index.php?user=detailProduct&id=<?php echo $product['id']; ?>">
                                        <h3 class="productName tp_product_name"><?php echo $product['name']; ?></h3>
                                    </a>
                                    <div class="wrappMidInfo">
                                        <div class="pro-color-selector">
                                            <div class="frameImageChilds">
                                                <ul class="color-swatches text-center">
                                                    <?php foreach ($product['colors'] as $color): ?>
                                                        <li class="color-item" data-img="<?php echo $color['image_url']; ?>">
                                                            <span style="background-color: <?php echo $color['color_code']; ?>"></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <a class="wishlistAdd wishlistItems" href="javascript:void(0)" data-id="<?php echo $product['id']; ?>">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    </div>
                                    <div class="productPrice">
                                        <span class="priceNew onlyPrice tp_product_price">
                                            <?php echo number_format($product['sale_price'] ?? $product['price'], 0, ',', '.') . 'đ'; ?>
                                        </span>
                                        <?php if (!empty($product['sale_price'])): ?>
                                            <del class="product-price-old tp_product_detail_price_old">
                                                <?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?>
                                            </del>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="hook-reviews">
                                    <div class="starbaprv-widget">
                                        <div class="starbap-prev-badge voteView0">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <a class="starbap-star starbap--off star-<?php echo $i; ?>">
                                                    <i class="fas fa-star fa-fw"></i>
                                                </a>
                                            <?php endfor; ?>
                                            <span class="starbap-prev-badgetext">(0)</span>
                                        </div>
                                    </div>
                                    <span class="number-purchase">(322 đã bán)</span>
                                </div>
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