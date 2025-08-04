<?php
include_once "models/admin/UserModel.php";

class AccountsController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $accounts = $this->userModel->getAll();
        $error = $_GET['error'] ?? '';
        $success = $_GET['success'] ?? '';

        // Biến dùng cho layout master.php
        $GLOBALS['accounts'] = $accounts;
        $GLOBALS['error'] = $error;
        $GLOBALS['success'] = $success;

        $content = 'views/admin/pages/accounts/list.php';
        include 'views/admin/master.php';
    }

    public function changeStatus($id)
    {
        $user = $this->userModel->getById($id);

        // Không cho phép thay đổi trạng thái của admin
        if ($user && $user['role'] === 'admin') {
            header('Location: index.php?admin=list_accounts&error=not_allow_admin');
            exit();
        }

        $status = $_POST['status'] ?? null;

        if ($status !== null) {
            $this->userModel->updateStatus($id, $status);
        }

        header('Location: index.php?admin=list_accounts');
        exit();
    }

    public function changeRole($id)
    {
        $user = $this->userModel->getById($id);

        // Kiểm tra user tồn tại
        if (!$user) {
            header('Location: index.php?admin=list_accounts&error=user_not_found');
            exit();
        }

        // Không cho thay đổi quyền nếu đã là admin
        if ($user['role'] === 'admin') {
            header('Location: index.php?admin=list_accounts&error=not_allow_admin');
            exit();
        }

        // Không cho nâng quyền nếu user đang không hoạt động
        if ($user['status'] !== 'active') {
            header('Location: index.php?admin=list_accounts&error=user_inactive');
            exit();
        }

        // Tiến hành nâng quyền
        $result = $this->userModel->updateRole($id, 'admin');

        if ($result) {
            header('Location: index.php?admin=list_accounts&success=role_updated');
            exit();
        } else {
            header('Location: index.php?admin=list_accounts&error=update_failed');
            exit();
        }
    }
}
