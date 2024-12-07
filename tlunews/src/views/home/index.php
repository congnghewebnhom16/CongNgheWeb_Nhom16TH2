<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức TLU - Trường Đại học Thủy Lợi</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
        }
        .news-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .category-badge {
            transition: background-color 0.3s ease;
        }
        .category-badge:hover {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header with Gradient -->
    <header class="bg-gradient-to-r from-blue-500 to-green-400 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <img src="./src/assets/logo.png" alt="Logo TLU" class="h-16 rounded-full">
                <nav class="space-x-6">
                    <a href="#" class="hover:text-yellow-200 transition duration-300">
                        <i class="fas fa-home mr-2"></i>Trang Chủ
                    </a>
                    <a href="#" class="hover:text-yellow-200 transition duration-300">
                        <i class="fas fa-newspaper mr-2"></i>Tin Mới
                    </a>
                    <a href="#" class="hover:text-yellow-200 transition duration-300">
                        <i class="fas fa-tags mr-2"></i>Danh Mục
                    </a>
                </nav>
            </div>

            <!-- Login/Manage Section -->
            <div class="flex items-center space-x-4">
                <?php if (!isset($_SESSION['role'])): ?>
                    <a href="index.php?page=admin&action=login" 
                       class="bg-white text-blue-600 px-5 py-2 rounded-full hover:bg-blue-50 transition duration-300 flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Đăng Nhập
                    </a>
                <?php elseif ($_SESSION['role'] == 1): ?>
                    <div class="flex items-center space-x-3">
                        <span class="text-white font-medium">
                            <i class="fas fa-user-shield mr-2"></i>Admin
                        </span>
                        <a href="index.php?page=admin&action=dashboard" 
                           class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition duration-300">
                            <i class="fas fa-tools mr-2"></i>Quản Lý
                        </a>
                        <a href="index.php?page=admin&action=logout" 
                           class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Đăng Xuất
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10">
        <!-- Search Section -->
        <div class="max-w-3xl mx-auto mb-12">
            <form method="POST" action="index.php?page=home&action=index" class="relative">
                <input type="text" name="keyword" 
                       placeholder="Tìm kiếm bài viết..." 
                       class="w-full px-6 py-4 rounded-full border-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg shadow-md">
                <button type="submit" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-500 text-white p-3 rounded-full hover:bg-blue-600 transition duration-300">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- News Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($newsList as $news): ?>
                <div class="news-card bg-white rounded-xl overflow-hidden">
                    <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>" 
                         class="w-full h-64 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="category-badge text-sm text-blue-600 bg-blue-100 px-3 py-1 rounded-full">
                                <?php echo htmlspecialchars($news['category_name']); ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                <?php echo date('d/m/Y', strtotime($news['created_at'])); ?>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold mb-3 text-gray-800 line-clamp-2 hover:text-blue-600 transition duration-300">
                            <?php echo htmlspecialchars($news['title']); ?>
                        </h2>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?php echo strip_tags(substr($news['content'], 0, 150)); ?>...
                        </p>
                        <a href="news_detail.php?id=<?php echo $news['id']; ?>" 
                           class="text-blue-500 hover:text-blue-700 font-semibold flex items-center group">
                            Đọc Thêm
                            <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition duration-300"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-6">
                <h3  class="text-2xl font-bold mb-4">Trường Đại học Thủy Lợi</h3>
            </div>
            <p>© 2023 Trang Tin Tức. Bản Quyền Thuộc Về Chúng Tôi.</p>
        </div>
    </footer>
</body>
</html>