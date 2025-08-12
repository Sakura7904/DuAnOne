<?php
$category = $category ?? [];
$parent_categories = $parent_categories ?? [];

// Lỗi & dữ liệu cũ nếu submit fail (được set trong CategoriesController::update)
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// Giá trị hiển thị: ưu tiên $old, fallback sang $category
$valName     = $old['name']      ?? ($category['name']      ?? '');
$valParentId = $old['parent_id'] ?? ($category['parent_id'] ?? null);
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
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
            Tên Danh Mục <span class="text-red-500">*</span>
        </p>
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-2">
            <input name="name" id="name"
                value="<?= htmlspecialchars($valName) ?>"
                class="input bg-transparent text-sm leading-4 text-gray-700 dark:text-gray-dark-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit"
                type="text" placeholder="Nhập tên danh mục">
        </div>
        <?php if (!empty($errors['name'])): ?>
            <div class="mt-1  font-bold text-sm" style="color:red"><?= htmlspecialchars($errors['name']) ?></div>
        <?php endif; ?>

        <!-- Danh mục cha -->
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Danh Mục Cha</p>
        <select name="parent_id"
            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-700 dark:text-gray-dark-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] mb-12">
            <option value="">-- Không có --</option>
            <?php foreach ($parent_categories as $cat): ?>
                <?php if ((int)$cat['id'] === (int)$category['id']) continue; // không cho chọn chính nó 
                ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ((string)$cat['id'] === (string)$valParentId) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Ảnh danh mục -->
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Ảnh Danh Mục</p>
        <!-- giữ ảnh hiện tại để Controller biết xoá/thay -->

        <div
            class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border"
            id="thumbnailUploadArea" style="cursor: pointer;">
            <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
            <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Hãy chọn ảnh từ thư viện của bạn</p>
            <p class="leading-6 text-gray-400 text-[13px]">Hỗ trợ các file như: JPG, PNG & GIF</p>
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($category['image_url'] ?? '') ?>">

            <!-- Input file ẩn -->
            <input type="file"
                id="imageInput"
                name="image"
                style="display: none;">

            <!-- Preview area -->
            <div id="thumbnailPreview" class="mt-2">
                <?php if (!empty($category['image_url'])): ?>
                    <div style="position: relative; display: inline-block;">
                        <img src="<?= $category['image_url'] ?>"
                            style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px;"
                            alt="Preview">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($errors['image'])): ?>
            <div class="mt-1 text-red-500 font-bold text-sm" style="color:red"><?= htmlspecialchars($errors['image']) ?></div>
        <?php endif; ?>

        <!-- Submit -->
        <div class="">
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

<script>
    document.getElementById('thumbnailUploadArea').addEventListener('click', function() {
        document.getElementById('imageInput').click();
    });
</script>