<?php
require_once 'models/admin/ProductImageModel.php';
require_once 'models/admin/ProductVariantModel.php';
require_once 'models/admin/ProductModel.php';

class ProductImageController
{
    private $productImageModel;
    private $productVariantModel;
    private $productModel;

    public function __construct()
    {
        $this->productImageModel = new ProductImageModel();
        $this->productVariantModel = new ProductVariantModel();
        $this->productModel = new ProductModel();
    }

    // Hiển thị trang danh sách ảnh (thư viện)
    public function index()
    {
        try {
            // Lấy tất cả ảnh với thông tin sản phẩm
            $images = $this->productImageModel->getAllImages();

            // Lấy danh sách sản phẩm để filter
            $products = $this->productModel->getAllProducts();

            // Thống kê
            $totalImages = count($images);

            // Load view
            $content = getContentPath('Product_Images', 'listProductImages');
            //Nếu để trong thư mục ví dụ: views/admin/pages/products/list_products.php
            //Thì phải truyền tham số trùng với tên thư mục vào getContentPath('products, 'list_products')

            view('admin/master', ['content' => $content, 'images' => $images, 'products' => $products, 'totalImages' => $totalImages]);
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải danh sách ảnh: " . $e->getMessage();
            header('Location: ?act=dashboard');
            exit;
        }
    }

    // Hiển thị form thêm ảnh
    public function create()
    {
        try {
            // Lấy danh sách sản phẩm và variants
            $products = $this->productModel->getAllProducts();
            $variants = $this->productVariantModel->getAllVariants();

            include 'views/admin/product-images/addProductImage.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải form thêm ảnh: " . $e->getMessage();
            header('Location: ?act=product_images');
            exit;
        }
    }

    // Xử lý thêm ảnh
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $variant_id = $_POST['variant_id'] ?? '';

                // Validate input
                if (empty($variant_id)) {
                    throw new Exception("Vui lòng chọn variant sản phẩm");
                }

                // Kiểm tra variant có tồn tại không
                $variant = $this->productVariantModel->getVariantById($variant_id);
                if (!$variant) {
                    throw new Exception("Variant không tồn tại");
                }

                // Xử lý upload ảnh
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $uploadedImages = $this->handleMultipleImageUpload($_FILES['images'], $variant_id);

                    if (empty($uploadedImages)) {
                        throw new Exception("Không có ảnh nào được upload thành công");
                    }

                    // Thêm ảnh vào database
                    $result = $this->productImageModel->addMultipleImages($variant_id, $uploadedImages);

                    if ($result) {
                        $_SESSION['success'] = "Thêm " . count($uploadedImages) . " ảnh thành công!";
                    } else {
                        throw new Exception("Lỗi khi lưu ảnh vào database");
                    }
                } else {
                    throw new Exception("Vui lòng chọn ít nhất một ảnh");
                }

                header('Location: ?act=product_images');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?act=product_images&action=create');
                exit;
            }
        }
    }

    // Hiển thị form chỉnh sửa ảnh
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? '';

            if (empty($id)) {
                throw new Exception("ID ảnh không hợp lệ");
            }

            $image = $this->productImageModel->getImageById($id);
            if (!$image) {
                throw new Exception("Không tìm thấy ảnh");
            }

            // Lấy thông tin variant và product
            $variant = $this->productVariantModel->getVariantById($image['variant_id']);
            $product = $this->productModel->getProductById($variant['product_id']);

            include 'views/admin/product-images/editProductImage.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?act=product_images');
            exit;
        }
    }

    // Xử lý cập nhật ảnh
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? '';

                if (empty($id)) {
                    throw new Exception("ID ảnh không hợp lệ");
                }

                $currentImage = $this->productImageModel->getImageById($id);
                if (!$currentImage) {
                    throw new Exception("Không tìm thấy ảnh");
                }

                // Xử lý upload ảnh mới (nếu có)
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $newImagePath = $this->handleSingleImageUpload($_FILES['image'], $currentImage['variant_id']);

                    if ($newImagePath) {
                        // Xóa ảnh cũ
                        if (file_exists($currentImage['image_url'])) {
                            unlink($currentImage['image_url']);
                        }

                        // Cập nhật đường dẫn ảnh mới
                        $result = $this->productImageModel->updateImage($id, $newImagePath);

                        if ($result) {
                            $_SESSION['success'] = "Cập nhật ảnh thành công!";
                        } else {
                            throw new Exception("Lỗi khi cập nhật ảnh");
                        }
                    }
                } else {
                    $_SESSION['info'] = "Không có ảnh mới được upload";
                }

                header('Location: ?act=product_images');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?act=product_images&action=edit&id=' . ($_POST['id'] ?? ''));
                exit;
            }
        }
    }

    // Xóa ảnh
    public function delete()
    {
        try {
            $id = $_GET['id'] ?? '';

            if (empty($id)) {
                throw new Exception("ID ảnh không hợp lệ");
            }

            $result = $this->productImageModel->deleteImage($id);

            if ($result) {
                $_SESSION['success'] = "Xóa ảnh thành công!";
            } else {
                throw new Exception("Lỗi khi xóa ảnh");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ?act=product_images');
        exit;
    }

    // Xem chi tiết ảnh của một variant
    public function viewByVariant()
    {
        try {
            $variant_id = $_GET['variant_id'] ?? '';

            if (empty($variant_id)) {
                throw new Exception("Variant ID không hợp lệ");
            }

            $images = $this->productImageModel->getImagesByVariantId($variant_id);
            $variant = $this->productVariantModel->getVariantById($variant_id);
            $product = $this->productModel->getProductById($variant['product_id']);

            include 'views/admin/product-images/viewVariantImages.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?act=product_images');
            exit;
        }
    }

    // API endpoint để lấy variants theo product_id (AJAX)
    public function getVariantsByProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? '';

            if (!empty($product_id)) {
                $variants = $this->productVariantModel->getVariantsByProductId($product_id);

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'variants' => $variants
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Product ID không hợp lệ'
                ]);
            }
        }
        exit;
    }

    // Xử lý upload một ảnh
    private function handleSingleImageUpload($file, $variant_id)
    {
        $uploadDir = 'images/product_images/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP");
        }

        if ($file['size'] > $maxFileSize) {
            throw new Exception("File ảnh không được vượt quá 5MB");
        }

        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'variant_' . $variant_id . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            throw new Exception("Lỗi khi upload file ảnh");
        }
    }

    // Xử lý upload nhiều ảnh
    private function handleMultipleImageUpload($files, $variant_id)
    {
        $uploadedFiles = [];
        $uploadDir = 'images/product_images/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'size' => $files['size'][$i]
                ];

                try {
                    $filePath = $this->handleSingleImageUpload($file, $variant_id);
                    $uploadedFiles[] = $filePath;
                } catch (Exception $e) {
                    // Log lỗi nhưng tiếp tục với file khác
                    error_log("Upload error for file {$file['name']}: " . $e->getMessage());
                }
            }
        }

        return $uploadedFiles;
    }

    // Tìm kiếm ảnh
    public function search()
    {
        try {
            $searchTerm = $_GET['search'] ?? '';

            if (empty($searchTerm)) {
                header('Location: ?act=product_images');
                exit;
            }

            $images = $this->productImageModel->searchImagesByName($searchTerm);
            $products = $this->productModel->getAllProducts();
            $totalImages = count($images);

            include 'views/admin/product-images/listProductImages.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tìm kiếm: " . $e->getMessage();
            header('Location: ?act=product_images');
            exit;
        }
    }
}
