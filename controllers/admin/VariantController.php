<?php

require_once 'models/admin/VariantModel.php';

class VariantController
{
    /** @var VariantModel */
    private $variantModel;

    public function __construct()
    {
        $this->variantModel = new VariantModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ====================== LIST ====================== */

    // GET ?admin=list_variant&product_id=&min_quantity=&page=
    // controllers/admin/VariantController.php
    public function index()
    {
        $filters = [
            'product_id'   => $this->input('product_id'),
            'min_quantity' => $this->input('min_quantity'),
        ];
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = 20;
        $sort    = $this->input('sort', 'product_asc'); // mặc định gom theo tên sản phẩm

        $variants = $this->variantModel->listWithDetails($filters, $page, $perPage, $sort);

        $content  = getContentPath('Variant', 'variantList');
        view('admin/master', [
            'content'        => $content,
            'variants'       => $variants,
            'totalVariants'  => count($variants),
            'filters'        => $filters,
            'page'           => $page,
            'perPage'        => $perPage,
            'sort'           => $sort,
        ]);
    }

    /* ====================== CREATE ====================== */

    // GET form
    // GET ?admin=variant#create&product_id=
    public function create()
    {
        // Lấy danh sách sản phẩm, màu, size
        $products = $this->variantModel->getAllProducts();
        $colors   = $this->variantModel->getAllColors();
        $sizes    = $this->variantModel->getAllSizes();

        // Lấy map ảnh cho từng sản phẩm
        $productIds      = array_column($products, 'id');
        $imagesByProduct = $this->variantModel->getImagesGroupedByProduct($productIds);

        $content = getContentPath('Variant', 'variantAdd');
        view('admin/master', [
            'content'         => $content,
            'mode'            => 'create',
            'products'        => $products,
            'colors'          => $colors,
            'sizes'           => $sizes,
            'imagesByProduct' => $imagesByProduct,
            'variant'         => [
                'product_id' => '',
                'price'      => '',
                'sale_price' => '',
                'quantity'   => 0,
                'image_url'  => '',
            ],
            'value_ids'       => [],
        ]);
    }

    // POST lưu
    // POST ?admin=variant#store
    public function store()
    {
        $this->ensurePost();

        $productId    = (int)$this->input('product_id');
        $colorValueId = (int)$this->input('color_value_id');
        $sizeValueId  = (int)$this->input('size_value_id');
        $price        = $this->toDecimal($this->input('price'));
        $salePrice    = $this->nullableDecimal($this->input('sale_price'));
        $quantity     = (int)$this->input('quantity', 0);
        $imageUrl     = $this->nullIfEmpty($this->input('image_url'));

        $data = compact('productId', 'price', 'salePrice', 'quantity', 'imageUrl');
        $data = [
            'product_id' => $productId,
            'price'      => $price,
            'sale_price' => $salePrice,
            'quantity'   => $quantity,
            'image_url'  => $imageUrl,
        ];

        // Validate cơ bản
        $errors = [];
        if ($productId <= 0)                $errors['product_id']     = 'Vui lòng chọn sản phẩm.';
        if ($colorValueId <= 0)             $errors['color_value_id'] = 'Vui lòng chọn màu.';
        if ($sizeValueId  <= 0)             $errors['size_value_id']  = 'Vui lòng chọn size.';
        if ($price < 0)                     $errors['price']          = 'Giá bán không hợp lệ.';
        if ($salePrice !== null && $salePrice < 0) $errors['sale_price'] = 'Giá khuyến mãi không hợp lệ.';
        if ($salePrice !== null && $salePrice > $price) $errors['sale_price'] = 'sale_price không được lớn hơn price.';
        if ($quantity < 0)                  $errors['quantity']       = 'Số lượng không hợp lệ.';

        // (khuyến nghị) đảm bảo chọn đúng nhóm thuộc tính
        $attrMap = $this->variantModel->getAttributeMapForValues($colorValueId, $sizeValueId);
        if (($attrMap[$colorValueId] ?? 0) !== 1) $errors['color_value_id'] = 'Màu không hợp lệ.';
        if (($attrMap[$sizeValueId]  ?? 0) !== 2) $errors['size_value_id']  = 'Size không hợp lệ.';

        if (!empty($errors)) {
            $_SESSION['errors_variants'] = $errors;
            $_SESSION['old_variants'] = [
                'product_id' => $productId,
                'color_value_id' => $colorValueId,
                'size_value_id' => $sizeValueId,
                'price' => $this->input('price'),
                'sale_price' => $this->input('sale_price'),
                'quantity' => $this->input('quantity'),
                'image_url' => $imageUrl
            ];
            return $this->redirect('index.php?admin=add_variant' . ($productId ? "&product_id=$productId" : ''));
        }

        // ===== CHẶN TRÙNG (product + color + size) =====
        if ($dup = $this->variantModel->existsCombination($productId, $colorValueId, $sizeValueId)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => "Biến thể (màu & size) đã tồn tại: #$dup"];
            $_SESSION['old_variants'] = [
                'product_id' => $productId,
                'color_value_id' => $colorValueId,
                'size_value_id' => $sizeValueId,
                'price' => $this->input('price'),
                'sale_price' => $this->input('sale_price'),
                'quantity' => $this->input('quantity'),
                'image_url' => $imageUrl
            ];
            return $this->redirect("index.php?admin=add_variant&product_id=$productId");
        }

        // Tạo
        $valueIds = [$colorValueId, $sizeValueId];
        try {
            $vid = $this->variantModel->create($data, $valueIds);
            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã tạo biến thể #$vid"];
            return $this->redirect("index.php?admin=list_variant&product_id=$productId");
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Tạo biến thể thất bại: ' . $e->getMessage()];
            $_SESSION['old_variants'] = [
                'product_id' => $productId,
                'color_value_id' => $colorValueId,
                'size_value_id' => $sizeValueId,
                'price' => $this->input('price'),
                'sale_price' => $this->input('sale_price'),
                'quantity' => $this->input('quantity'),
                'image_url' => $imageUrl
            ];
            return $this->redirect("index.php?admin=add_variant&product_id=$productId");
        }
    }
    /* ====================== EDIT ====================== */

    // GET form edit
    // GET ?admin=variant#edit&id=
    public function edit()
    {
        $id = (int)$this->input('id');
        if ($id <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu ID biến thể'];
            return $this->redirect('index.php?admin=list_variant');
        }

        try {
            $variant  = $this->variantModel->getById($id);
            if (!$variant) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Biến thể không tồn tại'];
                return $this->redirect('index.php?admin=list_variant');
            }

            $products = $this->variantModel->getAllProducts();
            $colors   = $this->variantModel->getAllColors();
            $sizes    = $this->variantModel->getAllSizes();

            // Map ảnh theo sản phẩm để view fill khi chọn product
            $imagesByProduct = $this->variantModel->getImagesGroupedByProduct(array_column($products, 'id'));

            $content = getContentPath('Variant', 'variantEdit');
            view('admin/master', [
                'content'         => $content,
                'variant'         => $variant,
                'products'        => $products,
                'colors'          => $colors,
                'sizes'           => $sizes,
                'imagesByProduct' => $imagesByProduct,
            ]);
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Không tải được dữ liệu: ' . $e->getMessage()];
            $this->redirect('index.php?admin=list_variant');
        }
    }
    // POST cập nhật
    // POST ?admin=variant#update
    public function update()
    {
        $this->ensurePost();
        $id           = (int)$this->input('id');
        $productId    = (int)$this->input('product_id');
        $colorValueId = (int)$this->input('color_value_id');
        $sizeValueId  = (int)$this->input('size_value_id');

        $data = [
            'product_id' => $productId,
            'price'      => $this->toDecimal($this->input('price')),
            'sale_price' => $this->nullableDecimal($this->input('sale_price')),
            'quantity'   => (int)$this->input('quantity', 0),
            'image_url'  => $this->nullIfEmpty($this->input('image_url')),
        ];

        // chặn trùng tổ hợp (bỏ qua chính nó)
        if ($dup = $this->variantModel->existsCombination($productId, $colorValueId, $sizeValueId, $id)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => "Biến thể (màu & size) đã tồn tại: #$dup"];
            $_SESSION['old_variants'] = $_POST;
            return $this->redirect("index.php?admin=edit_variant&id=$id");
        }

        // cập nhật + ghi lại mapping value_ids
        $valueIds = [$colorValueId, $sizeValueId];
        try {
            $this->variantModel->update($id, $data, $valueIds);
            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã cập nhật biến thể #$id"];
            return $this->redirect('index.php?admin=list_variant' . ($productId ? "&product_id=$productId" : ''));
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Cập nhật thất bại: ' . $e->getMessage()];
            $_SESSION['old_variants'] = $_POST;
            return $this->redirect("index.php?admin=edit_variant&id=$id");
        }
    }
    /* ====================== DELETE ====================== */

    // POST ?admin=variant#delete
    public function delete()
    {
        $this->ensurePost();
        $id = (int)$this->input('id');
        if ($id <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu ID biến thể'];
            return $this->redirect('index.php?admin=list_variant');
        }

        try {
            $ok = $this->variantModel->delete($id);
            $_SESSION['alert'] = $ok
                ? ['type' => 'success', 'message' => "Đã xoá biến thể #$id"]
                : ['type' => 'warning', 'message' => "Không xoá được biến thể #$id"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Xoá thất bại: ' . $e->getMessage()];
        }
        $this->redirect('index.php?admin=list_variant');
    }

    /* ================== IMAGES & STOCK ================== */

    // POST ?admin=variant#addImages
    public function addImages()
    {
        $this->ensurePost();
        $variantId = (int)$this->input('variant_id');
        $imageUrls = $this->inputArray('image_urls');

        if ($variantId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu variant_id'];
            return $this->redirect('index.php?admin=list_variant');
        }

        try {
            $count = $this->variantModel->addImages($variantId, $imageUrls);
            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã thêm {$count} ảnh cho biến thể #{$variantId}"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thêm ảnh thất bại: ' . $e->getMessage()];
        }
        $this->redirect("index.php?admin=add_variant#edit&id={$variantId}");
    }

    // POST ?admin=variant#removeImage
    public function removeImage()
    {
        $this->ensurePost();
        $imageId   = (int)$this->input('image_id');
        $variantId = (int)$this->input('variant_id');

        if ($imageId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu image_id'];
            return $this->redirect('index.php?admin=list_variant');
        }

        try {
            $ok = $this->variantModel->removeImage($imageId);
            $_SESSION['alert'] = $ok
                ? ['type' => 'success', 'message' => "Đã xoá ảnh #{$imageId}"]
                : ['type' => 'warning', 'message' => "Không xoá được ảnh #{$imageId}"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Xoá ảnh thất bại: ' . $e->getMessage()];
        }
        $to = $variantId > 0 ? "index.php?admin=variant#edit&id={$variantId}" : "index.php?admin=list_variant";
        $this->redirect($to);
    }

    // POST ?admin=variant#adjustStock
    public function adjustStock()
    {
        $this->ensurePost();
        $variantId = (int)$this->input('variant_id');
        $delta     = (int)$this->input('delta', 0);

        if ($variantId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu variant_id'];
            return $this->redirect('index.php?admin=list_variant');
        }

        try {
            if ($delta >= 0) {
                $this->variantModel->incrementStock($variantId, $delta);
            } else {
                $this->variantModel->decrementStock($variantId, abs($delta), true);
            }
            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã cập nhật tồn kho (#{$variantId}): {$delta}"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Cập nhật tồn kho thất bại: ' . $e->getMessage()];
        }
        $this->redirect("index.php?admin=variant#edit&id={$variantId}");
    }

    /* ====================== HELPERS ====================== */

    private function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    private function inputArray(string $key): array
    {
        $val = $this->input($key, []);
        if (is_string($val)) {
            // Cho phép nhập dạng "url1,url2"
            $parts = array_map('trim', explode(',', $val));
            return array_values(array_filter($parts, fn($x) => $x !== ''));
        }
        if (!is_array($val)) return [];
        // lọc rỗng
        return array_values(array_filter($val, fn($x) => $x !== null && $x !== ''));
    }

    private function toDecimal($v)
    {
        if ($v === null || $v === '') return 0;
        // loại bỏ dấu ngăn cách . , và khoảng trắng
        $v = str_replace(['.', ',', ' '], ['', '.', ''], (string)$v);
        return (float)$v;
    }

    private function nullableDecimal($v)
    {
        $v = trim((string)$v);
        if ($v === '') return null;
        return $this->toDecimal($v);
    }

    private function nullIfEmpty($v)
    {
        $v = trim((string)$v);
        return $v === '' ? null : $v;
    }

    private function ensurePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
    }

    private function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Validate dữ liệu variant. $isCreate = true khi tạo mới.
     */
    private function validateVariant(array $data, bool $isCreate): array
    {
        $errs = [];

        if ($isCreate && empty($data['product_id'])) {
            $errs[] = 'Thiếu product_id.';
        }
        if (isset($data['price']) && $data['price'] < 0) {
            $errs[] = 'Giá bán (price) không hợp lệ.';
        }
        if (array_key_exists('sale_price', $data) && $data['sale_price'] !== null) {
            if ($data['sale_price'] < 0) {
                $errs[] = 'Giá khuyến mãi (sale_price) không hợp lệ.';
            } elseif (isset($data['price']) && $data['sale_price'] > $data['price']) {
                $errs[] = 'sale_price không được lớn hơn price.';
            }
        }
        if (isset($data['quantity']) && $data['quantity'] < 0) {
            $errs[] = 'Số lượng không hợp lệ.';
        }

        return $errs;
    }
}
