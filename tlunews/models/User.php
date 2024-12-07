<?php
require_once 'Database.php';

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function authenticate($username, $password)
    {
        try {
            // Tìm người dùng trong cơ sở dữ liệu theo username
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra nếu tìm thấy người dùng và so sánh mật khẩu
            if ($user && $password == $user['password']) {
                // Lưu thông tin vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // 0: Người dùng, 1: Quản trị viên

                return true;
            }

            return false;
        } catch (PDOException $exception) {
            error_log("Lỗi xác thực: " . $exception->getMessage());
            return false;
        }
    }

    public function getUserRole()
    {
        if (isset($_SESSION['role'])) {
            return $_SESSION['role'] == 1 ? 'admin' : 'user';
        }
        return 'guest'; // Chưa đăng nhập
    }
}
