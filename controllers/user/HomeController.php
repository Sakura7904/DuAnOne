<?php
include_once "models/user/UserProductModel.php";

class HomeController
{
    public function home()
    {   
        $productModel = new UserProductModel();

        // Lấy 8 sản phẩm mới nhất
        $latestProducts = $productModel->getLatest(8);

        // Gắn màu sắc cho từng sản phẩm
        foreach ($latestProducts as &$product) {
            $product['colors'] = $productModel->getProductColors($product['id']);
        }
        unset($product);

        // Lấy danh sách danh mục
        $categories = $productModel->getAllCategories();
        $categories = $productModel->getCategoriesWithChildren();


        $content = getContentPathClient('', 'home');
        view('user/index', [
            'content' => $content,
            'latestProducts' => $latestProducts,
            'categories' => $categories
        ]);
    }
}
