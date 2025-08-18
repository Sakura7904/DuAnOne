<?php
include_once "models/user/UserProductModel.php";

class ProductByCategoryController
{
    public function showByCategory()
    {
        $categoryId  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $sort        = $_GET['sort'] ?? 'newest';
        $currentPage = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
        $perPage     = 12;
        $offset      = ($currentPage - 1) * $perPage;
        $keyword     = trim($_GET['keyword'] ?? '');

        if ($categoryId <= 0) {
            die("Danh mục không hợp lệ!");
        }

        // Chốt sort hợp lệ
        $allowedSort = ['newest', 'low_to_high', 'high_to_low'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'newest';
        }

        $model = new UserProductModel();

        if ($keyword !== '') {
            // TÌM KIẾM (đã lọc biến thể hợp lệ ở Model)
            $products      = $model->searchProduct($keyword);
            $totalProducts = count($products);
            $totalPages    = 1;
            $currentPage   = 1;
        } else {
            // DANH MỤC (đã lọc biến thể hợp lệ ở Model)
            $totalProducts = $model->countProductsByCategory($categoryId);
            $totalPages    = max(1, (int)ceil($totalProducts / $perPage));

            // Clamp lại currentPage nếu vượt
            if ($currentPage > $totalPages) {
                $currentPage = $totalPages;
                $offset      = ($currentPage - 1) * $perPage;
            }

            $products = ($totalProducts > 0)
                ? $model->getProductsByCategory($categoryId, $sort, $perPage, $offset)
                : [];
        }

        // Chuẩn hoá màu sắc: color_options
        if (!empty($products)) {
            foreach ($products as &$product) {
                // getProductColors trả về: color_name, color_code
                $colors = $model->getProductColors((int)$product['id']);
                $product['color_options'] = array_map(function ($c) {
                    return [
                        'name'       => $c['color_name'] ?? '',
                        'color_code' => $c['color_code'] ?? null,
                    ];
                }, $colors);
                unset($product['colors']); // nếu có cột này từ nơi khác
            }
            unset($product);
        }

        // Data cho view
        $categories      = $model->getAllCategories();
        $currentCategory = $model->getCategoryById($categoryId);

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
