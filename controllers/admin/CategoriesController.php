<?php
require_once 'models/admin/CategoryModel.php';
require_once 'database/function.php';

class CategoriesController
{
    private $model;

    public function __construct()
    {
        $db = new Database();
        $this->model = new CategoryModel();
    }

    /* ======================= UPLOAD ẢNH DANH MỤC ======================= */
    /**
     * Xử lý upload ảnh cho category
     * @param array        $file     Mảng $_FILES['image']
     * @param string|null  $oldPath  URL ảnh cũ (vd: /images/categories/abc.jpg) để xoá khi cập nhật
     * @return string|null           URL public để lưu DB (vd: /images/categories/abc.jpg) hoặc null
     */
    private function handleImageUpload(array $file, ?string $oldPath = null): ?string
    {
        // Không chọn file -> giữ nguyên ảnh cũ
        if (empty($file['name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $oldPath;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload lỗi: ' . $file['error']);
        }

        // Giới hạn 2MB
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new RuntimeException('Ảnh quá lớn (tối đa 2MB).');
        }

        // Xác thực MIME thực sự
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($allowed[$mime])) {
            throw new RuntimeException('Chỉ chấp nhận JPG/PNG/WebP.');
        }

        // Thư mục vật lý để lưu (theo DocumentRoot của domain)
        $uploadDirFs = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/images/categories';
        if (!is_dir($uploadDirFs)) {
            mkdir($uploadDirFs, 0775, true);
        }

        // Tên file unique
        $ext = $allowed[$mime];
        $filename = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
        $destFs = $uploadDirFs . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destFs)) {
            throw new RuntimeException('Không thể lưu file.');
        }

        // Xoá ảnh cũ nếu có
        if ($oldPath) {
            $oldFs = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . ltrim($oldPath, '/');
            if (is_file($oldFs)) {
                @unlink($oldFs);
            }
        }

        // Trả về URL public
        return '/images/categories/' . $filename;
    }
    /* =================================================================== */

    // Danh sách
    public function index()
    {
        $categories = $this->model->getAll();
        $allCategories = array_column($categories, 'name', 'id');
        view('admin/master', [
            'content' => 'views/admin/pages/categories/list.php',
            'categories' => $categories,
            'allCategories' => $allCategories
        ]);
    }

    // Thêm
    public function create()
    {
        $parent_categories = $this->model->getAll();
        view('admin/master', [
            'content' => 'views/admin/pages/categories/create.php',
            'parent_categories' => $parent_categories
        ]);
    }

    // Lưu thêm
    public function store()
    {
        $name = $_POST['name'] ?? '';
        $parent_id = $_POST['parent_id'] ?? null;
        $parent_id = ($parent_id === '') ? null : $parent_id;

        try {
            $image_url = $this->handleImageUpload($_FILES['image'] ?? []);
        } catch (Throwable $e) {
            $_SESSION['msg'] = '❌ ' . $e->getMessage();
            $_SESSION['msg_type'] = 'error';
            header('Location: index.php?admin=create_category');
            exit;
        }

        // Model: insert($name, $parent_id, $image_url)
        $this->model->insert($name, $parent_id, $image_url);
        header('Location: index.php?admin=list_categories');
        exit;
    }

    // Sửa
    public function edit($id)
    {
        $category = $this->model->getById($id);
        $parent_categories = $this->model->getAll();
        view('admin/master', [
            'content' => 'views/admin/pages/categories/edit.php',
            'category' => $category,
            'parent_categories' => $parent_categories
        ]);
    }

    // Cập nhật
    public function update($id)
    {
        $name = $_POST['name'] ?? '';
        $parent_id = $_POST['parent_id'] ?? null;
        $parent_id = ($parent_id === '') ? null : $parent_id;

        $current_image = $_POST['current_image'] ?? null;

        try {
            $image_url = $this->handleImageUpload($_FILES['image'] ?? [], $current_image);
        } catch (Throwable $e) {
            $_SESSION['msg'] = '❌ ' . $e->getMessage();
            $_SESSION['msg_type'] = 'error';
            header('Location: index.php?admin=edit_category&id=' . (int)$id);
            exit;
        }

        // Model: update($id, $name, $parent_id, $image_url)
        $this->model->update($id, $name, $parent_id, $image_url);
        header('Location: index.php?admin=list_categories');
        exit;
    }

    // Xóa
    public function delete($id)
    {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            $_SESSION['msg'] = '❌ Thiếu hoặc sai ID danh mục';
            $_SESSION['msg_type'] = 'error';
            header('Location: index.php?admin=list_categories');
            exit;
        }

        if ($this->model->hasProducts($id)) {
            $_SESSION['msg'] = '❌ Không thể xóa! Danh mục đang có sản phẩm còn tồn kho.';
            $_SESSION['msg_type'] = 'error';
            header('Location: index.php?admin=list_categories');
            exit;
        }

        // Xóa ảnh file trước khi xóa DB
        if (method_exists($this->model, 'getImagePathById')) {
            $img = $this->model->getImagePathById($id);
            if ($img) {
                $imgFs = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . ltrim($img, '/');
                if (is_file($imgFs)) {
                    @unlink($imgFs);
                }
            }
        }

        // Xóa cascade DB
        $this->model->deleteCategoryCascade($id);

        $_SESSION['msg'] = '✅ Xóa danh mục và toàn bộ dữ liệu liên quan thành công!';
        $_SESSION['msg_type'] = 'success';
        header('Location: index.php?admin=list_categories');
        exit;
    }
}
