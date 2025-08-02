<?php

class DashboardController
{
    public function index()
    {
        $content = getContentPath('', 'dashboard');
        //Nếu để trong thư mục ví dụ: views/admin/pages/products/list_products.php
        //Thì phải truyền tham số trùng với tên thư mục vào getContentPath('products, 'list_products')

        view('admin/master', ['content' => $content]);
    }
}
