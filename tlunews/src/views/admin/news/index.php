<?php
// Kiểm tra nếu có thông báo từ session
if (!empty($_SESSION['success_message'])) {
    echo "<p style='color: green'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    echo "<p style='color: red'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
}
?>

<h1>Danh sách bài viết</h1>

<!-- Nút thêm bài viết -->
<a href="index.php?page=admin&action=add" style="margin-bottom: 10px; display: inline-block; background-color: #4CAF50; color: white; padding: 10px; text-decoration: none;">Thêm bài viết</a>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Danh mục</th>
            <th>Ảnh</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($newsList)): ?>
            <?php foreach ($newsList as $news): ?>
                <tr>
                    <td><?php echo $news['id']; ?></td>
                    <td><?php echo htmlspecialchars($news['title']); ?></td>
                    <td><?php echo htmlspecialchars($news['category_name']); ?></td>
                    <td>
                        <?php if (!empty($news['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="Ảnh bài viết" style="max-width: 100px; height: auto;">
                        <?php else: ?>
                            Không có ảnh
                        <?php endif; ?>
                    </td>
                    <td><?php echo $news['created_at']; ?></td>
                    <td>
                        <a href="index.php?page=admin&action=edit&id=<?php echo $news['id']; ?>" style="background-color: #2196F3; color: white; padding: 5px 10px; text-decoration: none;">Sửa</a>
                        <a href="index.php?page=admin&action=delete&id=<?php echo $news['id']; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')" 
                           style="background-color: #f44336; color: white; padding: 5px 10px; text-decoration: none;">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">Không có bài viết nào!</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
