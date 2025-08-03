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
           if ($user && $user['email'] === $email && $user['password_hash'] === $password) {
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];

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


    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ?admin=loginForm");
        exit;
    }
}
