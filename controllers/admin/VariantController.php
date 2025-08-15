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

    // GET ?admin=variant#index&product_id=&min_quantity=&page=
    public function index()
    {
        $filters = [
            'product_id'   => $this->input('product_id'),
            'min_quantity' => $this->input('min_quantity'),
        ];
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = 20;


        $variants = $this->variantModel->listWithDetails($filters, $page, $perPage);

        $content  = getContentPath('Variant', 'variantList');
        view('admin/master', [
            'content'        => $content,
            'variants'       => $variants,
            'totalVariants'  => count($variants), // hoặc query COUNT riêng nếu bạn cần tổng all trang
            'filters'        => $filters,
            'page'           => $page,
            'perPage'        => $perPage,
        ]);
    }


    /* ====================== CREATE ====================== */

    // GET form
    // GET ?admin=variant#create&product_id=
    public function create()
    {
        $content = getContentPath('Variant', 'variantForm');
        view('admin/master', [
            'content' => $content,
            'mode'    => 'create',
            'variant' => [
                'product_id' => $this->input('product_id'),
                'price'      => '',
                'sale_price' => '',
                'quantity'   => 0,
                'image_url'  => '',
            ],
            // view sẽ hiển thị các checkbox/select cho value_ids (màu/size) nếu bạn có sẵn
            'value_ids' => [],
        ]);
    }

    // POST lưu
    // POST ?admin=variant#store
    public function store()
    {
        $this->ensurePost();

        $data = [
            'product_id' => (int)$this->input('product_id'),
            'price'      => $this->toDecimal($this->input('price')),
            'sale_price' => $this->nullableDecimal($this->input('sale_price')),
            'quantity'   => (int)$this->input('quantity', 0),
            'image_url'  => $this->nullIfEmpty($this->input('image_url')),
        ];
        $valueIds = $this->inputArray('value_ids'); // từ form: name="value_ids[]"

        $errors = $this->validateVariant($data, true);
        if ($errors) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            // đẩy lại form cùng dữ liệu cũ
            $content = getContentPath('Variant', 'variantForm');
            return view('admin/master', [
                'content'  => $content,
                'mode'     => 'create',
                'variant'  => $data,
                'value_ids' => $valueIds,
            ]);
        }

        try {
            $vid = $this->variantModel->create($data, $valueIds);

            // (Tuỳ chọn) nhận nhiều image_urls[] (text) để add vào bảng productimages
            $imageUrls = $this->inputArray('image_urls'); // name="image_urls[]"
            if (!empty($imageUrls)) {
                $this->variantModel->addImages($vid, $imageUrls);
            }

            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã tạo biến thể #$vid"];
            $this->redirect('index.php?admin=variant#index&product_id=' . (int)$data['product_id']);
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Tạo biến thể thất bại: ' . $e->getMessage()];
            $this->redirect('index.php?admin=variant#create&product_id=' . (int)$data['product_id']);
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
            return $this->redirect('index.php?admin=variant#index');
        }

        try {
            $variant = $this->variantModel->getById($id);
            if (!$variant) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Biến thể không tồn tại'];
                return $this->redirect('index.php?admin=variant#index');
            }

            $content = getContentPath('Variant', 'variantForm');
            view('admin/master', [
                'content'   => $content,
                'mode'      => 'edit',
                'variant'   => $variant,
                'value_ids' => array_column($variant['attribute_values'] ?? [], 'value_id'),
            ]);
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Không tải được biến thể: ' . $e->getMessage()];
            $this->redirect('index.php?admin=variant#index');
        }
    }

    // POST cập nhật
    // POST ?admin=variant#update
    public function update()
    {
        $this->ensurePost();

        $id = (int)$this->input('id');
        if ($id <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu ID biến thể'];
            return $this->redirect('index.php?admin=variant#index');
        }

        $data = [];
        // chỉ update những field có trong form
        foreach (['product_id', 'price', 'sale_price', 'quantity', 'image_url'] as $f) {
            if (isset($_POST[$f])) {
                $data[$f] = $_POST[$f];
            }
        }

        if (isset($data['product_id'])) $data['product_id'] = (int)$data['product_id'];
        if (isset($data['price']))      $data['price']      = $this->toDecimal($data['price']);
        if (array_key_exists('sale_price', $data)) $data['sale_price'] = $this->nullableDecimal($data['sale_price']);
        if (isset($data['quantity']))   $data['quantity']   = (int)$data['quantity'];
        if (isset($data['image_url']))  $data['image_url']  = $this->nullIfEmpty($data['image_url']);

        $valueIds = isset($_POST['value_ids']) ? $this->inputArray('value_ids') : null;

        $errors = $this->validateVariant($data, false);
        if ($errors) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            return $this->redirect("index.php?admin=variant#edit&id={$id}");
        }

        try {
            $ok = $this->variantModel->update($id, $data, $valueIds);
            if (!$ok) {
                $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Không có thay đổi nào được áp dụng'];
            } else {
                // (Tuỳ chọn) thêm mới ảnh từ image_urls[]
                $imageUrls = $this->inputArray('image_urls');
                if (!empty($imageUrls)) {
                    $this->variantModel->addImages($id, $imageUrls);
                }
                $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã cập nhật biến thể #$id"];
            }
            $productId = $data['product_id'] ?? 0;
            $goto = $productId ? "index.php?admin=variant#index&product_id={$productId}" : "index.php?admin=variant#index";
            $this->redirect($goto);
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Cập nhật thất bại: ' . $e->getMessage()];
            $this->redirect("index.php?admin=variant#edit&id={$id}");
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
            return $this->redirect('index.php?admin=variant#index');
        }

        try {
            $ok = $this->variantModel->delete($id);
            $_SESSION['alert'] = $ok
                ? ['type' => 'success', 'message' => "Đã xoá biến thể #$id"]
                : ['type' => 'warning', 'message' => "Không xoá được biến thể #$id"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Xoá thất bại: ' . $e->getMessage()];
        }
        $this->redirect('index.php?admin=variant#index');
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
            return $this->redirect('index.php?admin=variant#index');
        }

        try {
            $count = $this->variantModel->addImages($variantId, $imageUrls);
            $_SESSION['alert'] = ['type' => 'success', 'message' => "Đã thêm {$count} ảnh cho biến thể #{$variantId}"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thêm ảnh thất bại: ' . $e->getMessage()];
        }
        $this->redirect("index.php?admin=variant#edit&id={$variantId}");
    }

    // POST ?admin=variant#removeImage
    public function removeImage()
    {
        $this->ensurePost();
        $imageId   = (int)$this->input('image_id');
        $variantId = (int)$this->input('variant_id');

        if ($imageId <= 0) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Thiếu image_id'];
            return $this->redirect('index.php?admin=variant#index');
        }

        try {
            $ok = $this->variantModel->removeImage($imageId);
            $_SESSION['alert'] = $ok
                ? ['type' => 'success', 'message' => "Đã xoá ảnh #{$imageId}"]
                : ['type' => 'warning', 'message' => "Không xoá được ảnh #{$imageId}"];
        } catch (Throwable $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Xoá ảnh thất bại: ' . $e->getMessage()];
        }
        $to = $variantId > 0 ? "index.php?admin=variant#edit&id={$variantId}" : "index.php?admin=variant#index";
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
            return $this->redirect('index.php?admin=variant#index');
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
