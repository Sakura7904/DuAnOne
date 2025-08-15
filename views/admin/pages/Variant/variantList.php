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
                    <a href="?admin=list_variant" class="capitalize text-color-brands">All Variant</a>
                </div>
            </div>
        </div>
        <div class="flex">
            <!-- GIỮ NGUYÊN CLASS, CHỈ ĐỔI LINK TỚI TẠO BIẾN THỂ -->
            <a href="?admin=add_variant" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]">
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
                            <a href="?admin=edit_variant&id=<?= (int)$v['id'] ?>"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[9px]">
                                Xem
                            </a>
                            <a href="?admin=edit_variant&id=<?= (int)$v['id'] ?>"
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

    <?php
    // ===== Pagination cho list_variant (dùng pg thay vì page) =====
    $varCur    = max(1, (int)($_GET['pg'] ?? ($variantPage ?? 1)));   // trang hiện tại
    $varTotal  = max(1, (int)($variantPages ?? 1));                   // tổng số trang
    $varWindow = 2;                                                   // hiển thị 2 trang trước/sau

    $varStart = max(1, $varCur - $varWindow);
    $varEnd   = min($varTotal, $varCur + $varWindow);
    if ($varEnd - $varStart < 4) {
        $varStart = max(1, min($varStart, $varEnd - 4));
        $varEnd   = min($varTotal, max($varEnd, $varStart + 4));
    }

    // build URL giữ filter, đổi sang pg cho list_variant
    function admin_variant_page_url_pg(int $p): string
    {
        $qs = $_GET;
        $qs['admin'] = 'list_variant'; // đúng route list_variant
        unset($qs['page']);            // tránh router cũ
        $qs['pg'] = $p;
        return '?' . http_build_query($qs);
    }

    // classes
    $varBtnBase = 'btn text-sm h-fit min-h-fit capitalize leading-4 border-0 font-semibold py-[11px] px-[18px]';
    $varBtnAct  = $varBtnBase . ' bg-color-brands hover:bg-color-brands text-white';
    $varBtnNorm = $varBtnBase . ' bg-transparent text-gray-1100 hover:text-white hover:bg-color-brands dark:text-gray-dark-1100';

    $varGhostBtn = 'items-center justify-center border rounded-lg border-neutral hidden gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border sm:flex';
    $varGhostDis = ' opacity-50 pointer-events-none';
    $varGhostTxt = 'text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400';

    // prev/next/first/last
    $varIsFirst = ($varCur <= 1);
    $varIsLast  = ($varCur >= $varTotal);
    $varPrevUrl = admin_variant_page_url_pg(max(1, $varCur - 1));
    $varNextUrl = admin_variant_page_url_pg(min($varTotal, $varCur + 1));
    ?>

    <div class="flex items-center gap-x-10">

        <!-- First & Prev -->
        <div class="hidden sm:flex items-center gap-x-2">
            <a class="<?= $varGhostBtn . ($varIsFirst ? $varGhostDis : '') ?>" href="<?= htmlspecialchars(admin_variant_page_url_pg(1)) ?>">
                <span class="<?= $varGhostTxt ?>">« Trang đầu</span>
            </a>
            <a class="<?= $varGhostBtn . ($varIsFirst ? $varGhostDis : '') ?>" href="<?= htmlspecialchars($varPrevUrl) ?>">
                <span class="<?= $varGhostTxt ?>">‹ Trước</span>
            </a>
        </div>

        <!-- Dãy số trang -->
        <div>
            <?php if ($varStart > 1): ?>
                <a class="<?= $varCur === 1 ? $varBtnAct : $varBtnNorm ?>" href="<?= htmlspecialchars(admin_variant_page_url_pg(1)) ?>">1</a>
                <?php if ($varStart > 2): ?><span class="px-2">…</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $varStart; $i <= $varEnd; $i++): ?>
                <a class="<?= $varCur === $i ? $varBtnAct : $varBtnNorm ?>" href="<?= htmlspecialchars(admin_variant_page_url_pg($i)) ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($varEnd < $varTotal): ?>
                <?php if ($varEnd < $varTotal - 1): ?><span class="px-2">…</span><?php endif; ?>
                <a class="<?= $varCur === $varTotal ? $varBtnAct : $varBtnNorm ?>" href="<?= htmlspecialchars(admin_variant_page_url_pg($varTotal)) ?>"><?= $varTotal ?></a>
            <?php endif; ?>
        </div>

        <!-- Next & Last -->
        <div class="hidden sm:flex items-center gap-x-2">
            <a class="<?= $varGhostBtn . ($varIsLast ? $varGhostDis : '') ?>" href="<?= htmlspecialchars($varNextUrl) ?>">
                <span class="<?= $varGhostTxt ?>">Sau ›</span>
            </a>
            <a class="<?= $varGhostBtn . ($varIsLast ? $varGhostDis : '') ?>" href="<?= htmlspecialchars(admin_variant_page_url_pg($varTotal)) ?>">
                <span class="<?= $varGhostTxt ?>">Trang cuối »</span>
            </a>
        </div>
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
                form.action = '?admin=delete_variant';

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