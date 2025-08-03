<div class="">
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Photo gallery</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
        <div class="flex items-center gap-x-1">
            <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
            <a class="capitalize" href="index.php?admin=dashboard">home</a>
        </div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Photo gallery</span>
    </div>

    <section>
        <div class="flex justify-between gap-6 flex-col xl:flex-row">
            <div class="flex flex-col gap-[10px] lg:gap-[27px] xl:w-[25%] md:flex-row xl:flex-col">
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl pb-5 flex-1 px-[28px] pt-[35px]">

                    <!-- Form thêm ảnh -->
                    <form action="?admin=product_images&action=store" method="POST" enctype="multipart/form-data" id="addImageForm">
                        <!-- Hidden field để xác định edit mode -->
                        <input type="hidden" id="editMode" name="edit_mode" value="false">
                        <input type="hidden" id="editImageId" name="edit_image_id" value="">
                        <!-- Chọn sản phẩm -->
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Thêm ảnh cho sản phẩm</p>
                        <select name="product_id" id="product_id"
                            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-4"
                            onchange="loadVariants(this.value)" required>
                            <option value="">Chọn sản phẩm</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Chọn variant -->
                        <select name="variant_id" id="variant_id"
                            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-12"
                            required disabled>
                            <option value="">Vui lòng chọn sản phẩm trước</option>
                        </select>

                        <!-- Upload ảnh -->
                        <div class="flex flex-col">
                            <div>
                                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Thêm hình ảnh</p>

                                <!-- Drop zone -->
                                <div id="drop-zone"
                                    class="border-dashed border-2 text-center mb-4 border-neutral py-[26px] dark:border-dark-neutral-border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                    onclick="document.getElementById('images').click()">
                                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Hãy chọn hình ảnh của bạn</p>
                                    <p class="leading-6 text-gray-400 text-[13px]">Hãy chọn tệp JPG, PNG, GIF và WEBP</p>
                                </div>

                                <!-- Input file ẩn -->
                                <input type="file"
                                    id="images"
                                    name="images[]"
                                    multiple
                                    accept="image/jpeg,image/png,image/gif,image/webp"
                                    class="hidden"
                                    onchange="handleFileSelect(event)">

                                <!-- Preview ảnh đã chọn -->
                                <div id="preview-container" class="flex flex-col mb-12 gap-y-[10px]">
                                    <!-- Ảnh preview sẽ hiển thị ở đây -->
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center gap-x-4 flex-wrap gap-y-4">
                                    <button type="submit"
                                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                                        Thêm ảnh
                                    </button>
                                    <button type="button"
                                        onclick="clearForm()"
                                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-x-scroll scrollbar-hide flex-1 px-[28px] pt-[33px] pb-[23px]">
                <div class="flex items-center justify-between flex-col gap-4 mb-[23px] lg:flex-row">
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Thư viện ảnh</p>
                </div>
                <div class="w-full bg-neutral h-[1px] mb-[10px] dark:bg-dark-neutral-border"></div>
                <form method="POST" action="?admin=product_images&action=bulkDelete">
                    <table class="w-full min-w-[800px] lg:min-w-fit">
                        <thead>
                            <tr class="text-gray-1100 dark:text-gray-dark-1100">
                                <td></td>
                                <td>Tên sản phẩm</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($images as $image) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            name="image_ids[]"
                                            value="<?= $image['id'] ?>"
                                            onchange="toggleBulkDeleteButton()"
                                            class="checkbox checkbox-primary rounded border-2 w-[18px] h-[18px] translate-y-[3px]">
                                    </td>
                                    <td class="py-[10px]">
                                        <div class="flex items-center gap-[13px]"><img class="mr-2" src="<?= htmlspecialchars($image['image_url']) ?>" alt="pdf icon" width="120">
                                            <div class="flex flex-col gap-y-[5px]">
                                                <h4 class="font-semibold leading-4 text-gray-1100 text-[14px] dark:text-gray-dark-1100"><?= $image['product_name'] ?></h4>
                                                <time class="text-xs text-gray-400 dark:text-gray-dark-400">
                                                    <?= htmlspecialchars($image['variant_attributes']) ?>
                                                </time>
                                                <time class="text-xs text-gray-400 dark:text-gray-dark-400">
                                                    on <?= date('d/m/Y \\a\\t g:i a', strtotime($image['created_at'])) ?>
                                                </time>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <label for="edit-image-modal"
                                            class="edit-btn hover:scale-110 transition-transform duration-200 cursor-pointer btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]"
                                            onclick="openEditModal(
                                                <?= $image['id'] ?>, 
                                                '<?= addslashes($image['product_name']) ?>', 
                                                <?= $image['variant_id'] ?>, 
                                                <?= $image['product_id'] ?>,
                                                '<?= $image['image_url'] ?>',
                                                '<?= addslashes($image['variant_attributes'] ?? '') ?>'
                                            )"
                                            title="Sửa ảnh này">
                                            Sửa
                                        </label>
                                        <a href="?admin=product_images&action=delete&id=<?= $image['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa ảnh này không?')"
                                            class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[14px]">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit"
                        id="bulkDeleteBtn"
                        class="btn bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        style="display: none;"
                        onclick="return confirm('Bạn có chắc muốn xóa những ảnh đã chọn không?')">
                        Xóa đã chọn
                    </button>
                </form>
                <!-- PHÂN TRANG - DESIGN ĐẸP -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="flex items-center gap-x-10 mt-6">
                        <!-- Page buttons -->
                        <div>
                            <?php
                            $current_page = $pagination['current_page'];
                            $total_pages = $pagination['total_pages'];

                            // Hiển thị tối đa 5 trang
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $start_page + 4);

                            // Adjust start_page if we're near the end
                            if ($end_page - $start_page < 4) {
                                $start_page = max(1, $end_page - 4);
                            }

                            for ($i = $start_page; $i <= $end_page; $i++):
                                $page_url = '?admin=product_images&p=' . $i; // Dùng 'p' thay vì 'page'
                            ?>
                                <?php if ($i == $current_page): ?>
                                    <!-- Current page - Active state -->
                                    <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-color-brands font-semibold py-[11px] px-[18px] hover:bg-color-brands">
                                        <?= $i ?>
                                    </button>
                                <?php else: ?>
                                    <!-- Other pages - Inactive state -->
                                    <a href="<?= $page_url ?>"
                                        class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Next button -->
                        <?php if ($pagination['has_next']): ?>
                            <a class="items-center justify-center border rounded-lg border-neutral flex gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                href="?admin=product_images&p=<?= $pagination['next_page'] ?>">
                                <span class="text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400">Next</span>
                                <img src="./assets/admin/assets/images/icons/icon-arrow-right-long.svg" alt="arrow right icon">
                            </a>
                        <?php else: ?>
                            <!-- Next button disabled -->
                            <div class="items-center justify-center border rounded-lg border-neutral flex gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border opacity-50 cursor-not-allowed">
                                <span class="text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400">Next</span>
                                <img src="./assets/admin/assets/images/icons/icon-arrow-right-long.svg" alt="arrow right icon">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thông tin tổng quan -->
                    <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Trang <?= $pagination['current_page'] ?> / <?= $pagination['total_pages'] ?> •
                        <?= $pagination['total_images'] ?> ảnh
                    </div>
                <?php endif; ?>


            </div>
        </div>
    </section>
    <!-- Modal sửa ảnh-->
    <input type="checkbox" id="edit-image-modal" class="modal-toggle">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide w-full dark:bg-dark-neutral-bg pt-[53px] max-w-[794px]">
            <!-- Nút đóng modal -->
            <label class="absolute right-2 top-2 cursor-pointer" for="edit-image-modal">
                <img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button">
            </label>

            <div class="flex items-center justify-center flex-col">
                <!-- Tiêu đề modal -->
                <h6 class="text-header-6 font-semibold text-gray-500 text-center dark:text-gray-dark-500 mb-[50px]">
                    ✏️ Chỉnh sửa ảnh sản phẩm
                </h6>

                <!-- Form chỉnh sửa -->
                <form action="?admin=product_images&action=update" method="POST" enctype="multipart/form-data" id="editImageForm" class="w-full flex flex-col max-w-[531px] gap-[30px] mb-[60px] lg:mb-[166px]">

                    <!-- Hidden fields -->
                    <input type="hidden" name="edit_mode" value="true">
                    <input type="hidden" id="modal_edit_image_id" name="edit_image_id" value="">
                    <!-- Hiển thị ảnh hiện tại -->
                    <div id="modal_current_image_section" class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                            📷 Ảnh hiện tại
                        </p>
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 dark:bg-gray-800 flex items-center gap-4">
                            <!-- Ảnh giữ nguyên -->
                            <img id="modal_current_image" src="" alt="Current image" class="w-20 h-20 object-cover rounded border flex-shrink-0">

                            <!-- Container cho text - FIXED -->
                            <div class="min-w-0 flex-1 overflow-hidden">
                                <!-- Tên sản phẩm - ẨN TEXT TRÀN -->
                                <p id="modal_current_product_name"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300 
                      truncate whitespace-nowrap overflow-hidden text-ellipsis mb-1"
                                    title="">
                                </p>

                                <!-- Thông tin variant - ẨN TEXT TRÀN -->
                                <p id="modal_current_variant_info"
                                    class="text-xs text-blue-600 dark:text-blue-400 
                      truncate whitespace-nowrap overflow-hidden text-ellipsis mb-1"
                                    title="">
                                </p>

                                <!-- Tên file ảnh - ẨN TEXT TRÀN -->
                                <p id="modal_current_image_name"
                                    class="text-xs text-gray-500 
                      truncate whitespace-nowrap overflow-hidden text-ellipsis"
                                    title="">
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Upload ảnh mới -->
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                            🔄 Chọn ảnh mới (để trống nếu không thay đổi)
                        </p>
                        <div id="modal_drop_zone" class="border-dashed border-2 text-center border-neutral mx-auto cursor-pointer py-[26px] dark:border-dark-neutral-border w-full max-w-[724px]"
                            onclick="document.getElementById('modal_image_input').click()">
                            <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                            <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Drop your new image here, or browse</p>
                            <p class="leading-6 text-gray-400 text-[13px]">JPG, PNG, GIF và WEBP files are allowed</p>
                        </div>

                        <!-- Input file ẩn -->
                        <input type="file"
                            id="modal_image_input"
                            name="images"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            class="hidden"
                            onchange="handleModalFileSelect(event)">

                        <!-- Preview ảnh mới -->
                        <div id="modal_preview_container" class="mt-4">
                            <!-- Preview sẽ hiển thị ở đây -->
                        </div>
                    </div>

                    <!-- Chọn sản phẩm -->
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                            Sản phẩm
                        </p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                            <select id="modal_product_id" name="product_id"
                                class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400"
                                onchange="loadModalVariants(this.value)" required>
                                <option value="">Chọn sản phẩm</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['id'] ?>">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Chọn variant -->
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                            Phiên bản
                        </p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                            <select id="modal_variant_id" name="variant_id"
                                class="select w-full bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400"
                                required disabled>
                                <option value="">Vui lòng chọn sản phẩm trước</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-center gap-x-4">
                        <button type="submit"
                            class="btn bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">
                            💾 Cập nhật ảnh
                        </button>
                        <label for="edit-image-modal"
                            class="btn bg-gray-400 hover:bg-gray-500 text-white px-8 py-3 rounded-lg font-medium cursor-pointer">
                            ❌ Hủy
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript  -->
<script>
    // Load variants khi chọn sản phẩm
    function loadVariants(productId) {
        const variantSelect = document.getElementById('variant_id');

        if (!productId) {
            variantSelect.innerHTML = '<option value="">Vui lòng chọn sản phẩm trước</option>';
            variantSelect.disabled = true;
            return;
        }

        // Disable select và hiển thị loading
        variantSelect.disabled = true;
        variantSelect.innerHTML = '<option value="">Đang tải...</option>';

        // Gửi AJAX request
        fetch('?admin=product_images&action=getVariantsByProduct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let options = '<option value="">-- Chọn phiên bản --</option>';
                    data.variants.forEach(variant => {
                        const price = variant.sale_price ?
                            `${Number(variant.sale_price).toLocaleString()}đ (Sale)` :
                            `${Number(variant.price).toLocaleString()}đ`;
                        const attributes = variant.attributes ? ` - ${variant.attributes}` : '';
                        options += `<option value="${variant.id}">ID: ${variant.id} - ${price}${attributes}</option>`;
                    });
                    variantSelect.innerHTML = options;
                    variantSelect.disabled = false;
                } else {
                    variantSelect.innerHTML = '<option value="">Không có phiên bản nào</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                variantSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
            });
    }

    // Xử lý drag & drop
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('images');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = e.dataTransfer.files;
        fileInput.files = files;
        handleFileSelect({
            target: {
                files: files
            }
        });
    });

    // Xử lý khi chọn file
    function handleFileSelect(event) {
        const files = event.target.files;
        const container = document.getElementById('preview-container');
        container.innerHTML = '';

        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between py-3 border pl-3 pr-3 transition-all duration-300 border-[#E8EDF2] dark:border-[#313442] rounded-[5px] gap-x-[10px] hover:shadow-xl sm:pr-8 lg:pr-3 xl:pr-8';
                        div.innerHTML = `
                        <div class="flex items-center gap-x-3">
                            <img class="w-12 h-12 object-cover rounded" src="${e.target.result}" alt="preview">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" style="max-width: 120px;">${file.name}</p>
                                <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <button type="button" 
                                class="delete-btn hover:scale-110 transition-transform duration-200" 
                                onclick="removePreview(this, ${index})">
                            <img src="./assets/admin/assets/images/icons/icon-close-circle.svg" 
                                 alt="close circle icon" 
                                 class="w-6 h-6 hover:opacity-70">
                        </button>
                    `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Xóa preview ảnh
    function removePreview(button, index) {
        button.closest('.flex.items-center').remove();

        // Cập nhật file input (loại bỏ file đã xóa)
        const dt = new DataTransfer();
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        fileInput.files = dt.files;
    }

    // Clear form
    function clearForm() {
        document.getElementById('addImageForm').reset();
        document.getElementById('variant_id').disabled = true;
        document.getElementById('variant_id').innerHTML = '<option value="">Vui lòng chọn sản phẩm trước</option>';
        document.getElementById('preview-container').innerHTML = '';
    }

    // Hiển thị thông báo
    <?php if (isset($_SESSION['success'])): ?>
        alert('<?= htmlspecialchars($_SESSION['success']) ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        alert('Lỗi: <?= htmlspecialchars($_SESSION['error']) ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    function toggleBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('input[name="image_ids[]"]:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        if (checkedBoxes.length >= 2) {
            // Hiện với hiệu ứng fade
            bulkDeleteBtn.style.display = 'block';
            bulkDeleteBtn.style.opacity = '0';
            setTimeout(() => {
                bulkDeleteBtn.style.transition = 'opacity 0.3s';
                bulkDeleteBtn.style.opacity = '1';
            }, 10);
        } else {
            // Ẩn với hiệu ứng fade
            bulkDeleteBtn.style.transition = 'opacity 0.3s';
            bulkDeleteBtn.style.opacity = '0';
            setTimeout(() => {
                bulkDeleteBtn.style.display = 'none';
            }, 300);
        }
    }

    // Sửa
    /**
     * Mở modal chỉnh sửa và điền thông tin
     */
    function openEditModal(imageId, productName, variantId, productId, imageUrl, variantAttributes) {
        console.log('Opening edit modal for image:', imageId);

        // Set hidden fields
        document.getElementById('modal_edit_image_id').value = imageId;

        // Hiển thị thông tin ảnh hiện tại
        document.getElementById('modal_current_image').src = imageUrl;
        document.getElementById('modal_current_product_name').textContent = productName;
        document.getElementById('modal_current_variant_info').textContent = variantAttributes || `Variant #${variantId}`;

        const fileName = imageUrl.split('/').pop();
        document.getElementById('modal_current_image_name').textContent = fileName;

        // Set product dropdown
        document.getElementById('modal_product_id').value = productId;

        // Load variants và set variant
        loadModalVariants(productId).then(() => {
            document.getElementById('modal_variant_id').value = variantId;
        });

        // Clear preview
        document.getElementById('modal_preview_container').innerHTML = '';
        document.getElementById('modal_image_input').value = '';

        console.log('Edit modal opened successfully');
    }

    /**
     * Load variants cho modal
     */
    function loadModalVariants(productId) {
        const variantSelect = document.getElementById('modal_variant_id');

        if (!productId) {
            variantSelect.innerHTML = '<option value="">Vui lòng chọn sản phẩm trước</option>';
            variantSelect.disabled = true;
            return Promise.resolve();
        }

        variantSelect.disabled = true;
        variantSelect.innerHTML = '<option value="">⏳ Đang tải...</option>';

        return fetch('?admin=product_images&action=getVariantsByProduct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.variants.length > 0) {
                    let options = '<option value="">-- Chọn phiên bản --</option>';
                    data.variants.forEach(variant => {
                        const price = variant.sale_price ?
                            `${Number(variant.sale_price).toLocaleString()}đ (Sale)` :
                            `${Number(variant.price).toLocaleString()}đ`;
                        const attributes = variant.attributes ? ` - ${variant.attributes}` : '';
                        options += `<option value="${variant.id}">ID: ${variant.id} - ${price}${attributes}</option>`;
                    });
                    variantSelect.innerHTML = options;
                    variantSelect.disabled = false;
                } else {
                    variantSelect.innerHTML = '<option value="">❌ Không có phiên bản nào</option>';
                }
            })
            .catch(error => {
                console.error('Error loading variants:', error);
                variantSelect.innerHTML = '<option value="">❌ Lỗi khi tải dữ liệu</option>';
            });
    }

    /**
     * Xử lý file select trong modal
     */
    function handleModalFileSelect(event) {
        const file = event.target.files[0];
        const container = document.getElementById('modal_preview_container');

        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    container.innerHTML = `
                    <div class="border border-green-300 rounded-lg p-4 bg-green-50 dark:bg-green-900/20">
                        <p class="text-sm font-semibold text-white mb-2">🎉 Ảnh mới đã chọn:</p>
                        <div class="flex items-center gap-3">
                            <img src="${e.target.result}" alt="New image preview" class="w-16 h-16 object-cover rounded border">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${file.name}</p>
                                <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                    </div>
                `;
                };
                reader.readAsDataURL(file);
            }
        } else {
            container.innerHTML = '';
        }
    }

    /**
     * Drag & Drop cho modal
     */
    document.addEventListener('DOMContentLoaded', function() {
        const modalDropZone = document.getElementById('modal_drop_zone');
        const modalFileInput = document.getElementById('modal_image_input');

        if (modalDropZone) {
            modalDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                modalDropZone.classList.add('border-blue-400', 'bg-blue-50');
            });

            modalDropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                modalDropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });

            modalDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                modalDropZone.classList.remove('border-blue-400', 'bg-blue-50');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    modalFileInput.files = files;
                    handleModalFileSelect({
                        target: {
                            files: files
                        }
                    });
                }
            });
        }
    });
</script>