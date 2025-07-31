<?php
require_once 'models/categories/CategoriesModel.php';
require_once 'database/function.php'; 

class CategoriesController {
    private $model;

    public function __construct() {
        $db = new Database();
        $this->model = new CategoriesModel($db->pdo); 
    }

    // Danh sách
    public function index() {
        $categories = $this->model->getAll();
        $allCategories = array_column($categories, 'name', 'id');
        view('admin/master', [
            'content' => 'views/admin/pages/categories/list.php',
            'categories' => $categories,
            'allCategories' => $allCategories
        ]);
    }

    // Thêm
    public function create() {
        $parent_categories = $this->model->getAll();
        view('admin/master', [
            'content' => 'views/admin/pages/categories/create.php',
            'parent_categories' => $parent_categories
        ]);
    }

    // Lưu thêm
    public function store() {
        $name = $_POST['name'] ?? '';
        $parent_id = $_POST['parent_id'] ?? null;
        $this->model->insert($name, $parent_id);
        header('Location: index.php?act=list_categories');
        exit;
    }

    // Sửa
    public function edit($id) {
        $category = $this->model->getById($id);
        $parent_categories = $this->model->getAll();
        view('admin/master', [
            'content' => 'views/admin/pages/categories/edit.php',
            'category' => $category,
            'parent_categories' => $parent_categories
        ]);
    }

    // Cập nhật
    public function update($id) {
        $name = $_POST['name'] ?? '';
        $parent_id = $_POST['parent_id'] ?? null;
        $this->model->update($id, $name, $parent_id);
        header('Location: index.php?act=list_categories');
        exit;
    }

    // Xóa
    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?act=list_categories');
        exit;
    }
}
