<link rel="stylesheet" href="https://web.nvnstatic.net/tp/T0356/css/checkout.css?v=6" type="text/css">
<script defer type="text/javascript" src="https://web.nvnstatic.net/tp/T0356/js/order.js?v=6"></script>
<?php
$customer = $customer ?? ['name' => '', 'phone' => '', 'email' => ''];
$items    = $items    ?? [];
$totals   = $totals   ?? ['total_original_fmt' => '0đ', 'total_discount_fmt' => '0đ', 'total_payable_fmt' => '0đ'];
?>
<div class="content">
    <div class="container noPadding">
        <div class="headCategory hidden-xs hidden-sm" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif?v=1719919543);"></div>
        <div class="wrapBoxSearch">
            <form class="bigSearchBar" action="/search" method="get">
                <input type="text" class="search-box" aria-label="Search" name="q" placeholder="Bạn muốn tìm sản phẩm gì ?">
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
                <a class="129976" href="/cart/checkout">Thanh toán</a>
            </li>
        </ul>
        <form action="index.php?user=actCheckoutFromCart" method="post" class="clearfix">
            <div class="sidebar col-md-4 col-lg-4 col-sm-12 col-xs-12 noPadding">
                <div class="titleTop hidden-lg hidden-md">
                    Thanh toán
                </div>
                <div class="sidebar-content">
                    <div class="order-summary">
                        <div class="order-summary-sections">
                            <div class="order-summary-section order-summary-section-product-list product-pc">
                                <div class="product-table">
                                    <?php foreach ($items as $it): ?>
                                        <div class="productItemTable">
                                            <div class="product-image">
                                                <img class="product-thumbnail-image" alt="<?= htmlspecialchars($it['product_name']) ?>"
                                                    src="<?= htmlspecialchars($it['image_url']) ?>">
                                            </div>
                                            <div class="product-description">
                                                <span class="product-description-name">
                                                    <?= htmlspecialchars($it['product_name']) ?> - <?= $it['color_name'] ?> - <?= $it['size_name'] ?>
                                                </span>
                                                <div class="product-price">
                                                    <?php if (!is_null($it['sale_price'])): ?>
                                                        <span class="order-summary-emphasis"><?= $it['sale_price_fmt'] ?></span>
                                                        <span style="margin-left:6px;color:#999"><s><?= $it['original_price_fmt'] ?></s></span>
                                                    <?php else: ?>
                                                        <span class="order-summary-emphasis"><?= $it['original_price_fmt'] ?></span>
                                                    <?php endif; ?>
                                                    <a class="fixCart" href="?user=cart">Sửa</a>
                                                </div>
                                                <div class="quantityBox">
                                                    <span>Số lượng: <?= (int)$it['quantity'] ?></span>
                                                </div>
                                            </div>
                                            <a class="remove-item-cart" data-id="40770042" data-name="Đầm trễ vai nhẹ đính dây nơ chân bèo tầng - Đen - M" data-price="875000" href="javascript:"><i class="fal fa-times"></i></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="order-summary-section order-summary-section-discount">
                                <div class="fieldset">
                                    <div class="orderCheckoutWrapp">
                                        <div class="each-row">
                                            <h3>Tổng cộng</h3>
                                        </div>
                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Tổng tiền hàng: </span>
                                                <span class="totals_price1">
                                                    <?= $totals['total_original_fmt'] ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Giảm giá: </span>
                                                <span class="totals_price1">- <?= $totals['total_discount_fmt'] ?></span>
                                            </div>
                                        </div>
                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Tạm tính: </span>
                                                <span class="totals_price1"><?= $totals['total_payable_fmt'] ?></span>
                                            </div>
                                        </div>
                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Phí ship: </span>
                                                <span class="totals_price1" value="0" id="shipFee">0đ</span>
                                            </div>
                                            <span id="showCarrier"></span>
                                            <style>
                                                #showCarrier {
                                                    display: block;
                                                    text-align: right;
                                                    width: 100%;
                                                    font-size: 11px;
                                                }

                                                #showCarrier img {
                                                    width: auto;
                                                }

                                                .changeOrtherShipFee:hover {
                                                    color: darkred;
                                                    text-decoration: underline;
                                                }
                                            </style>
                                        </div>

                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Cần thanh toán: </span>
                                                <strong class="totals_price2" id="showTotalMoney" value="2721500"><?= $totals['total_payable_fmt'] ?></strong>
                                                <input type="hidden" id="getMn" value="2721500" name="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main col-md-8 col-lg-8 col-sm-12 col-xs-12">
                <div class="main-content">
                    <div class="step">
                        <div class="step-sections steps-onepage">
                            <div class="section">
                                <div class="section-header">
                                    <h2 class="section-title">Thông tin giao hàng</h2>
                                </div>
                                <div class="section-content section-customer-information no-mb">
                                    <div class="fieldset">
                                        <div class="field field-required emailWrapper">
                                            <div class="field-input-wrapper">
                                                <label>Tên</label>
                                                <input placeholder="Họ và tên" class="field-input" size="30" type="text"
                                                    id="billing_address_full_name" name="receiver_name"
                                                    value="<?= htmlspecialchars($customer['name'] ?? '') ?>">
                                            </div>
                                            <div class="field-input-wrapper">
                                                <label>Điện thoại</label>
                                                <input placeholder="Số điện thoại" class="field-input" size="30" maxlength="10" type="tel"
                                                    id="billing_address_phone" name="receiver_phone"
                                                    value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="field field-required">
                                            <div class="field-input-wrapper">
                                                <label>Địa chỉ Email</label>
                                                <input placeholder="Địa chỉ Email" class="field-input" size="30" type="email" id="checkout_user_email"
                                                    name="customerEmail"
                                                    value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="field field-required">
                                            <div class="field-input-wrapper">
                                                <label>Địa chỉ</label>
                                                <input placeholder="Địa chỉ" id="shipping_address" autocomplete="off"
                                                    name="shipping_address" class="field-input" size="30" type="text">
                                                <div id="suggestions" class="suggestions"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-content">
                                    <div class="fieldset">
                                        <div class="field field-show-floating-label field-required">
                                            <div class="field-input-wrapper">
                                                <label for="city">Tỉnh/Thành phố</label>
                                                <input class="field-input" type="text"
                                                    id="city" name="city" placeholder="Nhập tỉnh/thành phố">
                                            </div>

                                            <div class="field-input-wrapper">
                                                <label for="district">Quận/Huyện</label>
                                                <input class="field-input" type="text"
                                                    id="district" name="district" placeholder="Nhập tỉnh/thành phố">
                                            </div>

                                            <div class="field-input-wrapper">
                                                <label for="ward">Phường/Xã</label>
                                                <input class="field-input" type="text"
                                                    id="ward" name="ward" placeholder="Nhập tỉnh/thành phố">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fieldNotice">
                                    <div class="field-input-wrapper">
                                        <h3 class="field-label">Lời nhắn</h3>
                                        <div class="descriptionCustomer">
                                            <textarea name="description" class="input" placeholder="Ghi chú thêm (Ví dụ: Giao hàng giờ hành chính)" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="change_pick_location_or_shipping">
                                    <div class="section">
                                        <div class="infoCustomer-header">
                                            <h3>Phương thức thanh toán</h3>
                                        </div>

                                        <div class="section-content" id="section-payment-method">
                                            <div class="content-box">
                                                <div class="radio-wrapper content-box-row b active">
                                                    <label class="radio-label b">
                                                        <div class="radio-input">
                                                            <input class="input-radio cod" checked name="payment_method" type="radio" value="COD">
                                                        </div>
                                                        <img class="imagePayment" src="https://web.nvnstatic.net/tp/T0356/img/003-salary 1.png?v=9" style="max-width:50px" alt="">
                                                        <div class="radio-label-primary">
                                                            <span>Thanh toán khi nhận hàng (COD)</span>
                                                            <p>Miễn phí vận chuyển cho mọi đơn hàng trên 500.000đ</p>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="radio-wrapper content-box-row b active">
                                                    <label class="radio-label">
                                                        <div class="radio-input">
                                                            <input class="input-radio" name="payment_method" type="radio" value="MOMO_CC">
                                                        </div>
                                                        <div class="radio-label-primary">
                                                            <span>Thanh toán MOMO</span>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="radio-wrapper content-box-row b active">
                                                    <label class="radio-label">
                                                        <div class="radio-input">
                                                            <input class="input-radio" name="payment_method" type="radio" value="STRIPE">
                                                        </div>
                                                        <div class="radio-label-primary">
                                                            <span>Thanh toán STRIPE - Thẻ quốc tế</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="textPromotion">
                                    <p>Nếu bạn không hài lòng về sản phẩm của chúng tôi . Bạn hoàn toàn có thể trả lại sản phẩm.</p>
                                </div>

                                <!--                                <div class="order-summary-section noteCheckout">-->
                                <!--                                    --><!--                                </div>-->
                            </div>
                        </div>
                        <button type="submit" class="btn checkout-accept">Đặt hàng ngay</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Đặt script sau các input hoặc thêm defer nếu để trong <head> -->
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const apiKey = 'SrMw2WaWfyU3GjhmDtIx3Imld2ltZVCI1G41x0mi'; // demo; nên gọi qua backend để bảo mật
        const addressInput = document.getElementById('shipping_address');
        const suggestionsContainer = document.getElementById('suggestions');
        const cityInput = document.getElementById('city'); // có thể null nếu trang không có
        const districtInput = document.getElementById('district');
        const wardInput = document.getElementById('ward');
        const checkoutForm = document.getElementById('checkoutForm');

        if (!addressInput || !suggestionsContainer) {
            console.warn('Thiếu #shipping_address hoặc #suggestions trong DOM.');
            return;
        }

        // Fallback cho randomUUID
        function uuid() {
            if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                return window.crypto.randomUUID();
            }
            // fallback đơn giản
            return Date.now() + '-' + Math.random().toString(16).slice(2);
        }

        let sessionToken = uuid();

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        const debouncedSearch = debounce((query) => {
            if (!query || query.length < 2) {
                suggestionsContainer.style.display = 'none';
                suggestionsContainer.innerHTML = '';
                return;
            }

            fetch(`https://rsapi.goong.io/Place/AutoComplete?api_key=${apiKey}&input=${encodeURIComponent(query)}&sessiontoken=${sessionToken}`)
                .then(r => r.json())
                .then(data => {
                    if (!data || data.status !== 'OK' || !Array.isArray(data.predictions)) {
                        suggestionsContainer.style.display = 'none';
                        suggestionsContainer.innerHTML = '';
                        return;
                    }

                    suggestionsContainer.innerHTML = '';
                    suggestionsContainer.style.display = 'block';

                    data.predictions.forEach(prediction => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = prediction.description;
                        div.addEventListener('click', () => {
                            addressInput.value = prediction.description;
                            suggestionsContainer.style.display = 'none';

                            // Có trường nào thì mới gán (tránh lỗi null)
                            if (prediction.compound) {
                                if (cityInput) cityInput.value = prediction.compound.province || '';
                                if (districtInput) districtInput.value = prediction.compound.district || '';
                                if (wardInput) wardInput.value = prediction.compound.commune || '';
                            }
                        });
                        suggestionsContainer.appendChild(div);
                    });
                })
                .catch(err => {
                    console.error('Lỗi fetch:', err);
                    suggestionsContainer.style.display = 'none';
                });
        }, 300);

        addressInput.addEventListener('input', (e) => debouncedSearch(e.target.value));

        document.addEventListener('click', function(e) {
            if (!suggestionsContainer.contains(e.target) && e.target !== addressInput) {
                suggestionsContainer.style.display = 'none';
            }
        });

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                sessionToken = uuid(); // làm mới session token mỗi lần submit
                alert('Theo dõi mình để xem thêm các video công nghệ nhé!');
                // checkoutForm.submit(); // nếu muốn submit thật sự thì bỏ comment
            });
        } else {
            // Không có form thì bỏ qua phần submit
            console.info('Không tìm thấy #checkoutForm. Bỏ qua handler submit.');
        }
    });
</script>

<style>
    .suggestions {
        position: absolute;
        background: #1a1d24;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        z-index: 1000;
        display: none;
        margin-top: 3px;
        border: 1px solid #3f4451;
    }

    .suggestion-item {
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #3f4451;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: #F2F2F2;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:before {
        content: "📍";
        margin-right: 10px;
        font-size: 1.1em;
        transition: transform 0.3s ease;
    }

    .suggestion-item:hover {
        background: #3a4150;
        color: #ffffff;
        padding-left: 24px;
    }

    .suggestion-item:hover:before {
        transform: scale(1.2);
    }

    .suggestion-item:after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .suggestion-item:hover:after {
        transform: scaleY(1);
    }

    .address-container {
        position: relative;
        margin-bottom: 20px;
    }

    /* Tùy chỉnh thanh cuộn */
    .suggestions::-webkit-scrollbar {
        width: 8px;
    }

    .suggestions::-webkit-scrollbar-track {
        background: #1a1d24;
        border-radius: 8px;
    }

    .suggestions::-webkit-scrollbar-thumb {
        background: #3f4451;
        border-radius: 8px;
    }

    .suggestions::-webkit-scrollbar-thumb:hover {
        background: #4f5565;
    }

    #phone {
        filter: blur(5px)
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sec = document.getElementById('section-payment-method');
        if (!sec) return;

        const rows = sec.querySelectorAll('.content-box-row');
        const showAll = () => rows.forEach(r => r.style.display = 'block');

        // Lần đầu vào đảm bảo hiện hết
        showAll();

        // Mỗi lần đổi radio, theme sẽ ẩn bớt -> mình hiển thị lại
        sec.addEventListener('change', (e) => {
            if (e.target.matches('input[type="radio"][name="payment_method"]')) {
                setTimeout(showAll, 0); // đợi theme chạy xong rồi bật lại
            }
        }, true); // capture để chạy trước/ưu tiên
    });
</script>

<?php if (isset($_SESSION['order_alert'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?= $_SESSION['order_alert']['type'] ?>',
                title: '<?= $_SESSION['order_alert']['type'] == 'success' ? 'Thành công!' : ($_SESSION['order_alert']['type'] == 'error' ? 'Lỗi!' : 'Thông báo!') ?>',
                text: '<?= htmlspecialchars($_SESSION['order_alert']['message']) ?>',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
<?php
    unset($_SESSION['order_alert']);
endif;
?>