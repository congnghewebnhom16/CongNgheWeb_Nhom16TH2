<?php
class Database {
    private $host = "localhost";
    private $database_name = "tintuc";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->database_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("Kết nối cơ sở dữ liệu thất bại: " . $exception->getMessage(), 0);
            die("Không thể kết nối đến cơ sở dữ liệu. Vui lòng thử lại sau.");
        }

        if ($this->conn) {
            error_log("Kết nối thành công!"); // Log kiểm tra
        }

        return $this->conn;
    }

    public function closeConnection() {
        $this->conn = null;
    }
}