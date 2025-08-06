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

        // Chuẩn bị dữ liệu để truyền vào view
        $product = $data['product'];
        $variants = $data['variants'];
        $attributes = $data['attributes'];
        $images = $data['images'];
        $comments = $data['comments'];
        $totalComments = $data['total_comments'];
        $relatedProducts = $data['related_products'];

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
            'content' => $content,
            'product' => $product,
            'variants' => $variants,
            'attributes' => $attributes,
            'images' => $images,
            'comments' => $comments,
            'totalComments' => $totalComments,
            'relatedProducts' => $relatedProducts,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'defaultVariant' => $defaultVariant,
            'imagesByVariant' => $imagesByVariant,
            'userId' => $userId
        ]);
    }
}
