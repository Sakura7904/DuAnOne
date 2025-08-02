<?php
$categories = $categories ?? [];
$allCategories = $allCategories ?? [];

if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $type = $_SESSION['msg_type'] ?? 'info';

    unset($_SESSION['msg'], $_SESSION['msg_type']);

    $bgClass = match ($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error'   => 'bg-red-100 border-red-400 text-red-700',
        default   => 'bg-gray-100 border-gray-400 text-gray-700',
    };
?>
    <div class="px-4 py-3 mb-4 border rounded <?= $bgClass ?>">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php } ?>

<h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh mục</h2>
<div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
    <div class="flex items-center gap-x-1">
        <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
        <a class="capitalize" href="index.php?act=dashboard">home</a>
    </div>
    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
    <span class="capitalize text-color-brands">Danh mục</span>
</div>
<div class="p-6 bg-neutral-bg rounded-2xl border border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="capitalize text-gray-1100 font-bold text-[20px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh sách danh mục</h2>
        <a href="index.php?act=create_category" class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
            ➕ Thêm danh mục
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="text-left text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400"> STT </th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tên danh mục</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Danh mục cha</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Ngày tạo</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Cập nhật lần cuối</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $key => $cat): ?>
                    <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                        <td class="text-left"><?= $key + 1 ?> </td>
                        <td class="py-[25px]"><?= $cat['name'] ?> </td>
                        <td class="max-w-[196px]">
                            <?= !empty($cat['parent_id']) && isset($allCategories[$cat['parent_id']])
                                ? $allCategories[$cat['parent_id']]
                                : 'Không có' ?>
                        </td>
                        <td class="max-w-[196px]"><?= !empty($cat['created_at']) ? date('d/m/Y H:i:s', strtotime($cat['created_at'])) : '-' ?></td>
                        <td class="max-w-[196px]">
                            <?= !empty($cat['updated_at']) ? date('d/m/Y H:i:s', strtotime($cat['updated_at'])) : '-' ?>
                        </td>
                        <td class="max-w-[196px]">
                            <a href="index.php?act=edit_category&id=<?= $cat['id'] ?>" class="text-blue-600 hover:underline">✏️ Sửa</a>
                            |
                            <a href="index.php?act=delete_category&id=<?= $cat['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">🗑️ Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>