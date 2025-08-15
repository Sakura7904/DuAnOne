<?php
// FLASH
$errors = $_SESSION['errors_variants'] ?? [];
$old    = $_SESSION['old_variants'] ?? [];
unset($_SESSION['errors_variants'], $_SESSION['old_variants']);

// Dữ liệu controller cung cấp
$variant         = $data['variant'] ?? [];         // getById() đã trả: id, product_id, price, sale_price, quantity, image_url, attribute_values[], images[]...
$products        = $data['products'] ?? [];        // [ ['id'=>..., 'name'=>...], ... ]
$colors          = $data['colors'] ?? [];          // [ ['id'=>value_id, 'value'=>'Đỏ', 'color_code'=>'#f00'], ... ]
$sizes           = $data['sizes'] ?? [];           // [ ['id'=>value_id, 'value'=>'M'], ... ]
$imagesByProduct = $data['imagesByProduct'] ?? []; // product_id => [ ['url'=>'/path/a.jpg'], ... ]

// Helper
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Lấy màu/size đang chọn từ variant.attribute_values (attribute_id: 1=Color, 2=Size)
$selectedColorId = $old['color_value_id'] ?? null;
$selectedSizeId  = $old['size_value_id']  ?? null;
if (!$selectedColorId || !$selectedSizeId) {
    if (!empty($variant['attribute_values'])) {
        foreach ($variant['attribute_values'] as $av) {
            if ((int)$av['attribute_id'] === 1) $selectedColorId = $selectedColorId ?? $av['value_id'];
            if ((int)$av['attribute_id'] === 2) $selectedSizeId  = $selectedSizeId  ?? $av['value_id'];
        }
    }
}

$pid         = (string)($old['product_id'] ?? $variant['product_id'] ?? '');
$imageUrlOld = $old['image_url'] ?? null;
$cancelHref  = '?admin=list_variant' . ($pid ? '&product_id=' . urlencode($pid) : '');
?>
<div>
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Sửa biến thể #<?= (int)$variant['id'] ?></h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[17px]">
        <div class="flex items-center gap-x-1">
            <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
            <a class="capitalize" href="?admin=dashboard">home</a>
        </div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Sửa biến thể</span>
    </div>

    <!-- GIỮ Y NGUYÊN CLASSES, CHỈ ĐỔI ACTION -->
    <form action="?admin=update_variant" method="POST" id="variantEditForm">
        <input type="hidden" name="id" value="<?= (int)$variant['id'] ?>">
        <!-- hidden value_ids[] để tương thích controller->update() (đang đọc value_ids[]) -->
        <input type="hidden" name="value_ids[]" id="hid_color_value_id" value="">
        <input type="hidden" name="value_ids[]" id="hid_size_value_id" value="">

        <div class="flex gap-x-12 border rounded-2xl justify-between flex-col gap-y-12 bg-white border-neutral pt-[50px] pb-[132px] px-[39px] dark:border-dark-neutral-border lg:flex-row lg:gap-y-0 dark:bg-[#1F2128]">
            <div class="lg:max-w-[610px]">

                <!-- SẢN PHẨM -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Sản phẩm</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="product_id" id="product_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <?php foreach ($products as $p): ?>
                            <?php $sel = ((string)$p['id'] === $pid) ? 'selected' : ''; ?>
                            <option value="<?= (int)$p['id'] ?>" <?= $sel ?>>
                                <?= h($p['name']) ?> (ID: <?= (int)$p['id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['product_id'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['product_id']) ?></p>
                <?php endif; ?>

                <!-- MÀU -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Màu</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="color_value_id" id="color_value_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <option value="">--Chọn màu--</option>
                        <?php foreach ($colors as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= ((string)$selectedColorId === (string)$c['id'] ? 'selected' : '') ?>><?= h($c['value']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['color_value_id'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['color_value_id']) ?></p>
                <?php endif; ?>

                <!-- SIZE -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Size</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="size_value_id" id="size_value_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <option value="">--Chọn size--</option>
                        <?php foreach ($sizes as $s): ?>
                            <option value="<?= (int)$s['id'] ?>" <?= ((string)$selectedSizeId === (string)$s['id'] ? 'selected' : '') ?>><?= h($s['value']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['size_value_id'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['size_value_id']) ?></p>
                <?php endif; ?>

                <!-- GIÁ BÁN -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Giá bán</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="price" id="price"
                        value="<?= h($old['price'] ?? $variant['price'] ?? '') ?>"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" step="0.01" placeholder="Nhập giá bán">
                </div>
                <?php if (!empty($errors['price'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['price']) ?></p>
                <?php endif; ?>

                <!-- GIÁ KM -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Giá khuyến mãi (tuỳ chọn)</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="sale_price" id="sale_price"
                        value="<?= h($old['sale_price'] ?? $variant['sale_price'] ?? '') ?>"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" step="0.01" placeholder="Nhập giá khuyến mãi">
                </div>
                <?php if (!empty($errors['sale_price'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['sale_price']) ?></p>
                <?php endif; ?>

                <!-- SỐ LƯỢNG -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Số lượng</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="quantity"
                        value="<?= h($old['quantity'] ?? $variant['quantity'] ?? '0') ?>"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" min="0" placeholder="Nhập số lượng">
                </div>
                <?php if (!empty($errors['quantity'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['quantity']) ?></p>
                <?php endif; ?>

                <div class="">
                    <button type="submit"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                        Cập nhật
                    </button>
                    <a href="?admin=list_variant"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- ẢNH CHỌN TỪ SẢN PHẨM (SELECT) -->
            <div style="width: 610px;">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Ảnh biến thể (chọn)</p>

                <!-- Ảnh đại diện -->
                <div class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border">
                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Chọn ảnh đại diện từ thư viện ảnh của sản phẩm</p>
                    <select name="image_url" id="image_url"
                        class="select w-full mt-3 border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400">
                        <option value="">--Chọn ảnh đại diện--</option>
                        <!-- option sẽ được fill theo sản phẩm đã chọn -->
                    </select>
                    <div id="thumbPreviewArea" class="mt-3"></div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    /**
     * Dữ liệu ảnh theo sản phẩm từ PHP -> JS
     * imagesByProduct = { product_id: [ {url:'...'}, ... ] }
     */
    const imagesByProduct = <?= json_encode($imagesByProduct, JSON_UNESCAPED_SLASHES) ?>;
    const initState = <?= json_encode([
                            'product_id' => (string)$pid,
                            'image_url'  => $imageUrlOld ?? ($variant['image_url'] ?? ''),
                            'color_id'   => (string)($selectedColorId ?? ''),
                            'size_id'    => (string)($selectedSizeId ?? ''),
                        ], JSON_UNESCAPED_SLASHES) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('variantEditForm');
        const productSelect = document.getElementById('product_id');
        const imageSelect = document.getElementById('image_url');
        const thumbPreview = document.getElementById('thumbPreviewArea');

        const selColor = document.getElementById('color_value_id');
        const selSize = document.getElementById('size_value_id');
        const hidColor = document.getElementById('hid_color_value_id');
        const hidSize = document.getElementById('hid_size_value_id');

        function rebuildImageOptions(productId) {
            const list = imagesByProduct[productId] || [];
            imageSelect.innerHTML = '<option value="">--Chọn ảnh đại diện--</option>';
            list.forEach(it => {
                const opt = document.createElement('option');
                opt.value = it.url;
                opt.textContent = it.url;
                imageSelect.appendChild(opt);
            });

            // set selected nếu có
            if (initState.product_id && String(initState.product_id) === String(productId) && initState.image_url) {
                imageSelect.value = initState.image_url;
            }
            renderThumbPreview(imageSelect.value);
        }

        function renderThumbPreview(url) {
            thumbPreview.innerHTML = '';
            if (!url) return;
            const box = document.createElement('div');
            box.style.cssText = 'position:relative;display:inline-block;';
            box.innerHTML = `
            <img src="${url}" style="max-width:200px;max-height:150px;object-fit:cover;border-radius:8px;" alt="preview">
        `;
            thumbPreview.appendChild(box);
        }

        function syncValueIds() {
            // đẩy về value_ids[] để controller->update() đọc được
            hidColor.value = selColor.value || '';
            hidSize.value = selSize.value || '';
        }

        productSelect?.addEventListener('change', function() {
            rebuildImageOptions(this.value);
        });
        imageSelect?.addEventListener('change', function() {
            renderThumbPreview(this.value);
        });
        selColor?.addEventListener('change', syncValueIds);
        selSize?.addEventListener('change', syncValueIds);
        form.addEventListener('submit', syncValueIds);

        // Khởi tạo
        if (productSelect?.value) {
            rebuildImageOptions(productSelect.value);
        }
        // set sẵn hidden value_ids[]
        syncValueIds();
    });
</script>
<?php if (!empty($_SESSION['alert'])): ?>
    <?php
    $a = $_SESSION['alert'];
    unset($_SESSION['alert']);
    $icon  = in_array($a['type'] ?? '', ['success', 'error', 'warning', 'info', 'question']) ? $a['type'] : 'info';
    $title = $icon === 'success' ? 'Thành công'
        : ($icon === 'error'   ? 'Có lỗi xảy ra'
            : ($icon === 'warning' ? 'Cảnh báo' : 'Thông báo'));
    $msg   = $a['message'] ?? '';
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: <?= json_encode($icon) ?>,
                title: <?= json_encode($title) ?>,
                html: <?= json_encode($msg) ?>,
                confirmButtonText: 'OK',
                confirmButtonColor: '#7C4DFF' // màu brand của bạn
            });
        });
    </script>
<?php endif; ?>