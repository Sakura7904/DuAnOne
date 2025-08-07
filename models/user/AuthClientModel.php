<?php
class AuthClientModel
{
    public $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Đăng nhập bằng email hoặc số điện thoại
     */
    public function login($identifier, $password)
    {
        // Kiểm tra xem identifier là email hay số điện thoại
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // Đăng nhập bằng email
            $sql = "SELECT id, full_name, email, phone_number, password_hash, role, status 
                    FROM users WHERE email = ? AND status = 'active'";
        } else {
            // Đăng nhập bằng số điện thoại
            $sql = "SELECT id, full_name, email, phone_number, password_hash, role, status 
                    FROM users WHERE phone_number = ? AND status = 'active'";
        }

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra user tồn tại và mật khẩu đúng
        if ($user && $this->verifyPassword($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    /**
     * Đăng ký tài khoản mới
     */
    public function register($fullName, $email, $phoneNumber, $password, $address = null)
    {
        $errors = [];
        // Check email duplicate
        if ($this->emailExists($email)) {
            $errors['email'] = 'Email đã được sử dụng';
            echo "DEBUG: Email exists!<br>";
        }

        // Check phone duplicate
        if ($phoneNumber && $this->phoneExists($phoneNumber)) {
            $errors['mobile'] = 'Số điện thoại đã được sử dụng';
            echo "DEBUG: Phone exists!<br>";
        }

        // Return multiple errors nếu có
        if (!empty($errors)) {
            echo "DEBUG: Returning errors: " . json_encode($errors) . "<br>";
            return ['success' => false, 'errors' => $errors];
        }

        // Tiến hành insert...
        $sql = "INSERT INTO users (full_name, email, phone_number, password_hash, address, role, status) 
            VALUES (?, ?, ?, ?, ?, 'customer', 'active')";

        $stmt = $this->db->pdo->prepare($sql);
        $result = $stmt->execute([
            $fullName,
            $email,
            $phoneNumber,
            $this->hashPassword($password),
            $address
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Đăng ký thành công'];
        }

        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        // Lấy thông tin user hiện tại
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản'];
        }

        // Kiểm tra mật khẩu hiện tại
        if (!$this->verifyPassword($currentPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Mật khẩu cũ không đúng'];
        }

        // Cập nhật mật khẩu mới
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $result = $stmt->execute([
            $this->hashPassword($newPassword),
            $userId
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
        }

        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }


    /**
     * Kiểm tra email đã tồn tại
     */
    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại
     */
    public function phoneExists($phone)
    {
        $sql = "SELECT id FROM users WHERE phone_number = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch() !== false;
    }

    /**
     * Mã hóa mật khẩu
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Xác thực mật khẩu
     */
    public function verifyPassword($password, $hash)
    {
        // Nếu password_hash trong DB chưa được hash properly (như dữ liệu mẫu)
        // thì so sánh trực tiếp, ngược lại dùng password_verify
        if (strlen($hash) < 60) { // Hash thật thường dài hơn 60 ký tự
            return $password === $hash;
        }
        return password_verify($password, $hash);
    }



    /**
     * Kiểm tra email đã tồn tại (trừ user hiện tại)
     */
    public function emailExistsExcept($email, $userId)
    {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$email, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại (trừ user hiện tại)
     */
    public function phoneExistsExcept($phone, $userId)
    {
        $sql = "SELECT id FROM users WHERE phone_number = ? AND id != ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$phone, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Cập nhật thông tin profile người dùng
     */
    public function updateProfile($userId, $fullName, $email, $phoneNumber)
    {
        $errors = [];

        // Kiểm tra tất cả lỗi trước
        if ($this->emailExistsExcept($email, $userId)) {
            $errors['email'] = 'Email này đã có người sử dụng';
        }

        if ($phoneNumber && $this->phoneExistsExcept($phoneNumber, $userId)) {
            $errors['mobile'] = 'Số điện thoại này đã có người sử dụng';
        }

        // Nếu có bất kỳ lỗi nào, return tất cả
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Không có lỗi → Tiến hành update
        $sql = "UPDATE users SET full_name = ?, email = ?, phone_number = ? WHERE id = ?";

        $stmt = $this->db->pdo->prepare($sql);
        $result = $stmt->execute([
            $fullName,
            $email,
            $phoneNumber,
            $userId
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Cập nhật thông tin thành công'];
        }

        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }


    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById($userId)
    {
        $sql = "SELECT id, full_name, email, phone_number, role, status FROM users WHERE id = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public function getCurrentUser()
    {
        return $_SESSION['user'] ?? null;
    }
}
