<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; 
$action = isset($_GET['action']) ? $_GET['action'] : 'index'; 



// Tạo đường dẫn đến controller
$controllerPath = "src/controllers/" . ucfirst($page) . "Controller.php";

// Kiểm tra xem controller có tồn tại không
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerClass = ucfirst($page) . "Controller"; 
    $controller = new $controllerClass(); 

    // Kiểm tra action và gọi phương thức tương ứng
    if (method_exists($controller, $action)) {
        // Truyền ID nếu có
        if (in_array($action, ['delete', 'edit']) && isset($_GET['id'])) {
            $controller->$action($_GET['id']);
        } else {
            $controller->$action(); 
        }
    } else {
        // Thử với NewsController
        $newsControllerPath = "src/controllers/NewsController.php";
        if (file_exists($newsControllerPath)) {
            require_once $newsControllerPath;
            $newsController = new NewsController();
            
            if (method_exists($newsController, $action)) {
                // Truyền ID nếu có
                if (in_array($action, ['delete', 'edit']) && isset($_GET['id'])) {
                    $newsController->$action($_GET['id']);
                } else {
                    $newsController->$action(); 
                }
            } else {
                echo "Action không hợp lệ.";
            }
        } else {
            echo "Action không hợp lệ.";
        }
    }
} else {
    echo "Trang không tồn tại.";
}