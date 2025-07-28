<?php

class DashboardController
{
    public function index()
    {
        $content = getContentPath('', 'dashboard');

        view('admin/master', ['content' => $content]);
    }
}
