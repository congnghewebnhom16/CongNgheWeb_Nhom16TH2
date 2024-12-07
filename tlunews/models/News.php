<?php
require_once 'Database.php';

class News
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Thêm bài viết mới
    public function createNews($title, $content, $image, $category_id)
    {
        try {
            $query = "INSERT INTO news (title, content, image, created_at, category_id) 
                      VALUES (:title, :content, :image, NOW(), :category_id)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':category_id', $category_id);

            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log("Lỗi thêm bài viết: " . $exception->getMessage());
            return false;
        }
    }

    // Sửa bài viết
    public function updateNews($id, $title, $content, $image = null, $category_id)
    {
        try {
            $query = "UPDATE news 
                      SET title = :title, 
                          content = :content, 
                          category_id = :category_id";
            // Nếu có ảnh mới, cập nhật thêm
            if ($image) {
                $query .= ", image = :image";
            }
            $query .= " WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':category_id', $category_id);

            if ($image) {
                $stmt->bindParam(':image', $image);
            }

            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log("Lỗi sửa bài viết: " . $exception->getMessage());
            return false;
        }
    }

    // Xóa bài viết
    public function deleteNews($id)
    {
        try {
            $query = "DELETE FROM news WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log("Lỗi xóa bài viết: " . $exception->getMessage());
            return false;
        }
    }

    // Lấy tất cả bài viết
    public function getAllNews()
    {
        try {
            $query = "SELECT n.*, c.name as category_name 
                      FROM news n 
                      JOIN categories c ON n.category_id = c.id 
                      ORDER BY n.created_at DESC";
            $stmt = $this->db->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log("Lỗi lấy danh sách bài viết: " . $exception->getMessage());
            return [];
        }
    }

    // Lấy bài viết theo ID
    public function getNewsById($id)
    {
        try {
            $query = "SELECT * FROM news WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log("Lỗi lấy bài viết theo ID: " . $exception->getMessage());
            return false;
        }
    }

    // Tìm kiếm bài viết theo từ khóa
    public function searchNews($keyword)
    {
        try {
            $query = "SELECT n.*, c.name as category_name 
                    FROM news n
                    JOIN categories c ON n.category_id = c.id 
                    WHERE n.title LIKE :keyword OR n.content LIKE :keyword 
                    ORDER BY n.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':keyword', '%' . $keyword . '%'); // Thêm ký tự đại diện cho tìm kiếm
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách bài viết phù hợp
        } catch (PDOException $exception) {
            error_log("Lỗi tìm kiếm bài viết: " . $exception->getMessage());
            return [];
        }
    }
}
