<?php

class OrdersController
{
    public function order()
    {
        $content = getContentPath('orders', 'orderList');

        view('admin/master', ['content' => $content]);
    }
}
