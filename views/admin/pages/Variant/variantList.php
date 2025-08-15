<?php
// Data từ controller
$variants       = $data['variants'] ?? [];
$totalVariants  = $data['totalVariants'] ?? count($variants);

// Helper nhỏ: format tiền
if (!function_exists('vnd_format')) {
    function vnd_format($n)
    {
        if ($n === null || $n === '') return '—';
        return number_format((float)$n, 0, ',', '.') . 'đ';
    }
}
?>

<div>
    <div class="flex items-center justify-between mb-[19px]">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh sách các biến thể</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xs text-gray-500 gap-x-[11px]">
                    <div class="flex items-center gap-x-1">
                        <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
                        <a href="index.php?admin=dashboard"><span class="capitalize">home</span></a>
                    </div>
                    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
                    <span class="capitalize text-color-brands">All Variant</span>
                </div>
            </div>
        </div>
        <div class="flex">
            <!-- GIỮ NGUYÊN CLASS, CHỈ ĐỔI LINK TỚI TẠO BIẾN THỂ -->
            <a href="?admin=variant#create" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]">
                <img src="./assets/admin/assets/images/icons/icon-add.svg" alt="add icon">
                <span class="text-white font-semibold text-[14px] leading-[21px]">Thêm biến thể</span>
            </a>
        </div>
    </div>

    <div class="border p-6 bg-neutral-bg rounded-2xl border-neutral pb-0 overflow-x-scroll scrollbar-hide dark:bg-dark-neutral-bg dark:border-dark-neutral mb-[52px] xl:overflow-x-hidden">
        <div class="text-base leading-5 text-gray-1100 font-semibold mb-6 dark:text-gray-dark-1100">
            Tổng số biến thể: <strong><?= (int)$totalVariants ?></strong>
        </div>

        <table class="w-full min-w-[900px]">
            <tr>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Image</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Variant</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Product</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Category</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Price</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">QTY</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex text-center justify-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Action</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
            </tr>

            <?php foreach ($variants as $v): ?>
                <?php
                // Các field controller nên chuẩn hoá sẵn; dưới đây có fallback an toàn
                $thumb = $v['thumbnail_display'] ?? $v['image_url'] ?? './assets/admin/assets/images/placeholder.png';
                $productName = $v['product_name'] ?? ('#' . (int)$v['product_id']);
                $variantName = $v['variant_name'] ?? ('Biến thể #' . (int)$v['id']);
                $categoryName = $v['category_name'] ?? 'Chưa phân loại';
                $priceDisplay = $v['price_display'] ?? vnd_format($v['sale_price'] ?? $v['price'] ?? 0);
                $qty = isset($v['quantity']) ? (int)$v['quantity'] : 0;

                // Danh sách thuộc tính (màu/size) — controller có thể đẩy sẵn color_list/size_list
                $colorList = $v['color_list'] ?? [];
                $sizeList  = $v['size_list'] ?? [];

                // Badge kho
                $stockClass = 'badge-danger';
                $stockText  = 'Hết hàng';
                if ($qty > 50) {
                    $stockClass = 'badge-success';
                    $stockText = 'Còn nhiều';
                } elseif ($qty > 10) {
                    $stockClass = 'badge-warning';
                    $stockText = 'Còn ít';
                } elseif ($qty > 0) {
                    $stockClass = 'badge-danger';
                    $stockText = 'Sắp hết';
                }
                ?>
                <tr>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <img class="img-thumbnail product-thumbnail border border-neutral rounded-lg dark:border-dark-neutral-border w-[150px]"
                            src="<?= htmlspecialchars($thumb) ?>" alt="variant image">
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div class="flex flex-col gap-y-1 max-w-[250px]">
                            <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">
                                <?= htmlspecialchars($variantName) ?>
                            </p>

                            <?php if (!empty($colorList)): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">
                                    Màu:
                                    <small class="text-muted d-inline">
                                        <?= htmlspecialchars(implode(', ', array_map('trim', $colorList))) ?>
                                    </small>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($sizeList)): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">
                                    Size:
                                    <small class="text-muted d-inline">
                                        <?= htmlspecialchars(implode(', ', array_map('trim', $sizeList))) ?>
                                    </small>
                                </p>
                            <?php endif; ?>

                            <?php if (empty($colorList) && empty($sizeList)): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">Chưa có thuộc tính</p>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">
                            <?= htmlspecialchars($productName) ?>
                        </p>
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">
                            <?= htmlspecialchars($categoryName) ?>
                        </p>
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">
                            <?= htmlspecialchars($priceDisplay) ?>
                        </p>
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div>
                            <span class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100 badge <?= $stockClass ?> badge-lg">
                                <?= $qty ?>
                            </span>
                            <br>
                            <small class="<?= str_replace('badge-', 'text-', $stockClass) ?>">
                                <?= $stockText ?>
                            </small>
                        </div>
                    </td>

                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div class="flex flex-col items-start items-center gap-y-2">
                            <!-- Xem/Sửa: trỏ về form edit của biến thể -->
                            <a href="?admin=variant#edit&id=<?= (int)$v['id'] ?>"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[9px]">
                                Xem
                            </a>
                            <a href="?admin=variant#edit&id=<?= (int)$v['id'] ?>"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[9px]">Sửa</a>

                            <!-- XÓA: GIỮ NGUYÊN STYLE, NHƯNG SUBMIT BẰNG POST TỚI variant#delete -->
                            <a href="#"
                                onclick="deleteVariant(<?= (int)$v['id'] ?>, '<?= htmlspecialchars($variantName) ?>')"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[9px]">
                                Xóa
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    /**
     * Xoá biến thể bằng POST tới ?admin=variant#delete (khớp Controller của bạn)
     * Giữ nguyên SweetAlert style như file cũ.
     */
    function deleteVariant(variantId, variantName) {
        Swal.fire({
            title: 'Xác nhận xóa biến thể?',
            html: `Bạn có chắc chắn muốn xóa biến thể:<br><strong>"${variantName}"</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E23738',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Xóa',
            cancelButtonText: '<i class="fas fa-times"></i> Hủy',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-secondary mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng đợi',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Tạo form POST ẩn để khớp controller delete()
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '?admin=variant#delete';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = variantId;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
<style>
    .product-thumbnail {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
    }

    .badge-outline-primary {
        color: #007bff;
        border: 1px solid #007bff;
        background-color: transparent;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5em 0.75em;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group-vertical .btn {
        margin-bottom: 2px;
    }

    .badge {
        font-size: 0.75em;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>