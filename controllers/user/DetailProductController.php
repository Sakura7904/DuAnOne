<?php
include_once 'models/user/ProductClientModel.php';
class DetailProductController
{
    private $productClientModel;

    public function __construct()
    {
        $this->productClientModel = new ProductClientModel();
    }

    public function detailProduct()
    {
        // Lấy product ID từ URL
        $productId = $_GET['id'] ?? null;

        if (!$productId) {
            // Redirect về trang chủ nếu không có ID
            header('Location: ?user=home');
            exit;
        }

        // Lấy user ID nếu đã đăng nhập
        $userId = $_SESSION['user_id'] ?? null;

        // Lấy tất cả dữ liệu sản phẩm
        $data = $this->productClientModel->getCompleteProductData($productId, $userId);

        if (!$data) {
            // Sản phẩm không tồn tại - redirect hoặc hiển thị lỗi
            header('Location: ?user=home');
            exit;
        }

        // Kiểm tra theo model (phải có biến thể + có cả màu & size + có giá > 0)
        if (
            empty($data['variants']) ||
            empty($data['attributes']) ||
            !$this->hasColorAndSize($data['attributes']) ||
            !$this->hasValidVariantPrice($data['variants'])
        ) {
            // Không đủ điều kiện hiển thị theo rule model -> về trang chủ
            header('Location: ?user=home');
            exit;
        }

        // Chuẩn bị dữ liệu để truyền vào view
        $product         = $data['product'];
        $variants        = $data['variants'];
        $attributes      = $data['attributes'];
        $images          = $data['images'];
        $comments        = $data['comments'];
        $totalComments   = $data['total_comments'];
        $relatedProducts = $data['related_products'];

        // ADD: lọc relatedProducts theo rule (có màu + size + giá > 0)
        $filteredRelated = [];
        foreach (($relatedProducts ?? []) as $rp) {
            $ok = false;

            // Trường hợp model đã gắn color_options + min/max price
            if (isset($rp['color_options']) && is_array($rp['color_options'])) {
                $hasColor = count($rp['color_options']) > 0;
                $minPrice = isset($rp['min_price']) ? (float)$rp['min_price'] : null;
                $maxPrice = isset($rp['max_price']) ? (float)$rp['max_price'] : null;
                $disp     = $minPrice !== null && $minPrice > 0 ? $minPrice : ($maxPrice ?? 0);
                if ($hasColor && $disp > 0) $ok = true;
            }

            // Nếu không có color_options nhưng có attributes/variants
            if (
                !$ok
                && isset($rp['attributes'], $rp['variants'])
                && is_array($rp['attributes']) && is_array($rp['variants'])
            ) {
                if ($this->hasColorAndSize($rp['attributes']) && $this->hasValidVariantPrice($rp['variants'])) {
                    $ok = true;
                }
            }

            if ($ok) $filteredRelated[] = $rp;
        }
        $relatedProducts = $filteredRelated;

        // Tính giá min/max
        $prices = array_map(function ($variant) {
            return $variant['sale_price'] ?? $variant['price'];
        }, $variants);

        $minPrice = min($prices);
        $maxPrice = max($prices);

        // Lấy variant mặc định (variant đầu tiên)
        $defaultVariant = $variants[0] ?? null;

        // Nhóm ảnh theo variant
        $imagesByVariant = [];
        foreach ($images as $image) {
            $variantId = $image['variant_id'];
            if (!isset($imagesByVariant[$variantId])) {
                $imagesByVariant[$variantId] = [];
            }
            $imagesByVariant[$variantId][] = $image;
        }

        // Lấy content path
        $content = getContentPathClient('', 'detailProduct');

        // Truyền tất cả dữ liệu vào view
        view('user/index', [
            'content'       => $content,
            'product'       => $product,
            'variants'      => $variants,
            'attributes'    => $attributes,
            'images'        => $images,
            'comments'      => $comments,
            'totalComments' => $totalComments,
            'relatedProducts' => $relatedProducts,
            'minPrice'      => $minPrice,
            'maxPrice'      => $maxPrice,
            'defaultVariant'=> $defaultVariant,
            'imagesByVariant' => $imagesByVariant,
            'userId'        => $userId
        ]);
    }

    /**
     * API lấy size theo màu đã chọn
     */
    public function getSizesByColor()
    {
        header('Content-Type: application/json');

        $productId  = $_POST['product_id'] ?? null;
        $colorValue = $_POST['color_value'] ?? null;

        if (!$productId || !$colorValue) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            return;
        }

        $sizes = $this->productClientModel->getSizesByColor($productId, $colorValue);

        echo json_encode([
            'success' => true,
            'sizes'   => $sizes
        ]);
    }

    /**
     * API lấy thông tin variant và số lượng tồn kho theo màu và size
     */
    public function getVariantByColorAndSize()
    {
        header('Content-Type: application/json');

        $productId  = $_POST['product_id'] ?? null;
        $colorValue = $_POST['color_value'] ?? null;
        $sizeValue  = $_POST['size_value'] ?? null;

        if (!$productId || !$colorValue || !$sizeValue) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            return;
        }

        $variant = $this->productClientModel->getVariantByColorAndSize($productId, $colorValue, $sizeValue);

        if ($variant) {
            echo json_encode([
                'success'  => true,
                'variant'  => $variant,
                'quantity' => $variant['quantity']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        }
    }

    // Kiểm tra sản phẩm có hợp lệ để hiển thị không
    private function hasColorAndSize(array $attributes): bool
    {
        $hasColor = false;
        $hasSize  = false;
        foreach ($attributes as $attr) {
            $name = mb_strtolower(trim($attr['name'] ?? ''), 'UTF-8');
            if (in_array($name, ['color', 'màu', 'màu sắc', 'mau', 'mau sac'], true)) $hasColor = true;
            if (in_array($name, ['size', 'kích thước', 'kich thuoc', 'kích thuớc'], true)) $hasSize = true;
            if ($hasColor && $hasSize) return true;
        }
        return false;
    }

    private function hasValidVariantPrice(array $variants): bool
    {
        foreach ($variants as $v) {
            $sale = isset($v['sale_price']) ? (float)$v['sale_price'] : null;
            $base = isset($v['price']) ? (float)$v['price'] : null;
            $display = $sale && $sale > 0 ? $sale : ($base ?? 0);
            if ($display > 0) return true;
        }
        return false;
    }
}
