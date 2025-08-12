<?php
$parent_categories = $parent_categories ?? [];

// Lấy lỗi & dữ liệu cũ từ session (được set trong CategoriesController::store)
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
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
        <span class="capitalize text-color-brands">Thêm danh mục</span>
    </div>
</div>

<div class="p-6 bg-white rounded-xl shadow border dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <h1 class="font-bold text-gray-1100 text-[24px] leading-[30px] dark:text-gray-dark-1100 tracking-[0.1px] mb-[20px]">➕ Thêm danh mục</h1>

    <form action="index.php?admin=store_category" method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Tên danh mục -->
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
            Tên Danh Mục<span class="text-red-500">*</span>
        </p>
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-2">
            <input
                name="name" id="name"
                value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                class="input bg-transparent text-sm leading-4 text-gray-700 dark:text-gray-dark-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit"
                type="text" placeholder="Nhập tên danh mục">
        </div>
        <?php if (!empty($errors['name'])): ?>
            <div class="mt-1 font-bold text-sm" style="color:red"><?= htmlspecialchars($errors['name']) ?></div>
        <?php endif; ?>

        <!-- Danh mục cha -->
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Danh Mục Cha</p>
        <select name="parent_id"
            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-700 dark:text-gray-dark-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] mb-12">
            <option value="">-- Không có --</option>
            <?php foreach ($parent_categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= (isset($old['parent_id']) && (string)$old['parent_id'] === (string)$cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Ảnh danh mục -->
        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Ảnh Danh Mục</p>
        <div
            class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border"
            id="thumbnailUploadArea" style="cursor: pointer;">
            <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
            <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Hãy chọn ảnh từ thư viện của bạn</p>
            <p class="leading-6 text-gray-400 text-[13px]">Hỗ trợ các file như: JPG, PNG & GIF</p>

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
        <div>
            <button type="submit"
                class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                Thêm danh mục
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