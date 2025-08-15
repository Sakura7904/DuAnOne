<?php
// FLASH (đổi namespace riêng cho variant)
$errors = $_SESSION['errors_variants'] ?? [];
$old    = $_SESSION['old_variants'] ?? [];
unset($_SESSION['errors_variants'], $_SESSION['old_variants']);

// Dữ liệu controller cung cấp
$products        = $data['products'] ?? [];        // [ ['id'=>..., 'name'=>...], ... ]
$colors          = $data['colors'] ?? [];          // [ ['id'=>value_id, 'value'=>'Đỏ', 'color_code'=>'#f00'], ... ]
$sizes           = $data['sizes'] ?? [];           // [ ['id'=>value_id, 'value'=>'M'], ... ]
$imagesByProduct = $data['imagesByProduct'] ?? []; // product_id => [ ['url'=>'/path/a.jpg'], ... ]

// Helper safe
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<div>
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Thêm biến thể</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[17px]">
        <div class="flex items-center gap-x-1">
            <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
            <a class="capitalize" href="?admin=dashboard">home</a>
        </div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Thêm biến thể</span>
    </div>

    <!-- GIỮ Y NGUYÊN CLASSES, CHỈ ĐỔI ACTION -->
    <form action="?admin=store_variant" method="POST">
        <div class="flex gap-x-12 border rounded-2xl justify-between flex-col gap-y-12 bg-white border-neutral pt-[50px] pb-[132px] px-[39px] dark:border-dark-neutral-border lg:flex-row lg:gap-y-0 dark:bg-[#1F2128]">
            <div class="lg:max-w-[610px]">

                <!-- SẢN PHẨM -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Sản phẩm</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="product_id" id="product_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <option value="" disabled <?= empty($old['product_id']) ? 'selected' : '' ?>>--Chọn sản phẩm--</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= (int)$p['id'] ?>" <?= ((string)($old['product_id'] ?? '') === (string)$p['id']) ? 'selected' : '' ?>>
                                <?= h($p['name']) ?> (ID: <?= (int)$p['id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['product_id'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['product_id']) ?></p>
                <?php endif; ?>

                <!-- MÀU (attribute_id = 1) -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Màu</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="color_value_id" id="color_value_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <option value="" <?= empty($old['color_value_id']) ? 'selected' : '' ?>>--Chọn màu--</option>
                        <?php foreach ($colors as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= ((string)($old['color_value_id'] ?? '') === (string)$c['id']) ? 'selected' : '' ?>>
                                <?= h($c['value']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['color_value_id'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['color_value_id']) ?></p>
                <?php endif; ?>

                <!-- SIZE (attribute_id = 2) -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Size</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <select name="size_value_id" id="size_value_id"
                        class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400">
                        <option value="" <?= empty($old['size_value_id']) ? 'selected' : '' ?>>--Chọn size--</option>
                        <?php foreach ($sizes as $s): ?>
                            <option value="<?= (int)$s['id'] ?>" <?= ((string)($old['size_value_id'] ?? '') === (string)$s['id']) ? 'selected' : '' ?>>
                                <?= h($s['value']) ?>
                            </option>
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
                        value="<?= h($old['price'] ?? '') ?>"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" step="0.01" placeholder="Nhập giá bán">
                </div>
                <?php if (!empty($errors['price'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['price']) ?></p>
                <?php endif; ?>

                <!-- GIÁ KM (tuỳ chọn) -->
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Giá khuyến mãi (tuỳ chọn)</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="sale_price" id="sale_price"
                        value="<?= h($old['sale_price'] ?? '') ?>"
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
                        value="<?= h($old['quantity'] ?? '0') ?>"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" min="0" placeholder="Nhập số lượng">
                </div>
                <?php if (!empty($errors['quantity'])): ?>
                    <p class="text-[13px] text-[#E23738] mb-12"><?= h($errors['quantity']) ?></p>
                <?php endif; ?>

                <div class="">
                    <button type="submit"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                        Thêm biến thể
                    </button>
                    <a href="?admin=list_variant"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- ẢNH CHỌN TỪ SẢN PHẨM (SELECT) -->
            <?php $img = trim((string)($variant['image_url'] ?? '')); ?>

            <div style="width: 610px;">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                    Ảnh Biến Thể
                </p>

                <div class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border">
                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Chấp nhận các file <code>JPG, PNG,...</code></p>

                    <?php if ($img !== ''): ?>
                        <img class="mx-auto inline-block rounded-2xl mt-3"
                            style="max-width: 240px; max-height: 180px; object-fit: cover;"
                            src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="variant image">
                    <?php else: ?>
                        <p class="leading-6 text-gray-400 text-[13px] mt-3">Chưa có ảnh</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('variantEditForm');
        const selColor = document.getElementById('color_value_id');
        const selSize = document.getElementById('size_value_id');
        const hidColor = document.getElementById('hid_color_value_id');
        const hidSize = document.getElementById('hid_size_value_id');

        function syncValueIds() {
            hidColor.value = selColor?.value || '';
            hidSize.value = selSize?.value || '';
        }

        selColor?.addEventListener('change', syncValueIds);
        selSize?.addEventListener('change', syncValueIds);
        form?.addEventListener('submit', syncValueIds);
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