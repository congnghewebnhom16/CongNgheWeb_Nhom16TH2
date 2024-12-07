<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tin tức</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .edit-news-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
        }
        .preview-image {
            max-width: 300px;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 edit-news-container">
                <h2 class="text-center mb-4">Chỉnh sửa bài viết</h2>
                
                <form action="index.php?page=news&action=update" method="post" enctype="multipart/form-data">
                    <!-- ID tin tức (ẩn) -->
                    <input type="hidden" name="id" value="<?php echo $news['id']; ?>">
                    
                    <div class="form-group">
                        <label for="title">Tiêu đề bài viết</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="title" 
                            name="title" 
                            value="<?php echo htmlspecialchars($news['title']); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category_id">Danh mục</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="1" <?php echo ($news['category_id'] == 1) ? 'selected' : ''; ?>>
                                Tin KHCN và HTQT
                            </option>
                            <option value="2" <?php echo ($news['category_id'] == 2) ? 'selected' : ''; ?>>
                                Tin đào tạo
                            </option>
                            <option value="3" <?php echo ($news['category_id'] == 3) ? 'selected' : ''; ?>>
                                Tin tức chung
                            </option>
                            <option value="4" <?php echo ($news['category_id'] == 4) ? 'selected' : ''; ?>>
                                Tin công tác sinh viên
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Ảnh bìa</label>
                        <div class="custom-file">
                            <input 
                                type="file" 
                                class="custom-file-input" 
                                id="image" 
                                name="image" 
                                accept="image/*"
                            >
                            <label class="custom-file-label" for="image">
                                Chọn ảnh mới (để trống nếu không thay đổi)
                            </label>
                        </div>
                        
                        <?php if (!empty($news['image'])): ?>
                            <div class="mt-3">
                                <strong>Ảnh hiện tại:</strong>
                                <img 
                                    src="uploads/<?php echo htmlspecialchars($news['image']); ?>" 
                                    alt="Ảnh bài viết" 
                                    class="img-fluid preview-image"
                                >
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung bài viết</label>
                        <textarea 
                            class="form-control summernote" 
                            id="content" 
                            name="content" 
                            rows="10" 
                            required
                        ><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <a href="index.php?page=admin&action=dashboard" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Summernote Rich Text Editor
            $('.summernote').summernote({
                height: 300,
                placeholder: 'Nhập nội dung bài viết...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                ]
            });

            // Custom file input label
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
</body>
</html>