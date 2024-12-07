<?php
require_once 'src/models/Database.php';
require_once 'src/models/News.php';

class NewsController
{
    private $newsModel;

    public function __construct()
    {
        // Bắt đầu session nếu chưa được bắt đầu
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Database();
        $db = $database->getConnection();
        $this->newsModel = new News($db);
    }

    // Hiển thị danh sách bài viết
    public function index()
    {
        $news = $this->newsModel->getAllNews();
        include 'src/views/news/detail.php';
    }

    // Lưu bài viết mới
    public function store()
    {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: index.php');
            exit;
        }

        // Validate dữ liệu
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);

        if (empty($title) || empty($content) || $category_id <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }

        $image = null;
        // Xử lý ảnh tải lên
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'uploads/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;

            // Kiểm tra và di chuyển file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = $fileName;
            } else {
                $_SESSION['error'] = 'Lỗi tải lên ảnh';
                header('Location: index.php?page=admin&action=dashboard');
                exit;
            }
        }

        // Lưu bài viết
        try {
            if ($this->newsModel->createNews($title, $content, $image, $category_id)) {
                $_SESSION['success'] = 'Thêm bài viết thành công';
                header('Location: index.php?page=admin&action=dashboard');
                exit;
            } else {
                throw new Exception('Không thể lưu bài viết');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Thêm bài viết thất bại: ' . $e->getMessage();
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }
    }

    // Cập nhật bài viết
    public function update()
    {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: index.php');
            exit;
        }

        // Validate ID
        if (!isset($_POST['id'])) {
            $_SESSION['error'] = 'Thiếu thông tin bài viết';
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }

        // Lấy và validate dữ liệu
        $id = intval($_POST['id']);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);

        if (empty($title) || empty($content) || $category_id <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: index.php?page=news&action=edit&id=' . $id);
            exit;
        }

        // Xử lý ảnh
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'uploads/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;

            // Kiểm tra và di chuyển file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = $fileName;
            } else {
                $_SESSION['error'] = 'Lỗi tải lên ảnh';
                header('Location: index.php?page=news&action=edit&id=' . $id);
                exit;
            }
        }

        // Cập nhật bài viết
        try {
            if ($this->newsModel->updateNews($id, $title, $content, $image, $category_id)) {
                $_SESSION['success'] = 'Cập nhật bài viết thành công';
                header('Location: index.php?page=admin&action=dashboard');
                exit;
            } else {
                throw new Exception('Không thể cập nhật bài viết');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Cập nhật bài viết thất bại: ' . $e->getMessage();
            header('Location: index.php?page=news&action=edit&id=' . $id);
            exit;
        }
    }

    // Xóa bài viết (giữ nguyên như bản gốc)
    public function delete($id)
    {
        // Fetch the news item to get the current image
        $newsItem = $this->newsModel->getNewsById($id);
    
        if ($newsItem) {
            // If an image exists, delete the file
            if (!empty($newsItem['image']) && file_exists('uploads/' . $newsItem['image'])) {
                unlink('uploads/' . $newsItem['image']);
            }
    
            // Delete the news item from the database
            if ($this->newsModel->deleteNews($id)) {
                $_SESSION['success_message'] = "Tin tức đã được xóa thành công!";
            } else {
                $_SESSION['error_message'] = "Không thể xóa tin tức. Vui lòng thử lại.";
            }
        } else {
            $_SESSION['error_message'] = "Tin tức không tồn tại!";
        }
    
        // Redirect back to the news list
        header('Location: index.php?page=admin&action=dashboard');
        exit;
    }

    // Chỉnh sửa bài viết
    public function edit($id) 
    {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: index.php');
            exit;
        }
    
        // Lấy thông tin bài viết
        $news = $this->newsModel ->getNewsById($id);
        
        if (!$news) {
            $_SESSION['error'] = 'Không tìm thấy bài viết';
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }
    
        // Hiển thị form chỉnh sửa
        include 'src/views/admin/news/edit.php';
    }

    // Tìm kiếm bài viết
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $news = $this->newsModel->searchNews($keyword);
        include 'views/news/list.php'; // Hiển thị danh sách kết quả tìm kiếm
    }
}