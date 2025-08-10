<?php
require_once 'models/user/AuthClientModel.php';

class AuthClientController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthClientModel();
    }

    /**
     * Hiển thị trang đăng nhập/đăng ký
     */
    public function handleLogin()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validate dữ liệu đầu vào
            if (empty($username)) {
                $errors['username'] = 'Vui lòng nhập email hoặc số điện thoại';
            }

            if (empty($password)) {
                $errors['password'] = 'Vui lòng nhập mật khẩu';
            }

            // Nếu không có lỗi thì xử lý đăng nhập
            if (empty($errors)) {
                $user = $this->authModel->login($username, $password);

                if ($user) {
                    // Lưu thông tin user vào session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user'] = $user;

                    // Chuyển hướng theo role
                    if ($user['role'] === 'admin') {
                        header('Location: ?admin=dashboard');
                        exit;
                    } else {
                        header('Location: ?user=home');
                        exit;
                    }
                } else {
                    $errors['login'] = 'Email/Số điện thoại hoặc mật khẩu không đúng';
                }
            }

            // Nếu có lỗi, lưu vào session và redirect
            if (!empty($errors)) {
                $_SESSION['login_errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ?user=login');
                exit;
            }
        }

        // Hiển thị trang login bình thường
        $this->login();
    }

    /**
     * Hiển thị trang đăng nhập/đăng ký
     */
    /**
     * Hiển thị trang đăng nhập/đăng ký
     */
    public function login()
    {
        // Lấy errors từ session (cả login và register)
        $loginErrors = $_SESSION['login_errors'] ?? [];
        $registerErrors = $_SESSION['register_errors'] ?? [];
        $oldInput = $_SESSION['old_input'] ?? [];

        // Merge errors lại
        $errors = array_merge($loginErrors, $registerErrors);

        // Clear khỏi session sau khi lấy
        unset($_SESSION['login_errors']);
        unset($_SESSION['register_errors']);
        unset($_SESSION['old_input']);

        $content = getContentPathClient('user', 'login-register');
        view('user/index', [
            'content' => $content,
            'data' => [                    // ✅ Truyền qua 'data'
                'errors' => $errors,
                'oldInput' => $oldInput
            ]
        ]);
    }

    /**
     * Xử lý đăng ký (form submit truyền thống)
     */
    public function handleRegister()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mobile = trim($_POST['mobile'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fullName = trim($_POST['fullName'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirmPassword'] ?? '');

            // Validate dữ liệu đầu vào (check trống - đang hoạt động)

            if (empty($mobile)) {
                $errors['mobile'] = 'Vui lòng nhập số điện thoại';
            } elseif ($this->authModel->phoneExists($mobile)) {
                $errors['mobile'] = 'Số điện thoại đã được sử dụng';
            }

            if (empty($email)) {
                $errors['email'] = 'Vui lòng nhập email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ';
            } elseif ($this->authModel->emailExists($email)) {
                $errors['email'] = 'Email đã được sử dụng';
            }

            if (empty($fullName)) {
                $errors['fullName'] = 'Vui lòng nhập họ tên';
            }

            if (empty($password)) {
                $errors['password_register'] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors['password_register'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if ($password !== $confirmPassword) {
                $errors['confirmPassword'] = 'Mật khẩu xác nhận không khớp';
            }

            // ✅ CHỈ KHI VALIDATION CƠ BẢN OK THÌ MỚI CHECK DUPLICATE
            if (empty($errors)) {
                $result = $this->authModel->register($fullName, $email, $mobile, $password);

                if ($result['success']) {
                    $_SESSION['success'] = $result['message'];
                    header('Location: ?user=login');
                    exit;
                } else {
                    // ✅ XỬ LÝ CẢ SINGLE MESSAGE VÀ MULTIPLE ERRORS
                    if (isset($result['errors']) && is_array($result['errors'])) {
                        // Multiple errors từ model
                        $errors = array_merge($errors, $result['errors']);
                    } else {
                        // Single message từ model
                        $errors['register'] = $result['message'];
                    }
                }
            }

            // Lưu errors và redirect
            if (!empty($errors)) {
                $_SESSION['register_errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ?user=login');
                exit;
            }
        }

        $this->login();
    }



    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_destroy();
        header('Location: ?user=home');
        exit;
    }

    /**
     * Hiển thị trang đổi mật khẩu
     */
    public function changePassword()
    {
        // Kiểm tra đã đăng nhập chưa
        if (!$this->authModel->isLoggedIn()) {
            header('Location: ?user=login');
            exit;
        }

        // Lấy errors và old input từ session
        $errors = $_SESSION['change_password_errors'] ?? [];
        $oldInput = $_SESSION['old_input'] ?? [];

        // Clear khỏi session sau khi lấy
        unset($_SESSION['change_password_errors']);
        unset($_SESSION['old_input']);

        $currentUser = $this->authModel->getCurrentUser();

        $content = getContentPathClient('user', 'change-password');
        view('user/index', [
            'content' => $content,
            'data' => [
                'user' => $currentUser,
                'errors' => $errors,
                'oldInput' => $oldInput
            ]
        ]);
    }

    /**
     * Xử lý đổi mật khẩu
     */
    public function handleChangePassword()
    {
        // Kiểm tra đã đăng nhập chưa
        if (!$this->authModel->isLoggedIn()) {
            header('Location: ?user=login');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldPassword = trim($_POST['oldpassword'] ?? '');
            $newPassword = trim($_POST['newpassword'] ?? '');
            $rePassword = trim($_POST['repassword'] ?? '');

            $currentUser = $this->authModel->getCurrentUser();
            $userId = $currentUser['id'];

            // Validate dữ liệu đầu vào
            if (empty($oldPassword)) {
                $errors['oldpassword'] = 'Vui lòng nhập mật khẩu cũ';
            }

            if (empty($newPassword)) {
                $errors['newpassword'] = 'Vui lòng nhập mật khẩu mới';
            } elseif (strlen($newPassword) < 6) {
                $errors['newpassword'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            }

            if (empty($rePassword)) {
                $errors['repassword'] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($newPassword !== $rePassword) {
                $errors['repassword'] = 'Mật khẩu xác nhận không khớp';
            }

            // Nếu validation cơ bản OK thì xử lý đổi mật khẩu
            if (empty($errors)) {
                $result = $this->authModel->changePassword($userId, $oldPassword, $newPassword);

                if ($result['success']) {
                    $_SESSION['success'] = $result['message'];
                    header('Location: ?user=changePassword');
                    exit;
                } else {
                    $errors['oldpassword'] = $result['message'];
                }
            }

            // Nếu có lỗi, lưu vào session và redirect
            if (!empty($errors)) {
                $_SESSION['change_password_errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ?user=changePassword');
                exit;
            }
        }

        $this->changePassword();
    }


    /**
     * Hiển thị trang chỉnh sửa profile
     */
    public function profile()
    {
        // Kiểm tra đã đăng nhập chưa
        if (!$this->authModel->isLoggedIn()) {
            header('Location: ?user=login');
            exit;
        }

        // Lấy errors và old input từ session
        $errors = $_SESSION['profile_errors'] ?? [];
        $oldInput = $_SESSION['old_input'] ?? [];

        // Clear khỏi session sau khi lấy
        unset($_SESSION['profile_errors']);
        unset($_SESSION['old_input']);

        $currentUser = $this->authModel->getCurrentUser();

        $content = getContentPathClient('user', 'profile');
        view('user/index', [
            'content' => $content,
            'data' => [
                'user' => $currentUser,
                'errors' => $errors,
                'oldInput' => $oldInput
            ]
        ]);
    }


    /**
     * Xử lý cập nhật profile
     */
    public function handleUpdateProfile()
    {
        // Kiểm tra đã đăng nhập chưa
        if (!$this->authModel->isLoggedIn()) {
            header('Location: ?user=login');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['fullName'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');

            $currentUser = $this->authModel->getCurrentUser();
            $userId = $currentUser['id'];

            // Validate dữ liệu đầu vào TRƯỚC
            if (empty($fullName)) {
                $errors['fullName'] = 'Vui lòng nhập họ tên';
            }

            if (empty($email)) {
                $errors['email'] = 'Vui lòng nhập email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ';
            }

            // Nếu validation cơ bản OK thì check duplicate
            if (empty($errors)) {
                $result = $this->authModel->updateProfile($userId, $fullName, $email, $mobile);

                if ($result['success']) {
                    // Cập nhật lại thông tin trong session
                    $updatedUser = $this->authModel->getUserById($userId);
                    $_SESSION['user'] = $updatedUser;

                    $_SESSION['success'] = $result['message'];
                    header('Location: ?user=profile');
                    exit;
                } else {
                    // Xử lý multiple errors từ model
                    if (isset($result['errors'])) {
                        $errors = array_merge($errors, $result['errors']);
                    } else {
                        $errors['update'] = $result['message'];
                    }
                }
            }

            // Nếu có lỗi, lưu vào session và redirect
            if (!empty($errors)) {
                $_SESSION['profile_errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header('Location: ?user=profile');
                exit;
            }
        }

        $this->profile();
    }
}
