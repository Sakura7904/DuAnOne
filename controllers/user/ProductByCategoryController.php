<?php
include_once "models/user/UserProductModel.php";

class ProductByCategoryController
{
    public function showByCategory()
    {
        $categoryId   = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $sort         = $_GET['sort'] ?? 'newest';
        // ĐỌC THAM SỐ TRANG TỪ 'pg' (không phải 'page')
        $currentPage  = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
        $perPage      = 12;
        $offset       = ($currentPage - 1) * $perPage;
        $keyword      = trim($_GET['keyword'] ?? '');

        if ($categoryId <= 0) {
            die("Danh mục không hợp lệ!");
        }

        $productModel = new UserProductModel();

        if ($keyword !== '') {
            // TÌM KIẾM: không phân trang
            $products       = $productModel->searchProduct($keyword); 
            $totalProducts  = count($products);
            $totalPages     = 1;
            $currentPage    = 1;
        } else {
            // DANH MỤC
            $totalProducts  = $productModel->countProductsByCategory($categoryId);
            $totalPages     = (int)ceil($totalProducts / $perPage);

            // Clamp & nạp lại nếu cần
            if ($totalPages === 0) {
                $currentPage = 1;
                $offset = 0;
                $products = [];
            } elseif ($currentPage > $totalPages) {
                $currentPage = $totalPages;
                $offset = ($currentPage - 1) * $perPage;
                $products = $productModel->getProductsByCategory($categoryId, $sort, $perPage, $offset);
            } else {
                $products = $productModel->getProductsByCategory($categoryId, $sort, $perPage, $offset);
            }
        }

        foreach ($products as &$product) {
            $product['colors'] = $productModel->getProductColors($product['id']);
        }
        unset($product);

        $categories       = $productModel->getAllCategories();
        $currentCategory  = $productModel->getCategoryById($categoryId);

        $content = getContentPathClient('', 'productsByCategory');
        view('user/index', [
            'content'         => $content,
            'products'        => $products,
            'categories'      => $categories,
            'sort'            => $sort,
            'categoryId'      => $categoryId,
            'currentCategory' => $currentCategory,
            'totalPages'      => $totalPages,
            'currentPage'     => $currentPage,
            'perPage'         => $perPage,
            'totalProducts'   => $totalProducts,
            'keyword'         => $keyword, 
        ]);
    }
}
