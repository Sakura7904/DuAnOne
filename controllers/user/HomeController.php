<?php
include_once "models/user/UserProductModel.php";

class HomeController
{
    public function home()
    {
        $productModel = new UserProductModel();
        $latestProducts = $productModel->getLatest(8);

        // Gắn màu sắc cho từng sản phẩm
        foreach ($latestProducts as &$product) {
            $product['colors'] = $productModel->getProductColors($product['id']);
        }
        unset($product);
        $content = getContentPathClient('', 'home');
        view('user/index', [
            'content' => $content,
            'latestProducts' => $latestProducts
        ]);
    }
}
