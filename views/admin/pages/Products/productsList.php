<?php
$products = $data['products'] ?? [];
$totalProducts = $data['totalProducts'] ?? 0;

?>
<script>
    <?php if (isset($_SESSION['success_products'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '<?= htmlspecialchars($_SESSION['success_products']) ?>',
            timer: 3000
        });
        <?php unset($_SESSION['success_products']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_products'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            html: '<?= str_replace("\n", "<br>", htmlspecialchars($_SESSION['error_products'])) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error_products']); ?>
    <?php endif; ?>
</script>
<div>
    <div class="flex items-center justify-between mb-[19px]">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">All Products</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xs text-gray-500 gap-x-[11px]">
                    <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
                        <a href="index.php?admin=dashboard"><span class="capitalize">home</span></a>
                    </div>
                    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
                    <span class="capitalize text-color-brands">All Products</span>
                </div>
            </div>
        </div>
        <div class="flex">
            <a href="?admin=add_products" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]">
                <img src="./assets/admin/assets/images/icons/icon-add.svg" alt="add icon">
                <span class="text-white font-semibold text-[14px] leading-[21px]">Thêm sản phẩm</span>
            </a>
        </div>
    </div>
    <div class="border p-6 bg-neutral-bg rounded-2xl border-neutral pb-0 overflow-x-scroll scrollbar-hide dark:bg-dark-neutral-bg dark:border-dark-neutral-border mb-[52px] xl:overflow-x-hidden">
        <div class="text-base leading-5 text-gray-1100 font-semibold mb-6 dark:text-gray-dark-1100">
            Tổng số sản phẩm: <strong><?= $totalProducts ?></strong>
        </div>
        <table class="w-full min-w-[900px]">
            <tr>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Image</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Name</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Category</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Price</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">QTY</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Color</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex items-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Size</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
                <th class="border-b border-neutral pb-[17px] dark:border-dark-neutral-border">
                    <div class="flex text-center justify-center gap-x-[10px]"><span class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500">Action</span><img src="./assets/admin/assets/images/icons/icon-arrow-up-down.svg" alt="arrow up down icon"></div>
                </th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <img class="img-thumbnail product-thumbnail border border-neutral rounded-lg dark:border-dark-neutral-border w-[150px]"
                            src="<?= $product['thumbnail_display'] ?>">
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div class="flex flex-col gap-y-1 max-w-[250px]">
                            <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100"><?= $product['name'] ?></p>
                            <?php if ($product['variant_count'] > 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">Có <?= $product['variant_count'] ?> biến thể</p>
                            <?php endif; ?>

                            <?php if ($product['variant_count'] == 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500">Chưa có biến thể cho sản phẩm này</p>
                            <?php endif; ?>

                            <p class="text-xs text-gray-500 dark:text-gray-dark-500">
                                <?php if (!empty($product['description'])): ?>
                                    <small class="text-muted d-block mt-1">
                                        <?= htmlspecialchars(substr($product['description'], 0, 60)) ?>
                                        <?= strlen($product['description']) > 60 ? '...' : '' ?>
                                    </small>
                                <?php endif; ?>
                            </p>
                        </div>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500"><?= $product['category_name'] ?? 'Chưa phân loại' ?></p>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100"><?= $product['price_display'] ?></p>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <?php
                        $stockClass = 'badge-danger';
                        $stockText = 'Hết hàng';

                        if ($product['total_quantity'] > 50) {
                            $stockClass = 'badge-success';
                            $stockText = 'Còn nhiều';
                        } elseif ($product['total_quantity'] > 10) {
                            $stockClass = 'badge-warning';
                            $stockText = 'Còn ít';
                        } elseif ($product['total_quantity'] > 0) {
                            $stockClass = 'badge-danger';
                            $stockText = 'Sắp hết';
                        }
                        ?>
                        <div>
                            <span class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100 badge <?= $stockClass ?> badge-lg">
                                <?= $product['total_quantity'] ?>
                            </span>
                            <br>
                            <small class="<?= str_replace('badge-', 'text-', $stockClass) ?>">
                                <?= $stockText ?>
                            </small>
                        </div>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500"></p>
                        <?php if (!empty($product['color_list'])): ?>
                            <div class="d-flex flex-wrap">
                                <?php foreach ($product['color_list'] as $color): ?>
                                    <p class="text-sm leading-4 text-gray-1100 dark:text-gray-dark-1100">
                                        <?= htmlspecialchars(trim($color)) ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm leading-4 text-gray-1100 dark:text-gray-dark-1100">Chưa có</p>
                        <?php endif; ?>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <?php if (!empty($product['size_list'])): ?>
                            <div class="d-flex flex-wrap">
                                <?php foreach ($product['size_list'] as $size): ?>
                                    <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">
                                        <?= htmlspecialchars(trim($size)) ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">Chưa có</p>
                        <?php endif; ?>
                    </td>
                    <td class="border-b border-neutral py-[26px] dark:border-dark-neutral-border">
                        <div class="flex flex-col items-start items-center gap-y-2">
                            <a href="?admin=show_product&id=<?= $product['id'] ?>"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[9px]">
                                Xem
                            </a>
                            <a href="?admin=edit_product&id=<?= $product['id'] ?>"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[9px]">Sửa</a>
                            <a href="#"
                                onclick="deleteProduct(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')"
                                class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[9px]">
                                Xóa
                            </a>
                            <script>
                                function deleteProduct(productId, productName) {
                                    Swal.fire({
                                        title: 'Xác nhận xóa sản phẩm?',
                                        html: `Bạn có chắc chắn muốn xóa sản phẩm:<br><strong>"${productName}"</strong>?`,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#E23738',
                                        cancelButtonColor: '#6c757d',
                                        confirmButtonText: '<i class="fas fa-trash"></i> Xóa',
                                        cancelButtonText: '<i class="fas fa-times"></i> Hủy',
                                        reverseButtons: true,
                                        customClass: {
                                            confirmButton: 'btn btn-danger mx-2',
                                            cancelButton: 'btn btn-secondary mx-2'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Hiển thị loading
                                            Swal.fire({
                                                title: 'Đang xử lý...',
                                                text: 'Vui lòng đợi',
                                                icon: 'info',
                                                allowOutsideClick: false,
                                                showConfirmButton: false,
                                                willOpen: () => {
                                                    Swal.showLoading();
                                                }
                                            });

                                            // Redirect để xóa
                                            window.location.href = `?admin=delete_product&id=${productId}`;
                                        }
                                    });
                                }
                            </script>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php $pg = $data['pagination'];
    if ($pg['total'] > 1): ?>
        <div class="flex items-center gap-x-4 mt-8">
            <!-- Prev -->
            <?php if ($pg['hasPrev']): ?>
                <a href="?admin=list_products&p=<?= $pg['prev'] ?>"
                    class="btn">Prev</a>
            <?php else: ?>
                <span class="btn opacity-50 cursor-not-allowed">Prev</span>
            <?php endif; ?>

            <!-- Page numbers (tối đa 5) -->
            <?php
            $start = max(1, $pg['current'] - 2);
            $end   = min($pg['total'], $start + 4);
            if ($end - $start < 4) $start = max(1, $end - 4);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i == $pg['current']): ?>
                    <span class="btn bg-color-brands text-white"><?= $i ?></span>
                <?php else: ?>
                    <a href="?admin=list_products&p=<?= $i ?>" class="btn"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($pg['hasNext']): ?>
                <a href="?admin=list_products&p=<?= $pg['next'] ?>"
                    class="btn">Next</a>
            <?php else: ?>
                <span class="btn opacity-50 cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>



</div>

<style>
    .product-thumbnail {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
    }

    .badge-outline-primary {
        color: #007bff;
        border: 1px solid #007bff;
        background-color: transparent;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5em 0.75em;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group-vertical .btn {
        margin-bottom: 2px;
    }

    .badge {
        font-size: 0.75em;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>