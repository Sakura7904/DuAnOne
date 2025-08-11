<?php
include_once "models/user/PurchaseClientModel.php";

class PurchaseController
{
    public function purchase()
    {
        $content = getContentPathClient('', 'myPurchase');
        view('user/index', ['content' => $content]);
    }
}
