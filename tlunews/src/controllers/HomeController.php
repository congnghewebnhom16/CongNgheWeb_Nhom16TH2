<?php
require_once 'src\models\Database.php'; // Kết nối Database
require_once 'src\models\News.php'; // Model News

class HomeController {
    private $newsModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection(); // Kết nối CSDL
        $this->newsModel = new News($db); // Khởi tạo model News
    }

    // Hiển thị danh sách bài viết
    public function index() { // Khởi tạo model News
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword']; // Lấy từ khóa tìm kiếm
            $newsList = $this->newsModel->searchNews($keyword); // Tìm kiếm bài viết
        } else {
            $newsList = $this->newsModel->getAllNews(); // Lấy tất cả tin tức
        }
        require_once 'src\views\home\index.php'; // Gửi dữ liệu tới view
    }
}
?>