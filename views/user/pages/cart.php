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
                            <form id="cartSelectForm" method="POST" action="?user=orderSelected">
                                <div class="recent-orders hidden-sm hidden-xs col-md-8 col-lg-8">
                                    <div class="tableResponsivetab">
                                        <table class="table">
                                            <thead>
                                                <tr class="tt">
                                                    <td style="width:46px;">
                                                        <input type="checkbox" id="select-all">
                                                    </td>
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
                                                        <?php
                                                        $unitPrice = ($item['sale_price'] && $item['sale_price'] < $item['price']) ? (int)$item['sale_price'] : (int)$item['price'];
                                                        $qty = (int)$item['quantity'];
                                                        ?>
                                                        <tr class="idProduct"
                                                            data-id="<?= $item['cart_item_id'] ?>"
                                                            data-storeid="<?= $item['variant_id'] ?>"
                                                            data-unit-price="<?= $unitPrice ?>">
                                                            <td>
                                                                <input class="select-item"
                                                                    type="checkbox"
                                                                    name="selected_items[]"
                                                                    value="<?= $item['cart_item_id'] ?>">
                                                            </td>
                                                            <td class="imageWislist">
                                                                <a href="?user=detailProduct&id=<?= $item['product_id'] ?>">
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
                                                                        <input type="hidden" name="quantity" value="<?= max(1, $qty - 1) ?>">
                                                                        <button class="btnAction btn-minus" type="submit" <?= $qty <= 1 ? 'disabled' : '' ?>>-</button>
                                                                    </form>

                                                                    <!-- ô nhập -->
                                                                    <input class="form-control js-quantity-product number-sidebar"
                                                                        value="<?= $qty ?>"
                                                                        data-id="<?= $item['cart_item_id'] ?>"
                                                                        type="text">

                                                                    <!-- nút cộng -->
                                                                    <form method="POST" action="?user=updateCartQuantity" style="display:inline;">
                                                                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                                        <input type="hidden" name="quantity" value="<?= $qty + 1 ?>">
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
                                                        <td colspan="6" class="text-center" style="padding: 50px;">
                                                            <p>Giỏ hàng của bạn đang trống</p>
                                                            <a href="?user=shop" class="btn-large btn-buy">Tiếp tục mua sắm</a>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
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
                                                <span class="text-label">Tổng tiền hàng (toàn bộ giỏ): </span>
                                                <strong class="totals_price1"><?= number_format($cartTotal, 0, ',', '.') ?>đ</strong>
                                            </div>
                                        </div>

                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label">Đã chọn: </span>
                                                <strong class="totals_price1"><span id="selected-count">0</span> sản phẩm</strong>
                                            </div>
                                        </div>

                                        <div class="each-row">
                                            <div class="box-style">
                                                <span class="text-label" style="font-weight:600">Tổng tiền (đã chọn): </span>
                                                <strong class="totals_price2" id="selected-amount">0đ</strong>
                                            </div>
                                        </div>

                                        <?php if (!empty($cartItems)): ?>
                                            <div class="each-row">
                                                <button type="submit" class="btn-large btn-checkout" id="btn-buy-selected" style="width: 100%;">
                                                    Mua sản phẩm đã chọn
                                                </button>
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
                            </form> <!-- đóng form ở đây -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<script script>
    document.addEventListener('DOMContentLoaded', () => {
        const fmt = (n) => (n || 0).toLocaleString('vi-VN') + 'đ';

        const $form = document.getElementById('cartSelectForm');
        const $selectAll = document.getElementById('select-all');
        const $count = document.getElementById('selected-count');
        const $amount = document.getElementById('selected-amount');
        const $buyBtn = document.getElementById('btn-buy-selected');

        function getRowQty(row) {
            const qtyInput = row.querySelector('.js-quantity-product');
            const v = parseInt(qtyInput ? qtyInput.value : '0', 10);
            return Number.isFinite(v) && v > 0 ? v : 0;
        }

        function updateSelectedSummary() {
            const rows = Array.from(document.querySelectorAll('.idProduct'));
            let sum = 0;
            let cnt = 0;
            rows.forEach(row => {
                const cb = row.querySelector('.select-item');
                if (!cb || !cb.checked) return;
                const unit = parseInt(row.getAttribute('data-unit-price') || '0', 10);
                const qty = getRowQty(row);
                if (unit > 0 && qty > 0) {
                    sum += unit * qty;
                    cnt += 1;
                }
            });
            if ($count) $count.textContent = String(cnt);
            if ($amount) $amount.textContent = fmt(sum);
            if ($buyBtn) $buyBtn.disabled = (cnt === 0);
        }

        // “Chọn tất cả”
        if ($selectAll) {
            $selectAll.addEventListener('change', () => {
                document.querySelectorAll('.select-item').forEach(cb => cb.checked = $selectAll.checked);
                updateSelectedSummary();
            });
        }

        // Tick từng dòng
        document.addEventListener('change', (e) => {
            if (e.target && e.target.classList.contains('select-item')) {
                // sync trạng thái chọn tất cả
                const all = document.querySelectorAll('.select-item');
                const checked = document.querySelectorAll('.select-item:checked');
                if ($selectAll) $selectAll.checked = (all.length && checked.length === all.length);
                updateSelectedSummary();
            }
        });

        // Khi đổi số lượng (gõ tay), tính lại ngay
        document.addEventListener('input', (e) => {
            if (e.target && e.target.classList.contains('js-quantity-product')) {
                updateSelectedSummary();
            }
        });

        // Với nút +/- trong script trước, sau khi set value xong ta vẫn tính lại
        // (script cũ đã gán sự kiện click; ở đây chỉ nghe nổi bọt sau cùng)
        document.addEventListener('click', (e) => {
            if (e.target && (e.target.classList.contains('btn-plus') || e.target.classList.contains('btn-minus'))) {
                // đợi 1 tick cho input cập nhật
                setTimeout(updateSelectedSummary, 0);
            }
        });

        // Chặn submit nếu chưa chọn
        if ($form) {
            $form.addEventListener('submit', (e) => {
                const hasAny = document.querySelector('.select-item:checked');
                if (!hasAny) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Chưa chọn sản phẩm',
                            text: 'Vui lòng tick chọn ít nhất 1 sản phẩm để tiến hành đặt hàng.'
                        });
                    } else {
                        alert('Vui lòng chọn ít nhất 1 sản phẩm.');
                    }
                }
            });
        }

        // Khởi tạo
        updateSelectedSummary();
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