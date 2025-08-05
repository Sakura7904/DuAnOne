<?php
class HomeController
{
    public function home()
    {
        $content = getContentPathClient('', 'home');
        //Nếu để trong thư mục ví dụ: views/admin/pages/products/list_products.php
        //Thì phải truyền tham số trùng với tên thư mục vào getContentPath('products, 'list_products')

        view('user/index', ['content' => $content]);
    }
}
