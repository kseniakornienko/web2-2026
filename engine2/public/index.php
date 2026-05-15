<?php
include "../config/config.php";

$log_result = logPageRequest();

handleFeedbackAjax();

$page = 'index';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$params = [];

switch ($page) {
    case 'index':
        $params['title'] = 'Главная';
        break;

    case 'bux':
        $params['title'] = 'Бухи';
        $params['message'] = 'Файл загружен';
        $params['files'] = getFiles();
        _log($params, 'bux');
        break;

    case 'catalog':
        $params['title'] = 'Каталог товаров';
        $params['products'] = getAllProducts();
        break;
        
    case 'product':
        $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = getProductById($product_id);
        
        if (!$product) {
            echo "404 - Товар не найден";
            die();
        }
        
        $params['title'] = $product['name'];
        $params['product'] = $product;
        $params['reviews'] = getReviewsByProductId($product_id);
        $params['rating_info'] = getAverageRating($product_id);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_review'])) {
            $result = doFeedbackAction('create', [
                'product_id' => $product_id,
                'author' => $_POST['author'] ?? '',
                'rating' => $_POST['rating'] ?? 0,
                'comment' => $_POST['comment'] ?? ''
            ]);
            
            if ($result['success']) {
                $params['review_success'] = $result['message'];
                $params['reviews'] = getReviewsByProductId($product_id);
                $params['rating_info'] = getAverageRating($product_id);
            } else {
                $params['review_error'] = $result['message'];
            }
        }
        break;

    case 'about':
        $params['title'] = 'about';
        $params['phone'] = 444333;
        break;

    case 'gallery':
        $params['title'] = 'Галерея';
        $params['error'] = '';
        $params['success'] = false;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_gallery'])) {
            $result = uploadImage('gallery_image', GALLERY_DIR, 5242880, 1200, 1200);
            
            if ($result['success']) {
                $params['success'] = true;
                _log('Загружено изображение: ' . $result['filename'], 'gallery');
                header("Location: " . BASE_URL . "index.php?page=gallery");
                die();
            } else {
                $params['error'] = $result['error'];
            }
        }
        
        $params['gallery_html'] = buildGallery(GALLERY_DIR, GALLERY_URL, 150);
        break;

    case 'apicatalog':
        echo json_encode(getCatalog(), JSON_UNESCAPED_UNICODE);
        die();

    case 'admin_reviews':
        $params['title'] = 'Управление отзывами';
        break;

    default:
        echo "404";
        die();
}

echo render($page, $params);
?>