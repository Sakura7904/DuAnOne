<?php
include_once "models/user/UserProductModel.php";

class ProductByCategoryController
{
    public function showByCategory()
    {
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $sort = $_GET['sort'] ?? 'newest';
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 12;
        $offset = ($currentPage - 1) * $perPage;

        if ($categoryId <= 0) {
            die("Danh mục không hợp lệ!");
        }

        $productModel = new UserProductModel();
        $totalProducts = $productModel->countProductsByCategory($categoryId);
        $products = $productModel->getProductsByCategory($categoryId, $sort, $perPage, $offset);

        foreach ($products as &$product) {
            $product['colors'] = $productModel->getProductColors($product['id']);
        }
        unset($product);

        $categories = $productModel->getAllCategories();
        $currentCategory = $productModel->getCategoryById($categoryId);
        $content = getContentPathClient('', 'productsByCategory');

        $totalPages = ceil($totalProducts / $perPage);

        $content = getContentPathClient('', 'productsByCategory');
        view('user/index', [
            'content' => $content,
            'products' => $products,
            'categories' => $categories,
            'sort' => $sort,
            'categoryId' => $categoryId,
            'currentCategory' => $currentCategory,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,                  
            'totalProducts' => $totalProducts       
        ]);
    }
}
