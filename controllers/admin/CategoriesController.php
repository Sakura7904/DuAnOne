<?php
require_once 'models/categories/CategoriesModel.php';
require_once 'database/function.php';

class CategoriesController
{
    private $model;

    public function __construct()
    {
        $db = new Database();
        $this->model = new CategoriesModel();
    }

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

        $this->model->insert($name, $parent_id);
        header('Location: index.php?act=list_categories');
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

        $this->model->update($id, $name, $parent_id);
        header('Location: index.php?act=list_categories');
        exit;
    }

    // Xóa
   public function delete($id) {
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        $_SESSION['msg'] = '❌ Thiếu hoặc sai ID danh mục';
        $_SESSION['msg_type'] = 'error';
        header('Location: index.php?act=list_categories');
        exit;
    }

    if ($this->model->hasProducts($id)) {
        $_SESSION['msg'] = '❌ Không thể xóa! Danh mục đang có sản phẩm còn tồn kho.';
        $_SESSION['msg_type'] = 'error';
        header('Location: index.php?act=list_categories');
        exit;
    }

    // Gọi xoá cascade
    $this->model->deleteCategoryCascade($id);
    $_SESSION['msg'] = '✅ Xóa danh mục và toàn bộ dữ liệu liên quan thành công!';
    $_SESSION['msg_type'] = 'success';
    header('Location: index.php?act=list_categories');
    exit;
}

}
