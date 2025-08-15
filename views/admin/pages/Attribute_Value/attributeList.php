<?php
$items      = $items ?? [];           // từ controller
$attributes = $attributes ?? [];      // dropdown filter
$filters    = $filters ?? ['attribute_id' => null, 'q' => ''];
$pagination = $pagination ?? null;
$sort       = $sort ?? 'av.id DESC';
?>
<script>
    <?php if (!empty($_SESSION['alert'])): $a = $_SESSION['alert'];
        unset($_SESSION['alert']); ?>
        Swal.fire({
            icon: '<?= $a['type'] ?>',
            title: '<?= $a['type'] === 'success' ? 'Thành công' : 'Thông báo' ?>',
            text: '<?= htmlspecialchars($a['message']) ?>',
            timer: 1800,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<div>
    <div class="flex items-center justify-between mb-[19px]">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Attribute Values</h2>
            <div class="flex items-center text-xs text-gray-500 gap-x-[11px]">
                <div class="flex items-center gap-x-1">
                    <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="">
                    <a href="index.php?admin=dashboard"><span class="capitalize">home</span></a>
                </div>
                <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="">
                <span class="capitalize text-color-brands">Attribute Values</span>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="?admin=attribute_value_create" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]">
                <img src="./assets/admin/assets/images/icons/icon-add.svg" alt="">
                <span class="text-white font-semibold text-[14px] leading-[21px]">Thêm giá trị</span>
            </a>
        </div>
    </div>

    <!-- FILTERS -->
    <form class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3" method="get" action="">
        <input type="hidden" name="admin" value="attribute_value">
        <div>
            <select name="attribute_id" class="w-full border rounded-2xl p-3 bg-neutral-bg dark:bg-dark-neutral-bg border-neutral dark:border-dark-neutral-border">
                <option value="">-- Tất cả thuộc tính --</option>
                <?php foreach ($attributes as $a): ?>
                    <option value="<?= (int)$a['id'] ?>" <?= (isset($filters['attribute_id']) && (int)$filters['attribute_id'] === (int)$a['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="Tìm theo value…"
                class="w-full border rounded-2xl p-3 bg-neutral-bg dark:bg-dark-neutral-bg border-neutral dark:border-dark-neutral-border">
        </div>
        <div class="flex gap-2">
            <button class="btn bg-[#E8EDF2] text-[#666] rounded-2xl px-6">Lọc</button>
            <a href="?admin=attribute_value" class="btn rounded-2xl px-6 border-0 bg-[#f3f3f3] dark:bg-[#313442]">Reset</a>
        </div>
    </form>

    <div class="border p-6 bg-neutral-bg rounded-2xl border-neutral pb-0 overflow-x-auto scrollbar-hide dark:bg-dark-neutral-bg dark:border-dark-neutral mb-[52px]">
        <table class="w-full min-w-[900px]">
            <thead>
                <tr>
                    <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                        <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">#</span></div>
                    </th>
                    <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                        <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Attribute</span></div>
                    </th>
                    <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                        <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Value</span></div>
                    </th>
                    <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                        <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Color</span></div>
                    </th>
                    <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                        <div class="flex text-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Action</span></div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <img src="./assets/admin/assets/images/empty-state.svg" alt="empty" class="w-[120px] opacity-70">
                                <div class="text-gray-500 dark:text-gray-dark-500 text-sm">Chưa có giá trị nào</div>
                                <a href="?admin=attribute_value_create" class="btn bg-color-brands text-white px-6 py-2 rounded-2xl">Thêm giá trị</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    // group theo attribute_name cho dễ nhìn
                    $grouped = [];
                    foreach ($items as $it) {
                        $grouped[$it['attribute_name']][] = $it;
                    }
                    $stt = 1 + (int)(($pagination['page'] ?? 1) - 1) * (int)($pagination['per_page'] ?? 20);
                    ?>
                    <?php foreach ($grouped as $attrName => $rows): ?>
                        <tr>
                            <td colspan="5" class="pt-6 pb-2 text-sm font-semibold text-color-brands"><?= htmlspecialchars($attrName) ?></td>
                        </tr>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td class="border-b border-neutral py-[18px] dark:border-dark-neutral-border"><?= $stt++; ?></td>
                                <td class="border-b border-neutral py-[18px] dark:border-dark-neutral-border"><?= htmlspecialchars($r['attribute_name']) ?></td>
                                <td class="border-b border-neutral py-[18px] dark:border-dark-neutral-border"><?= htmlspecialchars($r['value']) ?></td>
                                <td class="border-b border-neutral py-[18px] dark:border-dark-neutral-border">
                                    <?php if (!empty($r['color_code'])): ?>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block w-5 h-5 rounded" style="background: <?= htmlspecialchars($r['color_code']) ?>; border:1px solid #e5e7eb;"></span>
                                            <span class="text-sm text-gray-600 dark:text-gray-dark-500"><?= htmlspecialchars($r['color_code']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border-b border-neutral py-[18px] dark:border-dark-neutral-border">
                                    <div class="flex-wrap gap-2">
                                        <a href="?admin=attribute_value_edit&id=<?= (int)$r['id'] ?>"
                                            class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-4 py-[6px] dark:border-dark-neutral-bg">Sửa</a>
                                        <a href="#"
                                            onclick="deleteAttributeValue(<?= (int)$r['id'] ?>, '<?= htmlspecialchars($r['value']) ?>', '<?= htmlspecialchars($r['attribute_name']) ?>')"
                                            class="btn normal-case h-fit min-h-fit transition-all duration-300 px-4 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] py-[6px]">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($pagination) && $pagination['total_pages'] > 1):
        $pg = $pagination; ?>
        <div class="flex items-center gap-x-4 mt-8">
            <?php if ($pg['page'] > 1): ?>
                <a href="?admin=attribute_value&page=<?= $pg['page'] - 1 ?>&attribute_id=<?= urlencode($filters['attribute_id'] ?? '') ?>&q=<?= urlencode($filters['q'] ?? '') ?>" class="btn">Prev</a>
            <?php else: ?>
                <span class="btn opacity-50 cursor-not-allowed">Prev</span>
            <?php endif; ?>

            <?php
            $current = (int)$pg['page'];
            $total   = (int)$pg['total_pages'];
            $start   = max(1, $current - 2);
            $end     = min($total, $start + 4);
            if ($end - $start < 4) $start = max(1, $end - 4);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i === $current): ?>
                    <span class="btn bg-color-brands text-white"><?= $i ?></span>
                <?php else: ?>
                    <a href="?admin=attribute_value&page=<?= $i ?>&attribute_id=<?= urlencode($filters['attribute_id'] ?? '') ?>&q=<?= urlencode($filters['q'] ?? '') ?>" class="btn"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pg['page'] < $pg['total_pages']): ?>
                <a href="?admin=attribute_value&page=<?= $pg['page'] + 1 ?>&attribute_id=<?= urlencode($filters['attribute_id'] ?? '') ?>&q=<?= urlencode($filters['q'] ?? '') ?>" class="btn">Next</a>
            <?php else: ?>
                <span class="btn opacity-50 cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteAttributeValue(id, val, attrName) {
        Swal.fire({
            title: 'Xác nhận xoá?',
            html: `Bạn muốn xoá value <b>"${val}"</b> của thuộc tính <b>${attrName}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E23738',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ',
            reverseButtons: true
        }).then(rs => {
            if (rs.isConfirmed) {
                Swal.fire({
                    title: 'Đang xử lý...',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => Swal.showLoading()
                });
                window.location.href = '?admin=attribute_value_delete&id=' + id;
            }
        });
    }
</script>

<?php if (!empty($_SESSION['alert'])):
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']); ?>
    <script>
        Swal.fire({
            icon: '<?= $alert['type'] ?>', // success | error | warning | info | question
            title: '<?= $alert['type'] === 'success' ? 'Thành công' : 'Thông báo' ?>',
            text: '<?= $alert['message'] ?>',
            timer: 1800,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>