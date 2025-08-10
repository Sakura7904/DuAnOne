<script defer type="text/javascript" src="https://web.nvnstatic.net/tp/T0356/js/cart.js?v=3"></script>
<section class="main-wrapper">
    <section class="signup page_customer_account">
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
                    <a class="129976" href="/cart">Giỏ hàng</a>
                </li>
            </ul>
            <div class="col-main-acount">
                <div class="col-left-account">
                    <div class="page-title">
                        <h1 class="title-head">Giỏ hàng của bạn</h1>
                        <p>Có <span style="font-weight: 600"><?= $cartItemCount ?></span> sản phẩm trong giỏ hàng</p>
                    </div>
                    <div class="my-account">
                        <div class="dashboard">
                            <div class="recent-orders hidden-sm hidden-xs col-md-8 col-lg-8">
                                <div class="tableResponsivetab">
                                    <table class="table">
                                        <thead>
                                            <tr class="tt">
                                                <td class="image">Hình ảnh</td>
                                                <td class="infoTable">Thông tin</td>
                                                <td>Số lượng</td>
                                                <td>Giá tiền</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody id="wishlist-row40" class="cart">
                                            <?php if (!empty($cartItems)): ?>
                                                <?php foreach ($cartItems as $item): ?>
                                                    <tr class="idProduct" data-id="<?= $item['cart_item_id'] ?>" data-storeid="<?= $item['variant_id'] ?>">
                                                        <td class="imageWislist">
                                                            <a href="?user=product-detail&id=<?= $item['product_id'] ?>">
                                                                <img src="<?= $item['image_thumbnail'] ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" />
                                                            </a>
                                                        </td>
                                                        <td class="nameWislist">
                                                            <a href="?user=product-detail&id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['product_name']) ?></a>
                                                            <?php if (!empty($item['variant_attributes'])): ?>
                                                                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                                                    <?= htmlspecialchars($item['variant_attributes']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <a class="btn-buyNow quickView" data-id="<?= $item['product_id'] ?>"
                                                                href="?user=detailProduct&id=<?= $item['product_id'] ?>">Xem lại</a>
                                                        </td>
                                                        <td class="quantityProduct">
                                                            <div class="input-groupBtn cart-qty" data-max="<?= (int)$item['stock'] ?>">
                                                                <!-- nút trừ -->
                                                                <form method="POST" action="?user=updateCartQuantity" style="display:inline;">
                                                                    <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                                    <input type="hidden" name="quantity" value="<?= max(1, $item['quantity'] - 1) ?>">
                                                                    <button class="btnAction btn-minus" type="submit" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>-</button>
                                                                </form>

                                                                <!-- ô nhập -->
                                                                <input class="form-control js-quantity-product number-sidebar"
                                                                    value="<?= $item['quantity'] ?>"
                                                                    data-id="<?= $item['cart_item_id'] ?>"
                                                                    type="text">

                                                                <!-- nút cộng -->
                                                                <form method="POST" action="?user=updateCartQuantity" style="display:inline;">
                                                                    <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                                    <input type="hidden" name="quantity" value="<?= $item['quantity'] + 1 ?>">
                                                                    <button class="btnAction btn-plus" type="submit">+</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="priceWislist">
                                                                <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                                                                    <span class="priceNew onlyPrice tp_product_price"><?= number_format($item['sale_price'], 0, ',', '.') ?>đ</span>
                                                                <?php else: ?>
                                                                    <span class="priceNew onlyPrice tp_product_price"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="actitonWislist">
                                                            <a href="javascript:void(0);"
                                                                class="btn-remove-item"
                                                                data-href="?user=removeFromCart&cart_item_id=<?= $item['cart_item_id'] ?>">
                                                                <i class="fal fa-times"></i>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center" style="padding: 50px;">
                                                        <p>Giỏ hàng của bạn đang trống</p>
                                                        <a href="?user=shop" class="btn-large btn-buy">Tiếp tục mua sắm</a>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile View -->
                            <div class="hidden-lg hidden-md col-sm-12 col-xs-12 noPadding">
                                <div class="listProductWislist cart">
                                    <?php if (!empty($cartItems)): ?>
                                        <?php foreach ($cartItems as $item): ?>
                                            <div class="idProduct" data-id="<?= $item['cart_item_id'] ?>" data-storeid="<?= $item['variant_id'] ?>">
                                                <div class="wislistItem">
                                                    <div class="mediaImage">
                                                        <a href="?user=product-detail&id=<?= $item['product_id'] ?>">
                                                            <img class="productImg" src="<?= $item['variant_image'] ?: $item['image_thumbnail'] ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                        </a>
                                                    </div>
                                                    <div class="mediaBody">
                                                        <h3 class="productName">
                                                            <a href="?user=product-detail&id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['product_name']) ?></a>
                                                        </h3>
                                                        <div class="vendorName">
                                                            <?php if (!empty($item['variant_attributes'])): ?>
                                                                <?= htmlspecialchars($item['variant_attributes']) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="priceProductWislist">
                                                            <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                                                                <span class="priceNew onlyPrice tp_product_price"><?= number_format($item['sale_price'], 0, ',', '.') ?>đ</span>
                                                            <?php else: ?>
                                                                <span class="priceNew onlyPrice tp_product_price"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="quantityProduct">
                                                            <div class="input-groupBtn">
                                                                <form method="POST" action="?user=updateCartQuantity" class="frm-minus" style="display:inline;">
                                                                    <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                                    <input type="hidden" name="quantity" value="<?= $item['quantity'] - 1 ?>">
                                                                    <button class="btnAction" type="submit" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>-</button>
                                                                </form>

                                                                <!-- input -->
                                                                <input class="form-control js-quantity-product number-sidebar"
                                                                    value="<?= $item['quantity'] ?>"
                                                                    data-id="<?= $item['cart_item_id'] ?>"
                                                                    type="text">

                                                                <!-- form cộng -->
                                                                <form method="POST" action="?user=updateCartQuantity" class="frm-plus" style="display:inline;">
                                                                    <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                                    <input type="hidden" name="quantity" value="<?= $item['quantity'] + 1 ?>">
                                                                    <button class="btnAction" type="submit">+</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <a class="btn-buyNow viewAgain" href="?user=product-detail&id=<?= $item['product_id'] ?>">Xem lại</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div style="text-align: center; padding: 50px;">
                                            <p>Giỏ hàng của bạn đang trống</p>
                                            <a href="?user=shop" class="btn-large btn-buy">Tiếp tục mua sắm</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 noPadding">
                                <div class="orderWrapp">
                                    <div class="each-row">
                                        <h3>Tóm tắt đơn hàng</h3>
                                    </div>
                                    <div class="each-row">
                                        <div class="box-style">
                                            <span class="text-label">Tổng tiền hàng: </span>
                                            <strong class="totals_price1"><?= number_format($cartTotal, 0, ',', '.') ?>đ</strong>
                                        </div>
                                    </div>
                                    <div class="each-row">
                                        <div class="box-style">
                                            <span class="text-label">Giảm giá: </span>
                                            <strong class="totals_price1">- 0đ</strong>
                                        </div>
                                    </div>
                                    <div class="each-row">
                                        <div class="box-style">
                                            <span class="text-label">Tạm tính: </span>
                                            <strong class="totals_price1"><?= number_format($cartTotal, 0, ',', '.') ?>đ</strong>
                                        </div>
                                    </div>
                                    <div class="each-row">
                                        <div class="box-style">
                                            <span class="text-label" style="font-weight: 600">Tổng tiền: </span>
                                            <strong class="totals_price2"><?= number_format($cartTotal, 0, ',', '.') ?>đ</strong>
                                        </div>
                                    </div>

                                    <?php if (!empty($cartItems)): ?>
                                        <div class="each-row">
                                            <a class="btn-large btn-checkout" title="Tiến hành đặt hàng" href="?user=order">Tiến hành đặt hàng</a>
                                            <a class="btn-large btn-buy" title="Mua thêm sản phẩm" href="?user=shop">Mua thêm sản phẩm</a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($cartItems)): ?>
                                        <div class="each-row" style="margin-top: 15px;">
                                            <a class="btn-large btn-buy btn-clear-cart"
                                                style="background: #dc3545; border-color: #dc3545;"
                                                title="Xóa toàn bộ giỏ hàng"
                                                href="javascript:void(0);"
                                                data-href="?user=clearCart">
                                                Xóa toàn bộ giỏ hàng
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const DEBOUNCE_MS = 3000;
        const toInt = (v, d = 0) => {
            const n = parseInt(v, 10);
            return Number.isFinite(n) ? n : d;
        };
        const toast = (m) => alert(m);
        const timers = new Map(); // per cart_item_id

        document.querySelectorAll('.cart-qty, [data-max]').forEach(row => {
            const qtyInput = row.querySelector('.js-quantity-product');
            if (!qtyInput) return;

            // Lấy tồn kho
            const getMax = () => toInt(row.dataset.max ?? qtyInput.dataset.max ?? 0, 0);

            // Form dùng để submit (lấy 1 form update trong row)
            const forms = row.querySelectorAll('form[action*="updateCartQuantity"]');
            const formSubmit = forms[0] || row.querySelector('form');

            // Hiển thị: “SL: x / y” + trạng thái chờ
            let indicator = row.querySelector('.qty-indicator');
            if (!indicator) {
                indicator = document.createElement('small');
                indicator.className = 'qty-indicator';
                indicator.style.cssText = 'display:inline-block;margin-left:8px;opacity:.9;';
                qtyInput.insertAdjacentElement('afterend', indicator);
            }
            let pending = row.querySelector('.qty-pending');
            if (!pending) {
                pending = document.createElement('small');
                pending.className = 'qty-pending';
                pending.style.cssText = 'display:none;margin-left:6px;opacity:.7;';
                indicator.insertAdjacentElement('afterend', pending);
            }
            const updateIndicator = (val) => {
                const max = getMax();
                indicator.textContent = max ? `SL: ${val} / ${max}` : `SL: ${val}`;
            };
            const showPending = (msg = 'Đang chờ cập nhật…') => {
                pending.textContent = msg;
                pending.style.display = 'inline';
            };
            const hidePending = () => {
                pending.style.display = 'none';
            };

            // Kẹp min/max
            function clamp(val, notify = true) {
                const max = getMax();
                let v = toInt(val, 1);
                if (v < 1) {
                    v = 1;
                    if (notify) toast('Tối thiểu là 1 sản phẩm.');
                }
                if (max && v > max) {
                    v = max;
                    if (notify) toast(`Vượt quá tồn kho. Chỉ còn ${max} sản phẩm.`);
                }
                return v;
            }

            // Lên lịch submit sau 3s không ấn
            function scheduleSubmit(targetQty) {
                if (!formSubmit) return;
                const id = qtyInput.dataset.id || row.dataset.id || (formSubmit.querySelector('input[name="cart_item_id"]')?.value);

                // clear cũ + set hidden qty
                if (id && timers.has(id)) clearTimeout(timers.get(id));
                let hidden = formSubmit.querySelector('input[name="quantity"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'quantity';
                    formSubmit.appendChild(hidden);
                }
                hidden.value = String(targetQty);
                showPending();

                // đặt timer
                const t = setTimeout(() => {
                    hidePending();
                    formSubmit.submit(); // native, né handler của template
                    if (id) timers.delete(id);
                }, DEBOUNCE_MS);
                if (id) timers.set(id, t);
            }

            // Bắt click nút +/- (debounce 3s)
            row.querySelectorAll('form[action*="updateCartQuantity"] button[type="submit"]').forEach(btn => {
                btn.addEventListener('click', (ev) => {
                    ev.preventDefault();
                    ev.stopPropagation();
                    ev.stopImmediatePropagation();

                    const isPlus = btn.classList.contains('btn-plus');
                    const isMinus = btn.classList.contains('btn-minus');

                    const cur = toInt(qtyInput.value, 1);
                    let next = cur + (isPlus ? 1 : (isMinus ? -1 : 0));
                    next = clamp(next, true);

                    qtyInput.value = String(next);
                    updateIndicator(next);
                    scheduleSubmit(next);
                }, true); // capture để chặn handler template
            });

            // Nhập tay: Enter => cập nhật ngay (không debounce)
            qtyInput.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter') return;
                e.preventDefault();

                const next = clamp(qtyInput.value, true);
                qtyInput.value = String(next);
                updateIndicator(next);

                // hủy timer nếu đang chờ từ nút +/-
                const id = qtyInput.dataset.id || row.dataset.id || (formSubmit.querySelector('input[name="cart_item_id"]')?.value);
                if (id && timers.has(id)) {
                    clearTimeout(timers.get(id));
                    timers.delete(id);
                }

                hidePending();
                if (formSubmit) {
                    let hidden = formSubmit.querySelector('input[name="quantity"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'quantity';
                        formSubmit.appendChild(hidden);
                    }
                    hidden.value = String(next);
                    formSubmit.submit();
                }
            });

            // Cập nhật hiển thị ban đầu
            const initVal = clamp(qtyInput.value || '1', false);
            qtyInput.value = String(initVal);
            updateIndicator(initVal);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Xóa 1 sản phẩm
        document.querySelectorAll('.btn-remove-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-href');
                Swal.fire({
                    title: 'Xóa sản phẩm?',
                    text: "Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });

        // Xóa toàn bộ giỏ
        const clearCartBtn = document.querySelector('.btn-clear-cart');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                const url = this.getAttribute('data-href');
                Swal.fire({
                    title: 'Xóa toàn bộ giỏ hàng?',
                    text: "Bạn sẽ không thể hoàn tác sau khi xóa!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa hết',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        }

    });
</script>

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