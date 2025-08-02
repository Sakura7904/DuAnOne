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


<div class="p-6 bg-neutral-bg rounded-2xl border border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Danh sách danh mục</h2>
        <a href="index.php?act=create_category" class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
            ➕ Thêm danh mục
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px] text-left border border-gray-300 rounded overflow-hidden">
            <thead class="bg-gray-100 text-sm uppercase text-gray-600">
                <tr>
                    <th class="py-3 px-4 border-b">ID</th>
                    <th class="py-3 px-4 border-b">Tên danh mục</th>
                    <th class="py-3 px-4 border-b">Danh mục cha</th>
                    <th class="py-3 px-4 border-b">Ngày tạo</th>
                    <th class="py-3 px-4 border-b">Cập nhật lần cuối</th>
                    <th class="py-3 px-4 border-b text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 bg-white">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-2 px-4 border-b"><?= $cat['id'] ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($cat['name']) ?></td>
                            <td class="py-2 px-4 border-b">
                                <?= !empty($cat['parent_id']) && isset($allCategories[$cat['parent_id']])
                                    ? $allCategories[$cat['parent_id']]
                                    : 'Không có' ?>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <?= !empty($cat['created_at']) ? date('d/m/Y H:i:s', strtotime($cat['created_at'])) : '-' ?>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <?= !empty($cat['updated_at']) ? date('d/m/Y H:i:s', strtotime($cat['updated_at'])) : '-' ?>
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                <a href="index.php?act=edit_category&id=<?= $cat['id'] ?>" class="text-blue-600 hover:underline">✏️ Sửa</a>
                                |
                                <a href="index.php?act=delete_category&id=<?= $cat['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">🗑️ Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">Không có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>