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
                        <div class="price-box">
                            <?php if ($defaultVariant['sale_price'] && $defaultVariant['sale_price'] > 0): ?>
                                <span class="discountPrice tp_product_detail_price"><?= number_format($defaultVariant['sale_price']) ?>đ</span>
                                <del class="product-price-old tp_product_detail_price_old"><?= number_format($defaultVariant['price']) ?>đ</del>
                            <?php else: ?>
                                <span class="discountPrice tp_product_detail_price"><?= number_format($defaultVariant['price']) ?>đ</span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" id="prdInfo" data-category="Thu Đông 1F" data-id="40768009"
                            data-name="Đầm sát nách dáng ôm dài buộc nơ" data-price="995,000">

                    </div>
                    <div id="script-general-container"></div>
                    <script>
                        var fundiinDetailConfig = {
                            data: {
                                // ex: product price = 300.000đ
                                amount: 995000,
                            },
                            style: {}
                        };
                    </script>
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
                    <div class="attr">
                        <div class="colorPicker clearfix">
                            <label class="control-label options-title">Màu sắc: </label>
                            <?php if (isset($colorAttribute['values'])) : ?>
                                <?php foreach ($colorAttribute['values'] as $value): ?>
                                    <p class="color req" column="i1">
                                        <span class="itemColor">
                                            <a rel="nofollow" href="javascript:"
                                                data-cloudzoom="useZoom: '.cloudzoom', image: 'https://pos.nvncdn.com/4ef0bf-108661/ps/20241107_TBLDN5nPSM.jpeg', zoomImage: 'https://pos.nvncdn.com/4ef0bf-108661/ps/20241107_TBLDN5nPSM.jpeg'"
                                                class="cloudzoom-gallery " value="1755477" title="<?= $value['value'] ?>"
                                                data-pIds="40768010,40768011,40768012,40768013"
                                                selid="40768010,40768011,40768012,40768013"
                                                data-src="https://pos.nvncdn.com/4ef0bf-108661/ps/20241107_TBLDN5nPSM.jpeg"
                                                style="background-color: <?= $value['color_code'] ?>">
                                                <img
                                                    src="https://pos.nvncdn.com/4ef0bf-108661/ps/20241107_TBLDN5nPSM.jpeg"
                                                    alt="" />
                                            </a>
                                        </span>
                                    </p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Chưa có màu sắc</p>
                            <?php endif; ?>
                        </div>
                        <div class="sizePicker clearfix">
                            <label class="control-label options-title">Size: </label>
                            <?php if (isset($sizeAttribute['values'])) : ?>

                                <?php foreach ($sizeAttribute['values'] as $value): ?>
                                    <p class="size req" column="i2">
                                        <a value="1747441" href="javascript:void(0)" class=""><?= $value['value'] ?></a>
                                    </p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Chưa có size</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-product">
                        <div class="clearfix form-group">
                            <div class="qty-ant clearfix custom-btn-number">
                                <label>Số lượng:</label>
                                <div class="custom custom-btn-numbers">
                                    <button data-id="40768009" class="btn-cts btn-minus-view"
                                        type="button">–</button>
                                    <input type="text" data-id="40768009" class="qty-detail input-text qty-view"
                                        value="1" max="44">
                                    <button data-id="40768009" class="btn-cts btn-plus-view"
                                        type="button">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="btn-mua prdView40768009">
                            <a href="javascript:void(0)" class="wishlistItems" data-toggle="tooltip"
                                data-placement="left" title="Thêm vào yêu thích" data-id="40768009">
                                <svg width="26px" height="27px" viewBox="0 0 15 12" version="1.1"
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
                            <div class="buttonWrapp">
                                <button id="addQuickCart" psid="40768009" selId="40768009"
                                    title="Vui lòng chọn màu sắc hoặc kích cỡ!"
                                    class="btnAddToCart btnAtc unsel btn-outline tp_button mr-3" ck="0"><span>Thêm
                                        vào giỏ hàng</span>
                                </button>
                                <button class="btnAddToCart btnAtc btn-outline tp_button" type="button" id="buyNow"
                                    data-psid="40768009" data-selId="40768009"
                                    title="Vui lòng chọn màu sắc hoặc kích cỡ!" data-ck="0">
                                    Mua ngay
                                </button>
                            </div>

                        </div>

                    </div>
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