<?php
$category = $category ?? [];
$parent_categories = $parent_categories ?? [];
?>
<div class="">
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Danh mục</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
        <div class="flex items-center gap-x-1">
            <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
            <a class="capitalize" href="index.php?admin=dashboard">home</a>
        </div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <a href="index.php?admin=list_categories"><span class="capitalize text-color-brands">Danh mục</span></a>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Sửa danh mục</span>
    </div>
</div>

<div class="p-6 bg-white rounded-xl shadow border dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">✏️ Sửa danh mục</h2>
    <form action="index.php?admin=update_category&id=<?= (int)$category['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

        <!-- Tên danh mục -->
        <div>
            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                Tên danh mục<span class="text-red-500">*</span>
            </p>
            <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-8 md:mb-12">
                <input name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>"
                       class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                       type="text" placeholder="Add name" required>
            </div>
        </div>

        <!-- Danh mục cha -->
        <div>
            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Danh mục cha</p>
            <select name="parent_id"
                    class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-12">
                <option value="" <?= ($category['parent_id'] === null ? 'selected' : '') ?>>-- Không có --</option>
                <?php foreach ($parent_categories as $cat): ?>
                    <?php if ($cat['id'] == $category['id']) continue; // không cho chọn chính nó ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ((int)$cat['id'] === (int)($category['parent_id'] ?? 0)) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ảnh danh mục -->
     <div>
    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
        Ảnh danh mục
    </p>
    <!-- giữ ảnh hiện tại để controller biết xóa/thay -->
    <input type="hidden" name="current_image" value="<?= htmlspecialchars($category['image_url'] ?? '') ?>">

    <div class="border rounded-lg border-[#E8EDF2] dark:border-[#313442] p-4">
        <input type="file" name="image" accept="image/*"
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                      file:rounded file:border-0 file:text-sm file:font-semibold
                      file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
    </div>
</div>


        <!-- Submit -->
        <div>
            <button type="submit"
                    class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                Cập nhật danh mục
            </button>
            <a href="index.php?admin=list_categories"
               class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                Quay lại
            </a>
        </div>
    </form>
</div>

