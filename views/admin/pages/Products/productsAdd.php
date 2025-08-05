<div>
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Thêm sản phẩm</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[17px]">
        <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon"><a class="capitalize" href="?admin=dashboard">home</a></div><img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon"><span class="capitalize text-color-brands">Thêm sản phẩm</span>
    </div>
    <form action="?admin=store_products" method="POST" enctype="multipart/form-data" >
        <div class="flex gap-x-12 border rounded-2xl justify-between flex-col gap-y-12 bg-white border-neutral pt-[50px] pb-[132px] px-[39px] dark:border-dark-neutral-border lg:flex-row lg:gap-y-0 dark:bg-[#1F2128]">
            <div class="lg:max-w-[610px]">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Tến sản phẩm</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="name"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="text" placeholder="Nhập tên sản phẩm">
                </div>

                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Giá gốc</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px] mb-12">
                    <input name="price" id="price"
                        class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit"
                        type="number" placeholder="Nhập giá gốc">
                </div>

                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Ảnh chính</p>
                <div class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border"
                    id="thumbnailUploadArea" style="cursor: pointer;">
                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Hãy chọn ảnh từ thư viện của bạn</p>
                    <p class="leading-6 text-gray-400 text-[13px]">Hỗ trợ các file như: JPG,PNG & GIF</p>

                    <!-- Input file ẩn -->
                    <input type="file"
                        id="thumbnailInput"
                        name="thumbnail"
                        accept="image/*"
                        style="display: none;">

                    <!-- Preview area -->
                    <div id="thumbnailPreview" class="mt-2">
                    </div>
                </div>

                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Danh mục</p>
                <select name="category_id" class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-12">
                    <option disabled="" selected="">--Chọn danh mục--</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                            <?php if ($category['parent_name']): ?>
                                (<?= htmlspecialchars($category['parent_name']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                    <textarea class="textarea w-full p-0 text-gray-400 resize-none rounded-none bg-transparent min-h-[140px] focus:outline-none" placeholder="Nhập mô tả"></textarea>
                </div>
                <div class="">
                    <button type="submit"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                        Thêm sản phẩm
                    </button>
                    <a href="?admin=list_products" 
                    class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                        Cancel
                    </a>
                </div>
            </div>
            <div style="width: 610px;">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Ảnh con</p>
                <div id="galleryUploadArea" style="cursor: pointer;"
                    class="border-dashed border-2 text-center mb-12 border-neutral py-[26px] dark:border-dark-neutral-border">
                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Hãy chọn ảnh từ thư viện của bạn</p>
                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Tối đa chỉ được 10 ảnh</p>
                    <p class="leading-6 text-gray-400 text-[13px]">Hỗ trợ các file như: JPG,PNG & GIF</p>
                    <input type="file"
                        id="galleryInput"
                        name="gallery[]"
                        accept="image/*"
                        multiple
                        data-max-files="10"
                        onchange="validateFileCount(this)"
                        style="display: none;">

                    <script>
                        function validateFileCount(input) {
                            const maxFiles = parseInt(input.dataset.maxFiles) || 10;

                            if (input.files.length > maxFiles) {
                                alert(`Bạn chỉ được chọn tối đa ${maxFiles} ảnh!`);
                                input.value = '';
                                return false;
                            }

                            // Tiếp tục xử lý preview...
                            previewGallery(input);
                            return true;
                        }
                    </script>

                </div>

                <div id="galleryPreviewContainer" class="flex flex-col mb-12 gap-y-[10px]">

                </div>
                <div class="flex items-center gap-x-4 flex-wrap gap-y-4">
                    <button type="button"
                        id="selectGalleryBtn"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                        Chọn ảnh
                    </button>
                    <button type="button"
                        id="clearGalleryBtn"
                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[14px]">
                        Xóa tất cả
                    </button>
                </div>
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

    // Function để xóa ảnh đã chọn
    function clearThumbnail() {
        document.getElementById('thumbnailInput').value = '';
        document.getElementById('thumbnailPreview').innerHTML = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const galleryUploadArea = document.getElementById('galleryUploadArea');
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');
        const selectGalleryBtn = document.getElementById('selectGalleryBtn');
        const clearGalleryBtn = document.getElementById('clearGalleryBtn');

        let selectedFiles = [];

        // Khi click vào vùng upload hoặc nút "Chọn ảnh"
        galleryUploadArea.addEventListener('click', function() {
            galleryInput.click();
        });

        selectGalleryBtn.addEventListener('click', function() {
            galleryInput.click();
        });

        // Khi chọn files
        galleryInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files);
        });

        // Nút xóa tất cả
        clearGalleryBtn.addEventListener('click', function() {
            clearAllGallery();
        });

        // Drag & Drop support
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

            const files = e.dataTransfer.files;
            handleFileSelect(files);
        });

        function handleFileSelect(files) {
            const validFiles = [];

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
                updateGalleryPreview();
                updateFileInput();
            }
        }

        function updateGalleryPreview() {
            galleryPreviewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center justify-between py-3 border pl-3 pr-3 transition-all duration-300 border-[#E8EDF2] dark:border-[#313442] rounded-[5px] gap-x-[10px] hover:shadow-xl sm:pr-8 lg:pr-3 xl:pr-8';

                    itemDiv.innerHTML = `
                    <img class="hidden sm:block lg:hidden xl:block w-12 h-12 object-cover rounded" 
                         src="${e.target.result}" 
                         alt="product">
                    <div class="flex flex-col flex-1 gap-y-[10px]">
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-gray-1100 text-sm leading-4 dark:text-gray-dark-1100">
                                ${file.name}
                            </span>
                            <span class="text-gray-500 text-xs">
                                ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </span>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="removeGalleryItem(${index})"
                            class="hover:opacity-70 transition-opacity">
                        <img src="./assets/admin/assets/images/icons/icon-close-circle.svg" alt="close circle icon">
                    </button>
                `;

                    galleryPreviewContainer.appendChild(itemDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            // Tạo DataTransfer object để cập nhật input file
            const dt = new DataTransfer();
            selectedFiles.forEach(file => {
                dt.items.add(file);
            });
            galleryInput.files = dt.files;
        }

        function clearAllGallery() {
            selectedFiles = [];
            galleryPreviewContainer.innerHTML = '';
            galleryInput.value = '';
        }

        // Global functions
        window.removeGalleryItem = function(index) {
            selectedFiles.splice(index, 1);
            updateGalleryPreview();
            updateFileInput();
        };
    });
</script>