<?php
$categories = $categories ?? [];
$allCategories = $allCategories ?? [];

// Flash message
if (isset($_SESSION['msg'])) {
    $msg  = $_SESSION['msg'];
    $type = $_SESSION['msg_type'] ?? 'info';
    unset($_SESSION['msg'], $_SESSION['msg_type']);

    $bgClass = match ($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error'   => 'bg-red-100 border-red-400 text-red-700',
        default   => 'bg-gray-100 border-gray-400 text-gray-700',
    }; ?>
    <div class="px-4 py-3 mb-4 border rounded <?= $bgClass ?>">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php } ?>

<h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh mục</h2>
<div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
    <div class="flex items-center gap-x-1">
        <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
        <a class="capitalize" href="index.php?admin=dashboard">home</a>
    </div>
    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
    <span class="capitalize text-color-brands">Danh mục</span>
</div>

<div class="p-6 bg-neutral-bg rounded-2xl border border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="capitalize text-gray-1100 font-bold text-[20px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh sách danh mục</h2>
        <a href="index.php?admin=create_category" class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
            ➕ Thêm danh mục
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">STT</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Ảnh</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Tên danh mục</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Danh mục cha</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Ngày tạo</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Cập nhật lần cuối</th>
                    <th class="text-left text-normal text-gray-400 pb-[15px] dark:text-gray-dark-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $key => $cat): ?>
                    <?php
                        $raw = $cat['image_url'] ?? '';
                        if ($raw === '' || $raw === null) {
                            $src = '/assets/images/placeholder-category.png';
                        } else {
                            $src = (preg_match('#^https?://#', $raw) || str_starts_with($raw, '/'))
                                ? $raw
                                : '/DuAnOne/' . ltrim($raw, '/'); // base path dự án
                        }
                    ?>
                    <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                        <td class="py-[18px]"><?= $key + 1 ?></td>
                        <td class="py-[18px]">
                            <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" width="40" height="40" style="object-fit:cover;border-radius:8px;">
                        </td>
                        <td class="py-[18px]"><?= htmlspecialchars($cat['name']) ?></td>
                        <td class="py-[18px]">
                            <?= (!empty($cat['parent_id']) && isset($allCategories[$cat['parent_id']]))
                                ? htmlspecialchars($allCategories[$cat['parent_id']])
                                : 'Không có' ?>
                        </td>
                        <td class="py-[18px]">
                            <?= !empty($cat['created_at']) ? date('d/m/Y H:i:s', strtotime($cat['created_at'])) : '-' ?>
                        </td>
                        <td class="py-[18px]">
                            <?= !empty($cat['updated_at']) ? date('d/m/Y H:i:s', strtotime($cat['updated_at'])) : '-' ?>
                        </td>
                        <td class="py-[18px]">
                            <a href="index.php?admin=edit_category&id=<?= (int)$cat['id'] ?>" class="text-blue-600 hover:underline">✏️ Sửa</a>
                            |
                            <a href="index.php?admin=delete_category&id=<?= (int)$cat['id'] ?>"
                               class="text-red-600 hover:underline"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa?')">🗑️ Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($categories)): ?>
                    <tr><td colspan="7" class="py-6 text-center text-gray-500">Chưa có danh mục nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
