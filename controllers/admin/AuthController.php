<?php
include_once "models/admin/AuthModel.php";
class AuthController
{

    public function loginForm()
    {
        include "views/admin/pages/auth/login.php";
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password_hash'] ?? '';

            $authModel = new AuthModel();
            $user = $authModel->findByEmail($email);
            // $password là mật khẩu người dùng nhập vào (plain text)
            // $user['password_hash'] là giá trị đã lưu trong DB bằng password_hash(...)

            if ($user && password_verify($password, $user['password_hash'])) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true); // chống session fixation

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['email']     = $user['email'];

                if ($user['role'] === 'admin') {
                    header("Location: index.php?admin=dashboard");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
            } else {
                $error = "Sai email hoặc mật khẩu!";
                include "views/admin/pages/auth/login.php";
            }
        }
    }


    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: ?admin=loginForm");
        exit;
    }
}
