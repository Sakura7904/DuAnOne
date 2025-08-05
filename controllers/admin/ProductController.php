<?php
class ProductController
{
    private $productImageModel;
    private $productVariantModel;
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productImageModel = new ProductImageModel();
        $this->productVariantModel = new ProductVariantModel();
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // Lấy trang hiện tại từ URL
        $page  = max(1, (int)($_GET['p'] ?? 1));
        $limit = 10;

        $totalProducts = $this->productModel->countProducts();
        $totalPages    = (int)ceil($totalProducts / $limit);

        $products = $this->productModel->getProductsPaginated($page, $limit);

        // Xử lý dữ liệu để hiển thị (giữ nguyên logic cũ)
        foreach ($products as &$product) {
            // Xử lý màu sắc
            $product['color_list'] = !empty($product['colors'])
                ? array_filter(explode(',', $product['colors']))
                : [];

            // Xử lý kích thước
            $product['size_list'] = !empty($product['sizes'])
                ? array_filter(explode(',', $product['sizes']))
                : [];

            // Xử lý giá hiển thị
            if ($product['min_price'] == $product['max_price']) {
                $product['price_display'] = number_format($product['min_price'], 0, ',', '.') . ' VNĐ';
            } else {
                $product['price_display'] = number_format($product['min_price'], 0, ',', '.') . ' - ' .
                    number_format($product['max_price'], 0, ',', '.') . ' VNĐ';
            }

            // Xử lý ảnh thumbnail
            $product['thumbnail_display'] = !empty($product['image_thumbnail'])
                ? $product['image_thumbnail']
                : 'images/no-image.jpg';

            // Đảm bảo total_quantity là số nguyên
            $product['total_quantity'] = (int)$product['total_quantity'];
        }

        // Thông tin phân trang

        $pagination = [
            'current'   => $page,
            'total'     => $totalPages,
            'hasPrev'   => $page > 1,
            'hasNext'   => $page < $totalPages,
            'prev'      => $page - 1,
            'next'      => $page + 1
        ];

        $content = getContentPath('Products', 'productsList');
        view('admin/master', [
            'content' => $content,
            'data'    => [
                'products'   => $products,
                'pagination' => $pagination
            ]
        ]);
    }



    public function create()
    {
        $categories = $this->categoryModel->getAllCategories();
        $content = getContentPath('Products', 'productsAdd');
        view('admin/master', ['content' => $content, 'categories' => $categories]);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?admin=list_products');
            exit;
        }

        try {
            // Validate dữ liệu
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);

            if (empty($name)) {
                throw new Exception('Tên sản phẩm không được để trống');
            }

            if ($price <= 0) {
                throw new Exception('Giá sản phẩm phải lớn hơn 0');
            }

            // Xử lý upload ảnh thumbnail
            $thumbnail_path = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbnail_path = $this->uploadImage($_FILES['thumbnail'], 'thumbnail');
            }

            // Thêm sản phẩm vào database
            $product_id = $this->productModel->addProduct($name, $description, $category_id, $thumbnail_path);

            if (!$product_id) {
                throw new Exception('Không thể thêm sản phẩm');
            }

            // Tạo variant mặc định với giá gốc
            $variant_id = $this->productVariantModel->addVariant($product_id, $price, null, 0);

            // Xử lý upload ảnh gallery
            if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                $this->uploadGalleryImages($_FILES['gallery'], $variant_id);
            }

            $_SESSION['success_products'] = 'Thêm sản phẩm thành công!';
            header('Location: ?admin=list_products');
            exit;
        } catch (Exception $e) {
            $_SESSION['error_products'] = $e->getMessage();
            header('Location: ?admin=add_products');
            exit;
        }
    }

    /**
     * Hiển thị form chi tiết sản phẩm
     */
    public function show($id = 0)
    {
        $id = (int)$id;
        if ($id <= 0) {
            $_SESSION['product_error'] = 'ID sản phẩm không hợp lệ';
            header('Location: ?admin=list_products');
            exit;
        }

        try {
            // Lấy chi tiết sản phẩm với màu sắc, size và tồn kho
            $product = $this->productModel->getProductDetailWithVariants($id);

            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm');
            }

            // Lấy variants chi tiết (nếu cần)
            $variants = $this->productVariantModel->getVariantsByProductId($id);

            // Lấy ảnh gallery
            $galleryImages = [];
            if (!empty($variants)) {
                $galleryImages = $this->productImageModel->getImagesByVariantId($variants[0]['id']);
            }

            $data = [
                'product' => $product,
                'variants' => $variants,
                'galleryImages' => $galleryImages,
                'title' => 'Chi tiết sản phẩm: ' . $product['name']
            ];

            $content = getContentPath('Products', 'productsDetail');
            view('admin/master', ['content' => $content, 'data' => $data]);
        } catch (Exception $e) {
            $_SESSION['product_error'] = '❌ ' . $e->getMessage();
            header('Location: ?admin=list_products');
            exit;
        }
    }
    /**
     * Hiển thị form sửa sản phẩm
     */
    public function edit($id = 0)
    {
        $id = (int)$id;
        if ($id <= 0) {
            $_SESSION['product_error'] = 'ID sản phẩm không hợp lệ';
            header('Location: ?admin=list_products');
            exit;
        }

        try {
            // Lấy thông tin sản phẩm
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm');
            }

            // Lấy categories
            $categories = $this->categoryModel->getAllCategories();

            // Lấy variants
            $variants = $this->productVariantModel->getVariantsByProductId($id);

            // LẤY ẢNH GALLERY HIỆN TẠI - QUAN TRỌNG
            $galleryImages = [];
            if (!empty($variants)) {
                $galleryImages = $this->productImageModel->getImagesByVariantId($variants[0]['id']);
            }

            $data = [
                'product' => $product,
                'categories' => $categories,
                'variants' => $variants,
                'galleryImages' => $galleryImages,  // ← Truyền ảnh hiện tại
                'defaultVariant' => !empty($variants) ? $variants[0] : null,
                'title' => 'Sửa sản phẩm: ' . $product['name']
            ];

            $content = getContentPath('Products', 'productsEdit');
            view('admin/master', ['content' => $content, 'data' => $data]);
        } catch (Exception $e) {
            $_SESSION['product_error'] = '❌ ' . $e->getMessage();
            header('Location: ?admin=list_products');
            exit;
        }
    }

    /**
     * Xử lý cập nhật sản phẩm
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?admin=list_products');
            exit;
        }

        try {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID sản phẩm không hợp lệ');
            }

            // Validate dữ liệu
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);

            if (empty($name)) {
                throw new Exception('Tên sản phẩm không được để trống');
            }

            if ($price <= 0) {
                throw new Exception('Giá sản phẩm phải lớn hơn 0');
            }

            // Lấy thông tin sản phẩm hiện tại
            $currentProduct = $this->productModel->getProductById($id);
            if (!$currentProduct) {
                throw new Exception('Không tìm thấy sản phẩm');
            }

            // Xử lý upload thumbnail mới (nếu có)
            $thumbnail_path = $currentProduct['image_thumbnail']; // Giữ ảnh cũ

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                // Upload ảnh mới
                $new_thumbnail = $this->uploadImage($_FILES['thumbnail'], 'thumbnail');

                // Xóa ảnh cũ nếu có
                if ($thumbnail_path && file_exists($thumbnail_path)) {
                    unlink($thumbnail_path);
                }

                $thumbnail_path = $new_thumbnail;
            }

            // Cập nhật thông tin sản phẩm
            $result = $this->productModel->updateProduct($id, $name, $description, $category_id, $thumbnail_path);

            if (!$result) {
                throw new Exception('Không thể cập nhật sản phẩm');
            }

            // Cập nhật giá của variant đầu tiên (nếu có)
            $variants = $this->productVariantModel->getVariantsByProductId($id);
            if (!empty($variants)) {
                $this->productVariantModel->updateVariant(
                    $variants[0]['id'],
                    $price,
                    $variants[0]['sale_price'],
                    $variants[0]['quantity']
                );
            }

            // Xử lý upload ảnh gallery mới (nếu có)
            if (!empty($_FILES['gallery']['name'][0])) {
                $variant_id = $variants[0]['id'] ?? 0;
                if (!$variant_id) throw new Exception('Không tìm thấy variant mặc định');

                // Re-use hàm đã có – upload & trả về mảng đường dẫn
                $uploaded = $this->handleMultipleImageUpload($_FILES['gallery'], $variant_id);

                if (empty($uploaded)) {
                    throw new Exception('Không có ảnh nào được upload thành công');
                }

                // Ghi DB
                if (!$this->productImageModel->addMultipleImages($variant_id, $uploaded)) {
                    throw new Exception('Lỗi khi lưu ảnh vào database');
                }
            }

            $_SESSION['success_products'] = '✅ Cập nhật sản phẩm thành công!';
            header('Location: ?admin=list_products');
            exit;
        } catch (Exception $e) {
            $_SESSION['product_error'] = '❌ ' . $e->getMessage();
            header('Location: ?admin=edit_product&id=' . ($id ?? 0));
            exit;
        }
    }

    public function deleteProductGallery()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit;
        }

        try {
            // Lấy thông tin ảnh
            $image = $this->productImageModel->getImageById($id);
            if (!$image) {
                http_response_code(404);
                exit;
            }

            // Xóa file vật lý
            if (file_exists($image['image_url'])) {
                unlink($image['image_url']);
            }

            // Xóa record khỏi DB
            $this->productImageModel->deleteImage($id);

            http_response_code(200);
            echo 'success';
        } catch (Exception $e) {
            http_response_code(500);
            echo 'error';
        }
        exit;
    }


    public function delete($id = 0)
    {
        $id = (int)$id;
        if ($id <= 0) {
            $_SESSION['error_products'] = 'ID sản phẩm không hợp lệ';
            header('Location: ?admin=list_products');
            exit;
        }

        try {
            // 1. Kiểm tra sản phẩm có tồn tại không
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm');
            }

            // 2. KIỂM TRA TỒN KHO - QUAN TRỌNG NHẤT
            $variants = $this->productVariantModel->getVariantsByProductId($id);
            $totalStock = 0;
            $stockDetails = [];

            foreach ($variants as $variant) {
                $quantity = (int)$variant['quantity'];
                $totalStock += $quantity;

                if ($quantity > 0) {
                    // Lấy thông tin attributes nếu có
                    $variantInfo = !empty($variant['attributes'])
                        ? $variant['attributes']
                        : "Variant #" . $variant['id'];
                    $stockDetails[] = "- {$variantInfo}: {$quantity} sản phẩm";
                }
            }

            // Nếu còn hàng thì không cho xóa
            if ($totalStock > 0) {
                $msg = "Không thể xóa '{$product['name']}'\n  Còn {$totalStock} sản phẩm:\n" .
                    implode("\n", $stockDetails);
                throw new Exception($msg);
            }

            // 3. Xóa file ảnh gallery
            foreach ($variants as $variant) {
                $images = $this->productImageModel->getImagesByVariantId($variant['id']);
                foreach ($images as $img) {
                    if (file_exists($img['image_url'])) {
                        unlink($img['image_url']);
                    }
                }
            }

            // 4. Xóa thumbnail
            if ($product['image_thumbnail'] && file_exists($product['image_thumbnail'])) {
                unlink($product['image_thumbnail']);
            }

            // 5. Xóa sản phẩm (CASCADE sẽ xóa variants + images record)
            if (!$this->productModel->deleteProduct($id)) {
                throw new Exception('Lỗi khi xóa sản phẩm khỏi database');
            }

            $_SESSION['success_products'] = '✅ Đã xóa sản phẩm thành công!';
        } catch (Exception $e) {
            $_SESSION['error_products'] =  $e->getMessage();
        }

        header('Location: ?admin=list_products');
        exit;
    }


    private function uploadImage($file, $type = 'gallery')
    {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Kiểm tra loại file
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Chỉ cho phép upload file JPG, PNG, GIF, WEBP');
        }

        // Kiểm tra kích thước
        if ($file['size'] > $max_size) {
            throw new Exception('File ảnh không được vượt quá 5MB');
        }

        // Tạo thư mục nếu chưa tồn tại
        $upload_dir = 'images/products/';
        if ($type === 'thumbnail') {
            $upload_dir = 'images/products_thumbnail/';
        }

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;

        // Upload file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Không thể upload ảnh');
        }

        return $filepath;
    }

    private function uploadGalleryImages($files, $variant_id)
    {
        $uploaded_count = 0;

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'size' => $files['size'][$i]
                ];

                try {
                    $filepath = $this->uploadImage($file, 'gallery');
                    $this->productImageModel->addImage($variant_id, $filepath);
                    $uploaded_count++;
                } catch (Exception $e) {
                    // Log lỗi nhưng không dừng quá trình
                    error_log("Upload gallery image failed: " . $e->getMessage());
                }
            }
        }

        return $uploaded_count;
    }

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
}
