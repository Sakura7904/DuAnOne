<?php
$product = $data['product'] ?? [];
$categories = $data['categories'] ?? [];
$defaultVariant = $data['defaultVariant'] ?? null;
$galleryImages = $data['galleryImages'] ?? [];
$title = $data['title'] ?? 'Chi tiết sản phẩm';
?>
<div>
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]"><?= $title ?></h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[17px]">
        <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon"><a class="capitalize" href="?admin=dashboard">home</a></div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Chi tiết sản phẩm</span>
    </div>
    <form action="?admin=update_product" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <div class="flex gap-x-12 border rounded-2xl justify-between flex-col gap-y-12 bg-white border-neutral pt-[50px] pb-[132px] px-[39px] dark:border-dark-neutral-border lg:flex-row lg:gap-y-0 dark:bg-[#1F2128]">
            <div class="lg:max-w-[610px]">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Tến sản phẩm</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="name" value="<?= $product['name']  ?>" readonly
                        class="w-full input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="text" placeholder="Nhập tên sản phẩm">
                </div>

                <div class="flex justify-between flex-col lg:flex-row">
                    <div>
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Giá gốc</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                            <input name="price" id="price" value="<?= $defaultVariant ? $defaultVariant['price'] : 0 ?>" readonly
                                class="w-full input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                                type="number" placeholder="Nhập giá gốc">
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Tồn kho</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                            <input name="text" id="price" value="" readonly
                                class="w-full input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                                type="number" placeholder="Nhập giá gốc">
                        </div>
                    </div>
                </div>

                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Danh mục</p>
                <select disabled name="category_id" class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-12">
                    <option disabled="" selected="">--Chọn danh mục--</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"
                            <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                            <?php if ($category['parent_name']): ?>
                                (<?= htmlspecialchars($category['parent_name']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="flex justify-between flex-col lg:flex-row">
                    <div class="w-full mb-5">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Màu</p>
                        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-lg p-[15px] mt-[10px] min-h-[107px]">
                            <div class="flex flex-wrap gap-[10px]">
                                <?php if (!empty($product['colors_list'])) : ?>
                                    <?php foreach ($product['colors_list'] as $color): ?>
                                        <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]">
                                            <span class="text-xs text-gray-400"><?= htmlspecialchars(trim($color)) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Chưa có màu cho sản phẩm này</p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <div class="w-full mb-5">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Size</p>
                        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-lg p-[15px] mt-[10px] min-h-[107px]">
                            <div class="flex flex-wrap gap-[10px]">
                                <?php if (!empty($product['sizes_list'])) : ?>
                                    <?php foreach ($product['sizes_list'] as $size): ?>
                                        <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]">
                                            <span class="text-xs text-gray-400"><?= htmlspecialchars(trim($size)) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Chưa có size cho sản phẩm này</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>



                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Mô tả</p>
                <div class="rounded-lg mb-12 border border-neutral dark:border-dark-neutral-border p-[13px]">
                    <div class="flex items-center gap-y-4 flex-col gap-x-[27px] mb-[31px] xl:flex-row xl:gap-y-0">
                        <div class="flex items-center gap-x-[20px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-bold.svg" alt="bold icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-italicized.svg" alt="italicized icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-underlined.svg" alt="underlined icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-strikethrough.svg" alt="strikethrough icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-textcolor.svg" alt="textcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-backgroundcolor.svg" alt="backgroundcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-smile.svg" alt="smile icon"></div>
                        <div class="flex items-center gap-x-[20px]">
                            <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-paragraphformat.svg" alt="paragraphformat icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                            <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-align-left.svg" alt="align left icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                            <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-ordered-list.svg" alt="ordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                            <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-unordered-list.svg" alt="unordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="indent icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="outdent icon">
                        </div>
                        <div class="flex items-center gap-x-[20px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-image.svg" alt="insert image icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-link.svg" alt="insert link icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-file.svg" alt="insert-file icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-video.svg" alt="insert video icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-undo.svg" alt="undo icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-redo.svg" alt="redo icon"></div>
                    </div>
                    <textarea readonly class="textarea w-full p-0 text-gray-400 resize-none rounded-none bg-transparent min-h-[140px] focus:outline-none" placeholder="Nhập mô tả"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="">
                    <a href="?admin=list_products"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                        Cancel
                    </a>
                </div>
            </div>

            <div style="width: 610px;">
                <p id="galleryUploadArea" class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                    Ảnh chính
                </p>
                <img class="block border rounded-lg mb-12 mx-auto border-neutral dark:border-dark-neutral-border p-[23.8px]" src="<?= htmlspecialchars($product['image_thumbnail']) ?>" alt="product">
                <p id="galleryUploadArea" class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                    Ảnh con
                </p>
                <input type="file" readonly
                    id="galleryInput"
                    name="gallery[]"
                    accept="image/*"
                    multiple
                    data-max-files="10"
                    style="display: none;">
                <div id="galleryPreviewContainer" class="flex flex-col mb-6 gap-y-[10px]">
                </div>
                <input type="hidden" id="selectGalleryBtn">
                <input type="hidden" id="clearGalleryBtn">
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailUploadArea = document.getElementById('thumbnailUploadArea');
        const thumbnailInput = document.getElementById('thumbnailInput');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        // Khi click vào vùng upload
        thumbnailUploadArea.addEventListener('click', function() {
            thumbnailInput.click();
        });

        // Khi chọn file
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Kiểm tra loại file
                if (!file.type.startsWith('image/')) {
                    alert('Vui lòng chọn file ảnh!');
                    return;
                }

                // Kiểm tra kích thước (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File ảnh không được vượt quá 5MB!');
                    return;
                }

                // Hiển thị preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.innerHTML = `
                    <div style="position: relative; display: inline-block;">
                        <img src="${e.target.result}" 
                             style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px;"
                             alt="Preview">
                        <button type="button" 
                                onclick="clearThumbnail()" 
                                style="position: absolute; top: 5px; right: 5px; 
                                       background: rgba(220, 53, 69, 0.8); color: white; 
                                       border: none; border-radius: 50%; width: 25px; height: 25px; 
                                       font-size: 12px; cursor: pointer;">
                            ×
                        </button>
                    </div>
                `;
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag & Drop support (tùy chọn)
        thumbnailUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f0f8ff';
        });

        thumbnailUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
        });

        thumbnailUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                thumbnailInput.files = files;
                thumbnailInput.dispatchEvent(new Event('change'));
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const galleryUploadArea = document.getElementById('galleryUploadArea');
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');
        const selectGalleryBtn = document.getElementById('selectGalleryBtn');
        const clearGalleryBtn = document.getElementById('clearGalleryBtn');

        let selectedFiles = [];
        let existingImagesCount = 0;

        // LOAD ẢNH CŨ TRƯỚC - Gọi từ PHP
        function loadExistingImages(existingImages) {
            existingImagesCount = existingImages.length;
            existingImages.forEach(img => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center justify-between py-3 border pl-3 pr-3 transition-all duration-300 border-[#E8EDF2] dark:border-[#313442] rounded-[5px] gap-x-[10px] hover:shadow-xl sm:pr-8 lg:pr-3 xl:pr-8';
                itemDiv.setAttribute('data-type', 'existing');
                itemDiv.setAttribute('data-id', img.id);

                itemDiv.innerHTML = `
                <img class="hidden sm:block lg:hidden xl:block w-12 h-12 object-cover rounded" readonly
                     src="${img.image_url}" 
                     alt="existing image">
                <div class="flex flex-col flex-1 gap-y-[10px]">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-gray-1100 text-sm leading-4 dark:text-gray-dark-1100">
                            ${img.image_url.split('/').pop()}
                        </span>
                    </div>
                </div>
            `;

                galleryPreviewContainer.appendChild(itemDiv);
            });
        }

        // PREVIEW ẢNH MỚI - Thêm vào container
        function handleFileSelect(files) {
            const validFiles = [];
            const totalImages = existingImagesCount + selectedFiles.length;

            Array.from(files).forEach(file => {
                // Kiểm tra loại file
                if (!file.type.startsWith('image/')) {
                    alert(`File "${file.name}" không phải là ảnh!`);
                    return;
                }

                // Kiểm tra kích thước (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" vượt quá 5MB!`);
                    return;
                }

                // Kiểm tra tổng số ảnh (cũ + mới) <= 10
                if (totalImages + validFiles.length >= 10) {
                    alert(`Tổng số ảnh không được vượt quá 10! Hiện có ${totalImages} ảnh.`);
                    return;
                }

                // Kiểm tra trùng lặp
                const isDuplicate = selectedFiles.some(selectedFile =>
                    selectedFile.name === file.name && selectedFile.size === file.size
                );

                if (!isDuplicate) {
                    validFiles.push(file);
                }
            });

            if (validFiles.length > 0) {
                selectedFiles = [...selectedFiles, ...validFiles];
                addNewImagesToPreview(validFiles);
                updateFileInput();
            }
        }

        function addNewImagesToPreview(files) {
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center justify-between py-3 border pl-3 pr-3 transition-all duration-300 border-green-300 dark:border-green-600 rounded-[5px] gap-x-[10px] hover:shadow-xl sm:pr-8 lg:pr-3 xl:pr-8';
                    itemDiv.setAttribute('data-type', 'new');
                    itemDiv.setAttribute('data-index', selectedFiles.length - files.length + index);

                    itemDiv.innerHTML = `
                    <img class="hidden sm:block lg:hidden xl:block w-12 h-12 object-cover rounded" 
                         src="${e.target.result}" 
                         alt="new image">
                    <div class="flex flex-col flex-1 gap-y-[10px]">
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-gray-1100 text-sm leading-4 dark:text-gray-dark-1100">
                                ${file.name}
                            </span>
                            <span class="text-green-500 text-xs font-semibold">
                                MỚI - ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </span>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="removeNewImage(this)"
                            class="hover:opacity-70 transition-opacity">
                        <img src="./assets/admin/assets/images/icons/icon-close-circle.svg" alt="remove">
                    </button>
                `;

                    galleryPreviewContainer.appendChild(itemDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => {
                dt.items.add(file);
            });
            galleryInput.files = dt.files;
        }

        function clearAllGallery() {
            // Chỉ xóa ảnh mới, giữ lại ảnh cũ
            const newImageElements = galleryPreviewContainer.querySelectorAll('[data-type="new"]');
            newImageElements.forEach(element => element.remove());

            selectedFiles = [];
            galleryInput.value = '';
        }

        // Event listeners
        galleryUploadArea.addEventListener('click', function() {
            galleryInput.click();
        });

        selectGalleryBtn.addEventListener('click', function() {
            galleryInput.click();
        });

        galleryInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files);
        });

        clearGalleryBtn.addEventListener('click', function() {
            clearAllGallery();
        });

        // Drag & Drop
        galleryUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f0f8ff';
            this.style.borderColor = '#007bff';
        });

        galleryUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
            this.style.borderColor = '';
        });

        galleryUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
            this.style.borderColor = '';
            handleFileSelect(e.dataTransfer.files);
        });

        // Global functions
        window.removeNewImage = function(button) {
            const element = button.closest('[data-type="new"]');
            const index = parseInt(element.getAttribute('data-index'));

            // Xóa khỏi mảng selectedFiles
            selectedFiles.splice(index, 1);

            // Xóa element
            element.remove();

            // Update file input
            updateFileInput();

            // Update index cho các element còn lại
            updateNewImageIndexes();
        };

        window.deleteExistingImage = function(imageId, button) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                // Gửi AJAX hoặc redirect để xóa ảnh trên server
                fetch(`?admin=delete_product_gallery&id=${imageId}`, {
                    method: 'GET'
                }).then(response => {
                    if (response.ok) {
                        // Xóa element khỏi DOM
                        button.closest('[data-type="existing"]').remove();
                        existingImagesCount--;

                        // Thông báo thành công
                        alert('Đã xóa ảnh thành công!');
                    } else {
                        alert('Lỗi khi xóa ảnh!');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Lỗi khi xóa ảnh!');
                });
            }
        };

        function updateNewImageIndexes() {
            const newElements = galleryPreviewContainer.querySelectorAll('[data-type="new"]');
            newElements.forEach((element, index) => {
                element.setAttribute('data-index', index);
            });
        }

        // LOAD ẢNH CŨ KHI TRANG LOAD - Gọi từ PHP
        <?php if (!empty($galleryImages)): ?>
            loadExistingImages(<?= json_encode($galleryImages) ?>);
        <?php endif; ?>
    });
</script>