<?php
include "../config/config.php";

// Логирование запроса
$log_result = logPageRequest();
// Для отладки - временно
if (isset($_GET['debug'])) {
    echo "<!-- Log result: " . ($log_result ? "SUCCESS" : "FAILED") . " -->";
    $log_file = $_SERVER['DOCUMENT_ROOT'] . "/_log/log.txt";
    if (file_exists($log_file)) {
        echo "<!-- Log file exists, size: " . filesize($log_file) . " bytes -->";
    }
}

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
        /* if (!empty($_FILES)) {
            upload();
            header(/?page=bux);
        die();
        }*/

        $params['title'] = 'Бухи';
        $params['message'] = 'Файл загружен';
        $params['files'] = getFiles();
        _log($params, 'bux');
        break;

    case 'catalog':
        $params['title'] = 'Каталог';
        $params['catalog'] = getCatalog();
        break;

    case 'about':
        $params['title'] = 'about';
        $params['phone'] = 444333;
        break;

    case 'gallery':
        $params['title'] = 'Галерея';
        $params['error'] = '';
        $params['success'] = false;
        
        // Обработка загрузки файла
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_gallery'])) {
            $result = uploadImage('gallery_image', GALLERY_DIR, 5242880, 1200, 1200);
            
            if ($result['success']) {
                $params['success'] = true;
                _log('Загружено изображение: ' . $result['filename'], 'gallery');
                // Перезагрузить страницу чтобы показать новое изображение
                header("Location: " . BASE_URL . "index.php?page=gallery");
                die();
            } else {
                $params['error'] = $result['error'];
            }
        }
        
        // Построить галерею
        $params['gallery_html'] = buildGallery(GALLERY_DIR, GALLERY_URL, 150);
        break;

    case 'apicatalog':
        echo json_encode(getCatalog(), JSON_UNESCAPED_UNICODE);
        die();

    default:
        echo "404";
        die();
}

echo render($page, $params);


