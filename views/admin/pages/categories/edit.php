<?php
$category = $category ?? [];
$parent_categories = $parent_categories ?? [];
?>

<div class="p-6 bg-white rounded-xl shadow border dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">✏️ Sửa danh mục</h2>

    <form action="index.php?act=update_category&id=<?= $category['id'] ?>" method="POST" class="space-y-6">
        <!-- Tên danh mục -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên danh mục <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" required
                   value="<?= htmlspecialchars($category['name']) ?>"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <!-- Danh mục cha -->
        <div>
            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Danh mục cha</label>
            <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Không có --</option>
                <?php foreach ($parent_categories as $cat): ?>
                    <?php if ($cat['id'] != $category['id']): // Không cho chọn chính nó làm cha ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $cat['id'] == $category['parent_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Submit -->
        <div class="pt-4">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                Cập nhật
            </button>
            <a href="index.php?act=list_categories"
               class="ml-3 text-gray-600 hover:underline dark:text-gray-300">Quay lại</a>
        </div>
    </form>
</div>
