<section class="bread-crumb margin-bottom-10">
    <div class="container">
        <div class="headCategory hidden-xs hidden-sm"
            style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif);">
        </div>
        <div class="wrapBoxSearch">
            <form class="bigSearchBar" action="https://jm.com.vn/search" method="get">
                <input type="text" class="search-box" aria-label="Search" name="q"
                    placeholder="Bạn muốn tìm sản phẩm gì ?">
                <input class="hidden" name="rating" value="true">
                <button type="submit" class="search__btn">
                    Tìm kiếm ngay
                </button>
            </form>
        </div>
        <ul class="breadcrumbCate">
            <li>
                <a href="?user=home">Trang chủ</a>
            </li>
            <li>
                <a class="542064" href="https://jm.com.vn/dam-pc542064.html"><?= $product['category_name'] ?></a>
            </li>
            <li>
                <a class="542067"><?= $product['name'] ?></a>
            </li>
        </ul>
    </div>
</section>
<div class="visible-sm visible-xs fixedViewTop">
    <ul>
        <li>
            <a href="javascript:void(0)" class="childView active" data-type="image">
                Hình ảnh
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="childView" data-type="information">
                Thông tin
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="childView" data-type="comment">
                Nhận xét
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="childView" data-type="product">
                Sản phẩm khác
            </a>
        </li>
    </ul>
</div>
<section class="productWrap">
    <div class="container">
        <div class="details-product">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6 noPadding wrapperImage boxItem" data-name="image">
                <div class="imgZoom clearfix">
                    <div class="zoomImageBox">
                        <div class="zoomer">
                            <?php if (isset($imagesByVariant[$defaultVariant['id']])) : ?>
                                <?php foreach ($imagesByVariant[$defaultVariant['id']] as $index => $image): ?>
                                    <a href="<?= $image['image_url'] ?>"
                                        class="image-frame">
                                        <img width="100%" class="z cloudzoom"
                                            src="<?= $image['image_url'] ?>"
                                            data-cloudzoom="zoomImage: '<?= $image['image_url'] ?>'" />
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Chưa có ảnh con</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="zoomSlide">
                        <ul class="listImgZoom">
                            <?php if (isset($imagesByVariant[$defaultVariant['id']])) : ?>

                                <?php foreach ($imagesByVariant[$defaultVariant['id']] as $index => $image): ?>
                                    <li class="thumbnailImage"
                                        data-src="<?= $image['image_url'] ?>">
                                        <a>
                                            <img class='cloudzoom-gallery'
                                                src="<?= $image['image_url'] ?>"
                                                data-cloudzoom="useZoom: '.cloudzoom', image: '<?= $image['image_url'] ?>',
                                                 zoomImage: '<?= $image['image_url'] ?>'">
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Chưa có ảnh con</p>
                            <?php endif; ?>
                        </ul>
                        <!--                        <a href="javascript:void(0)" id="prevSlideZ" class="spriteIcon"></a>-->
                        <!--                        <a href="javascript:void(0)" id="nextSlideZ" class="spriteIcon"></a>-->

                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6 details-pro noPadding">
                <div class="popupBuyMobile">
                    <div class="boxProductMobile">
                        <div class="infoProductMobile">
                            <h1 class="title-head"><?= $product['name'] ?></h1>
                            <div class="boxHeadViewProduct">
                                <div class="product-code">Danh mục: <span><?= $product['category_name'] ?></span></div>
                                <div class="starbaprv-widget">
                                    <div class="starbap-prev-badge voteView0">
                                        <a class="starbap-star starbap--off star-1">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-2">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-3">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-4">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-5">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <span class="starbap-prev-badgetext">(0)</span>
                                    </div>
                                </div>
                                <a href="#box-rating" class="number-purchase">0 Nhận xét</a>
                            </div>
                        </div>
                        <div class="price-box" id="priceDisplay">
                            <?php if ($defaultVariant['sale_price'] && $defaultVariant['sale_price'] > 0): ?>
                                <span class="discountPrice tp_product_detail_price"><?= number_format($defaultVariant['sale_price']) ?>đ</span>
                                <del class="product-price-old tp_product_detail_price_old"><?= number_format($defaultVariant['price']) ?>đ</del>
                            <?php else: ?>
                                <span class="discountPrice tp_product_detail_price"><?= number_format($defaultVariant['price']) ?>đ</span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" id="prdInfo" data-category="Thu Đông 1F" data-id="40768009"
                            data-name="Đầm sát nách dáng ôm dài buộc nơ"
                            data-price="<?= $defaultVariant['sale_price'] && $defaultVariant['sale_price'] > 0 ? number_format($defaultVariant['sale_price']) : number_format($defaultVariant['price']) ?>">

                    </div>
                    <?php
                    // Tách riêng các thuộc tính
                    $colorAttribute = null;
                    $sizeAttribute = null;
                    $otherAttributes = [];

                    foreach ($attributes as $attribute) {
                        $attributeName = strtolower($attribute['name']);

                        if (in_array($attributeName, ['color', 'màu', 'màu sắc', 'mau', 'mau sac'])) {
                            $colorAttribute = $attribute;
                        } elseif (in_array($attributeName, ['size', 'kích thước', 'kich thuoc', 'kích cỡ', 'kich co'])) {
                            $sizeAttribute = $attribute;
                        } else {
                            $otherAttributes[] = $attribute;
                        }
                    }
                    ?>

                    <!-- ==== FORM ADD TO CART ==== -->
                    <form id="addToCartForm" action="?user=addToCart" method="POST">

                        <!-- Các thuộc tính chọn (giữ nguyên của bạn) -->
                        <div class="attr">
                            <!-- Chọn màu sắc -->
                            <div class="colorPicker clearfix">
                                <label class="control-label options-title">Màu sắc: </label>
                                <?php if (isset($colorAttribute['values'])) : ?>
                                    <?php foreach ($colorAttribute['values'] as $value): ?>
                                        <p class="color req" column="i1">
                                            <span class="itemColor">
                                                <a rel="nofollow" href="javascript:"
                                                    class="color-option-new"
                                                    data-color-value="<?= htmlspecialchars($value['value']) ?>"
                                                    title="<?= htmlspecialchars($value['value']) ?>"
                                                    style="background-color: <?= $value['color_code'] ?>">
                                                    <img src="https://pos.nvncdn.com/4ef0bf-108661/ps/20241107_TBLDN5nPSM.jpeg" alt="" />
                                                </a>
                                            </span>
                                        </p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Chưa có màu sắc</p>
                                <?php endif; ?>
                            </div>

                            <!-- Size -->
                            <div class="sizePicker clearfix" id="sizeSection" style="display:none;">
                                <label class="control-label options-title">Size: </label>
                                <div id="sizeContainer" class="size-options-inline"></div>
                            </div>

                            <!-- Tồn kho -->
                            <div class="stockInfo clearfix" id="stockSection" style="display:none;">
                                <label class="control-label options-title">Còn: </label>
                                <div id="stockContainer" class="stock-options-inline"></div>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="form-product">
                            <div class="clearfix form-group">
                                <div class="qty-ant clearfix custom-btn-number">
                                    <label>Số lượng:</label>
                                    <div class="custom custom-btn-numbers">
                                        <button type="button" class="btn-cts btn-minus-view">–</button>
                                        <input type="number" name="quantity" class="qty-detail input-text qty-view"
                                            value="1" id="quantityInput" min="1">
                                        <button type="button" class="btn-cts btn-plus-view">+</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút -->
                            <div class="btn-mua">
                                <div class="buttonWrapp">
                                    <button type="submit" class="btnAddToCart btnAtc unsel btn-outline tp_button mr-3">
                                        <span>Thêm vào giỏ hàng</span>
                                    </button>
                                    <button type="submit" name="buy_now" class="btnAddToCart btnAtc btn-outline tp_button">
                                        Mua ngay
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ==== HIDDEN INPUTS để POST ==== -->
                        <input type="hidden" name="product_id" id="productIdHidden" value="<?= (int)$product['id'] ?>">
                        <input type="hidden" name="variant_id" id="variantIdHidden" value="">
                        <input type="hidden" name="color_value" id="colorValueHidden" value="">
                        <input type="hidden" name="size_value" id="sizeValueHidden" value="">
                        <input type="hidden" name="stock_qty" id="stockHidden" value="">
                        <input type="hidden" name="variant_image" id="variantImageHidden" value="">

                    </form>

                    <div class="accordion-wrapper">
                        <div class="content descriptionProduct boxItem" data-name="information">
                            <div class="title-content">
                                <h2>Thông tin chi tiết</h2>
                                <i class="far fa-plus"></i>
                            </div>
                            <div class="descriptionContent">
                                <p><?= $product['description'] ?> </p>
                            </div>
                        </div>
                        <div class="content descriptionProduct">
                            <div class="title-content">
                                <h2>Bảng size</h2>
                                <i class="far fa-plus"></i>
                            </div>
                            <div class="descriptionContent">
                                <p><img src="https://pos.nvncdn.com/4ef0bf-108661/contentKey/20230428_sxfyDIA1.jpg"
                                        alt="20230428_sxfyDIA1.jpg" /><img
                                        src="https://pos.nvncdn.com/4ef0bf-108661/contentKey/20230531_D9iytd2BVuMY.png"
                                        alt="" /></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="box-rating" class="inner-box-content-detail box-review boxItem" data-name="comment">
            <div class="container">
                <div class="starbap-widget starbap-review-widget">
                    <div class="starbap-rev-widg">
                        <div class="starbap-rev-widg__header">
                            <h2 class="starbap-rev-widg__title">Đánh giá sản phẩm</h2>
                            <div class="starbap-rev-widg__summary-text">0 đánh giá</div>
                            <div class="reviewWrapper clearfix">
                                <div class="boxLeft">
                                    <div class="starbap-rev-widg__summary">
                                        <p class="starbap-rev-total-point">0.0 / <span>5</span></p>
                                        <div class="starbap-rev-widg__summary-stars voteView0">
                                            <a class="starbap-star starbap--off star-1">
                                                <i class="fa fa-star fa-fw"></i>
                                            </a>
                                            <a class="starbap-star starbap--off star-2">
                                                <i class="fa fa-star fa-fw"></i>
                                            </a>
                                            <a class="starbap-star starbap--off star-3">
                                                <i class="fa fa-star fa-fw"></i>
                                            </a>
                                            <a class="starbap-star starbap--off star-4">
                                                <i class="fa fa-star fa-fw"></i>
                                            </a>
                                            <a class="starbap-star starbap--off star-5">
                                                <i class="fa fa-star fa-fw"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="boxRight">
                                    <h4>Sản phẩm được đánh giá</h4>

                                    <!-- Product View Voted -->

                                    <ul>
                                        <li class="show-vote-5">
                                            <label class="title">5 sao </label>
                                            <span class="percentWrapper">
                                                <span class="percent" style="width: 0;"></span>
                                            </span>
                                            <span class="count">(0)</span>
                                        </li>
                                        <li class="show-vote-4">
                                            <label class="title">4 sao </label>
                                            <span class="percentWrapper">
                                                <span class="percent" style="width: 0;"></span>
                                            </span>
                                            <span class="count">(0)</span>
                                        </li>
                                        <li class="show-vote-3">
                                            <label class="title">3 sao </label>
                                            <span class="percentWrapper">
                                                <span class="percent" style="width: 0;"></span>
                                            </span>
                                            <span class="count">(0)</span>
                                        </li>
                                        <li class="show-vote-2">
                                            <label class="title">2 sao </label>
                                            <span class="percentWrapper">
                                                <span class="percent" style="width: 0;"></span>
                                            </span>
                                            <span class="count">(0)</span>
                                        </li>
                                        <li class="show-vote-1">
                                            <label class="title">1 sao </label>
                                            <span class="percentWrapper">
                                                <span class="percent" style="width: 0;"></span>
                                            </span>
                                            <span class="count">(0)</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!--                        <a href="javascript:void(0);" class="starbap-write-rev-link">Chấm điểm và viết đánh giá sản phẩm</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="productSuggest-wrapper tp_product_detail_suggest boxItem" data-name="product">
            <h2 class="titleBox">
                Có thể bạn sẽ thích </h2>
            <div class="container-fluid">
                <div class="productSuggest productList clearfix owl-carousel">

                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="productItem prd40768686" data-id="40768686">
                            <div class="productImage">
                                <a
                                    href="?user=detailProduct&id=<?= $related['id'] ?>">
                                    <img loading="lazy"
                                        src="<?= $related['image_thumbnail'] ?>"
                                        alt="<?= $related['name'] ?>" data-hover="">
                                </a>
                                <!-- <span class="saleLabel">-50%</span> -->
                            </div>
                            <div class="productInfo">
                                <a
                                    href="?user=productDetail&id=<?= $related['id'] ?>">
                                    <h4 class="productName tp_product_name"><?= $related['name'] ?></h4>
                                </a>
                                <div class="wrappMidInfo">
                                    <div class="pro-color-selector">
                                        <div class="frameImageChilds">
                                            <?php
                                            $maxColors = 3; // Tối đa 3 màu
                                            $totalColors = count($related['color_options']);
                                            $remainingColors = max(0, $totalColors - $maxColors);
                                            ?>
                                            <ul class="color-swatches text-center" data-hover="true" psId="40768686"
                                                data-handle="/dam-co-beo-ngan-tay-chan-xep-ly-chum-tay-ngan-p40768686.html"
                                                column="">
                                                <?php for ($i = 0; $i < min($maxColors, $totalColors); $i++): ?>
                                                    <?php $color = $related['color_options'][$i]; ?>
                                                    <!-- data-img="https://pos.nvncdn.com/4ef0bf-108661/ps/20250217_5TYb8DULPB.jpeg" -->
                                                    <li class="color-item"
                                                        value="40768687">
                                                        <span style="background-color: <?= htmlspecialchars($color['color_code']) ?>; "></span>

                                                    </li>
                                                <?php endfor; ?>
                                            </ul>
                                            <?php if ($remainingColors > 0): ?>
                                                <!-- href="https://jm.com.vn/dam-co-beo-ngan-tay-chan-xep-ly-chum-tay-ngan-p40768686.html" -->
                                                <a
                                                    class="numberColor">
                                                    +<?= $remainingColors ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a class="wishlistAdd wishlistItems" href="javascript:void(0)" data-id="40768686">
                                        <!--                                        <i class="far fa-heart"></i>-->
                                        <svg width="14px" height="14px" viewBox="0 0 15 12" version="1.1"
                                            class="wishlist-icon">
                                            <g stroke-width="1" fill-rule="evenodd" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <g transform="translate(-384.000000, -173.000000)">
                                                    <g transform="translate(373.000000, 160.000000)">
                                                        <path
                                                            d="M24.1870486,15.1337994 C23.0156479,13.7915995 21.0146777,13.6174702 19.6357465,14.7377957 C19.0576454,15.2115959 18.655296,15.8713213 18.4944667,16.6082814 C18.3352955,15.8721638 17.9348804,15.2124384 17.3587137,14.7377957 C15.9806115,13.6211213 13.9837864,13.7949698 12.8129383,15.1337994 C11.6169435,16.5248678 11.756771,18.6380112 13.125754,19.853546 C13.1564276,19.8807888 13.1873776,19.9074699 13.2191566,19.9335893 L17.900338,23.7840937 C18.2510121,24.0719688 18.7517383,24.0719688 19.1024124,23.7840937 L23.7808304,19.9335893 C25.1929223,18.7705741 25.4101247,16.6641713 24.2658052,15.228728 C24.2401057,15.1967106 24.2138535,15.1649742 24.1870486,15.1337994 L24.1870486,15.1337994 Z">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="productPrice">
                                    <span class="priceNew tp_product_price"> <?= number_format($related['min_price']) ?>đ</span>
                                    <?php if ($related['min_price'] != $related['max_price']): ?>
                                        <del class="oldPrice tp_product_price_old"> <?= number_format($related['max_price']) ?>đ</del>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="hook-reviews">
                                <div class="starbaprv-widget">
                                    <div class="starbap-prev-badge voteView0">
                                        <a class="starbap-star starbap--off star-1">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-2">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-3">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-4">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <a class="starbap-star starbap--off star-5">
                                            <i class="fas fa-star fa-fw"></i>
                                        </a>
                                        <span class="starbap-prev-badgetext">(0)</span>
                                    </div>
                                </div>
                                <span class="number-purchase">(322 đã bán)</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="flip"><span>Xem thêm <i class="far fa-angle-right"></i></div>
            </div>
        </div>
</section>

<script>
    /**
     * YÊU CẦU endpoint:
     *  - POST ?user=getSizesByColor -> { success: true, sizes: [{size_value, variant_id?}, ...] }
     *  - POST ?user=getVariantByColorAndSize -> {
     *      success: true,
     *      variant: { id|variant_id, price, sale_price, image_url }, quantity: <number>
     *    }
     */
    (function() {
        // ===== State & DOM =====
        let selectedColor = null;
        let selectedSize = null;
        let currentVariant = null;
        let currentStock = 0;

        const form = document.getElementById('addToCartForm');
        const qtyInput = document.getElementById('quantityInput');
        const btnPlus = document.querySelector('.btn-plus-view');
        const btnMinus = document.querySelector('.btn-minus-view');

        const sizeSection = document.getElementById('sizeSection');
        const sizeContainer = document.getElementById('sizeContainer');
        const stockSection = document.getElementById('stockSection');
        const stockContainer = document.getElementById('stockContainer');
        const priceDisplay = document.getElementById('priceDisplay'); // nếu có khối hiển thị giá

        const imgWrap = document.getElementById('variantImagePreviewWrap');
        const imgPreview = document.getElementById('variantImagePreview');

        const hidProductId = document.getElementById('productIdHidden');
        const hidVariantId = document.getElementById('variantIdHidden');
        const hidColor = document.getElementById('colorValueHidden');
        const hidSize = document.getElementById('sizeValueHidden');
        const hidStock = document.getElementById('stockHidden');
        const hidImage = document.getElementById('variantImageHidden');

        const currentProductId = toInt(hidProductId.value, 0);

        // ===== Utils =====
        function toInt(v, d = 0) {
            const n = parseInt(v, 10);
            return Number.isFinite(n) ? n : d;
        }

        function show(el) {
            if (el) el.style.display = 'block';
        }

        function hide(el) {
            if (el) el.style.display = 'none';
        }

        function toast(msg) {
            alert(msg);
        } // thay bằng toast UI nếu có

        // ===== Chọn màu =====
        document.querySelectorAll('.color-option-new').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedColor = btn.dataset.colorValue || null;
                hidColor.value = selectedColor || '';

                // UI chọn màu
                document.querySelectorAll('.color-option-new').forEach(x => x.classList.remove('selected'));
                btn.classList.add('selected');

                // Reset size/stock/variant/ảnh
                selectedSize = null;
                hidSize.value = '';
                currentVariant = null;
                currentStock = 0;
                hidVariantId.value = '';
                hidStock.value = '';
                hidImage.value = '';
                if (imgPreview) imgPreview.src = '';
                hide(imgWrap);

                qtyInput.removeAttribute('max');
                qtyInput.value = '1';
                hide(stockSection);
                hide(sizeSection);
                sizeContainer.innerHTML = '';

                if (!currentProductId || !selectedColor) {
                    toast('Thiếu product/màu');
                    return;
                }
                loadSizesByColor(selectedColor);
            });
        });

        // ===== Load sizes theo màu =====
        function loadSizesByColor(colorValue) {
            fetch('?user=getSizesByColor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        product_id: currentProductId,
                        color_value: colorValue
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data?.success && Array.isArray(data.sizes) && data.sizes.length) {
                        renderSizes(data.sizes);
                        show(sizeSection);
                    } else {
                        toast('Màu này hiện không có size có sẵn');
                    }
                })
                .catch(err => {
                    console.error(err);
                    toast('Không thể tải size');
                });
        }

        function renderSizes(sizes) {
            sizeContainer.innerHTML = '';
            sizes.forEach(size => {
                const p = document.createElement('p');
                p.className = 'size req';
                p.innerHTML = `
        <a href="javascript:void(0)"
           class="size-option-new"
           data-size-value="${size.size_value}"
           data-variant-id="${size.variant_id || ''}">
          ${size.size_value}
        </a>`;
                sizeContainer.appendChild(p);
            });

            sizeContainer.querySelectorAll('.size-option-new').forEach(a => {
                a.addEventListener('click', () => {
                    sizeContainer.querySelectorAll('.size-option-new').forEach(x => x.classList.remove('selected'));
                    a.classList.add('selected');

                    selectedSize = a.dataset.sizeValue || null;
                    hidSize.value = selectedSize || '';

                    if (!currentProductId || !selectedColor || !selectedSize) {
                        toast('Thiếu dữ liệu để lấy tồn kho');
                        return;
                    }
                    loadStockInfo(selectedColor, selectedSize);
                });
            });
        }

        // ===== Load stock + variant theo (color,size) =====
        function loadStockInfo(colorValue, sizeValue) {
            fetch('?user=getVariantByColorAndSize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        product_id: currentProductId,
                        color_value: colorValue,
                        size_value: sizeValue
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (!data?.success) {
                        toast('Không tìm thấy biến thể');
                        return;
                    }

                    const variant = data.variant || {};
                    const quantity = toInt(data.quantity, 0);

                    currentVariant = variant;
                    currentStock = quantity;

                    // Ghi hidden để POST
                    hidVariantId.value = String(variant.id || variant.variant_id || '');
                    hidStock.value = String(currentStock);
                    hidImage.value = variant.image_url || '';

                    // Ảnh preview (nếu có)
                    if (variant.image_url) {
                        if (imgPreview) imgPreview.src = variant.image_url;
                        show(imgWrap);
                    } else {
                        if (imgPreview) imgPreview.src = '';
                        hide(imgWrap);
                    }

                    // Set max + kẹp số lượng hiện tại
                    qtyInput.setAttribute('max', String(currentStock));
                    let curVal = toInt(qtyInput.value, 1);
                    if (currentStock > 0) {
                        if (curVal > currentStock) curVal = currentStock;
                        if (curVal < 1) curVal = 1;
                    } else {
                        curVal = 1;
                    }
                    qtyInput.value = String(curVal);

                    // UI tồn kho + giá
                    updateStockUI(quantity);
                    show(stockSection);
                    updatePriceUI(variant);
                })
                .catch(err => {
                    console.error(err);
                    toast('Không thể tải tồn kho');
                });
        }

        // ===== UI tồn kho & giá =====
        function updateStockUI(qty) {
            stockContainer.innerHTML = qty > 0 ?
                `<p class="stock-info text-success"><strong>${qty} sản phẩm</strong> có sẵn</p>` :
                `<p class="stock-info text-danger"><strong>Hết hàng</strong></p>`;
        }

        function updatePriceUI(variant) {
            if (!priceDisplay || !variant) return;
            const price = toInt(variant.price, 0);
            const sale = toInt(variant.sale_price, 0);
            priceDisplay.innerHTML = (sale > 0) ?
                `<span class="discountPrice tp_product_detail_price">${sale.toLocaleString()}đ</span>
         <del class="product-price-old tp_product_detail_price_old">${price.toLocaleString()}đ</del>` :
                `<span class="normalPrice tp_product_detail_price">${price.toLocaleString()}đ</span>`;
        }

        // ===== +/- & input số =====
        btnPlus?.addEventListener('click', () => {
            const max = toInt(qtyInput.getAttribute('max'), 0);
            const next = toInt(qtyInput.value, 1) + 1;
            qtyInput.value = String((max && next > max) ? max : next);
        });
        btnMinus?.addEventListener('click', () => {
            qtyInput.value = String(Math.max(1, toInt(qtyInput.value, 1) - 1));
        });
        qtyInput?.addEventListener('input', () => {
            const max = toInt(qtyInput.getAttribute('max'), 0);
            let val = toInt(qtyInput.value, 1);
            if (val < 1) val = 1;
            if (max && val > max) val = max;
            qtyInput.value = String(val);
        });

        // ===== Validate trước khi submit =====
        form.addEventListener('submit', (e) => {
            const qty = toInt(qtyInput.value, 1);
            const max = toInt(qtyInput.getAttribute('max'), 0);

            if (!hidVariantId.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin!',
                    text: 'Vui lòng chọn màu và size!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (max && qty > max) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Vượt quá tồn kho!',
                    text: 'Số lượng bạn chọn vượt quá số lượng tồn kho.',
                    confirmButtonText: 'OK'
                });
                return;
            }
            // OK -> submit: product_id, variant_id, color_value, size_value, stock_qty, quantity, variant_image
        });

        // ===== Khởi tạo =====
        hide(sizeSection);
        hide(stockSection);
        hide(imgWrap);

        // ===== Helper =====
        function toInt(v, d = 0) {
            const n = parseInt(v, 10);
            return Number.isFinite(n) ? n : d;
        }
    })();
</script>

<script>
    // Cập nhật hiển thị giá - sửa lại
    function updatePriceDisplay(variant) {
        const priceDisplay = document.querySelector('.price-box'); // Thay đổi selector

        if (variant.sale_price && variant.sale_price > 0) {
            priceDisplay.innerHTML = `
            <span class="discountPrice tp_product_detail_price">${parseInt(variant.sale_price).toLocaleString()}đ</span>
            <del class="product-price-old tp_product_detail_price_old">${parseInt(variant.price).toLocaleString()}đ</del>
        `;
        } else {
            priceDisplay.innerHTML = `
            <span class="discountPrice tp_product_detail_price">${parseInt(variant.price).toLocaleString()}đ</span>
        `;
        }

        // Cập nhật thông tin hidden input nếu cần
        const prdInfoInput = document.getElementById('prdInfo');
        if (prdInfoInput) {
            const displayPrice = variant.sale_price && variant.sale_price > 0 ? variant.sale_price : variant.price;
            prdInfoInput.setAttribute('data-price', parseInt(displayPrice).toLocaleString());
        }
    }

    // Hàm load thông tin tồn kho - đã sửa
    function loadStockInfo(colorValue, sizeValue) {
        fetch('?user=getVariantByColorAndSize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    product_id: currentProductId,
                    color_value: colorValue,
                    size_value: sizeValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật variant hiện tại
                    currentVariant = data.variant;

                    // Cập nhật giá TRƯỚC khi hiển thị stock info
                    updatePriceDisplay(data.variant);

                    // Hiển thị thông tin tồn kho
                    displayStockInfo(data.variant, data.quantity);
                    document.getElementById('stockSection').style.display = 'block';

                    // Cập nhật input quantity max
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        quantityInput.max = data.quantity;
                        // Reset quantity về 1 nếu vượt quá số lượng có sẵn
                        if (parseInt(quantityInput.value) > data.quantity) {
                            quantityInput.value = Math.min(1, data.quantity);
                        }
                    }

                } else {
                    alert('Không tìm thấy sản phẩm với màu và size này');
                }
            })
            .catch(error => {
                console.error('Lỗi khi load thông tin tồn kho:', error);
            });
    }
</script>

<style>
    /* Style để size giống hệt màu sắc */
    .size-options-inline,
    .stock-options-inline {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    /* Style cho màu sắc được chọn - border đỏ */
    .color-option-new {
        border: 2px solid transparent;
        /* Border trong suốt ban đầu */
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .color-option-new.selected {
        border: 3px solid #e74c3c !important;
        /* Border đỏ khi được chọn */
        box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2);
        /* Shadow đỏ nhẹ */
        transform: scale(1.05);
        /* Phóng to nhẹ */
    }

    .color-option-new:hover {
        border-color: #c0392b;
        /* Border đỏ đậm khi hover */
    }

    /* Đảm bảo màu sắc trong itemColor cũng có border đỏ */
    .itemColor .color-option-new.selected {
        border: 3px solid #e74c3c !important;
    }

    /* Style cho size được chọn - border đỏ */
    .size-option-new {
        display: inline-block;
        padding: 8px 16px;
        border: 2px solid #ddd;
        /* Border xám ban đầu */
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        background-color: white;
        color: #333;
        min-width: 45px;
        text-align: center;
        font-weight: 500;
    }

    .size-option-new:hover {
        border-color: #c0392b;
        /* Border đỏ đậm khi hover */
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .size-option-new.selected {
        background-color: #fff5f5 !important;
        /* Nền hồng nhẹ */
        color: #e74c3c !important;
        /* Chữ đỏ */
        border: 3px solid #e74c3c !important;
        /* Border đỏ */
        box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.15);
        /* Shadow đỏ nhẹ */
        font-weight: 600;
    }
</style>

<?php if (isset($_SESSION['alert'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['type'] == 'success' ? 'Thành công!' : ($_SESSION['alert']['type'] == 'error' ? 'Lỗi!' : 'Thông báo!') ?>',
                text: '<?= htmlspecialchars($_SESSION['alert']['message']) ?>',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
<?php
    unset($_SESSION['alert']);
endif;
?>