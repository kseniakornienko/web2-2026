<?php

function render($page, $params = []) {
    return renderTemplate(LAYOUTS_DIR . 'main', [
        'title' => $params['title'],
        'menu' => renderTemplate('menu', $params),
        'content' => renderTemplate($page, $params)
    ]);
}


//$params = ['menu' => 'код меню', 'catalog' => ['чай'], 'content' => 'Код подшаблона']
function renderTemplate($page, $params = []) {

    /*    foreach ($params as $key => $value) {
            $$key = $value;
        }*/
    extract($params);

    ob_start();
    include TEMPLATES_DIR . $page . ".php";
    return ob_get_clean();
}

function isGdExtensionEnabled() {
    return function_exists('gd_info') && !empty(gd_info());
}

// Получить список изображений из папки
function getGalleryImages($directory) {
    $path = realpath($directory);
    
    if (!is_dir($path)) {
        return [];
    }
    
    $images = [];
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($file_extension, $allowed_types)) {
                $images[] = $file;
            }
        }
    }
    
    return $images;
}

// Построить HTML галереи
function buildGallery($directory, $base_url, $thumbnail_width = 150) {
    $images = getGalleryImages($directory);
    
    $html = '<div class="gallery">';
    foreach ($images as $image) {
        // Пропустить файлы миниатюр - они не нужны в основном списке
        if (strpos($image, 'thumb_') === 0) {
            continue;
        }
        
        $thumbnail_file = 'thumb_' . $image;
        $thumbnail_exists = file_exists($directory . $thumbnail_file);
        
        // Использовать миниатюру если она существует, иначе оригинал
        $display_image = $thumbnail_exists ? $thumbnail_file : $image;
        
        $html .= '<a href="' . htmlspecialchars($base_url . $image) . '" target="_blank" class="gallery-item">';
        $html .= '<img src="' . htmlspecialchars($base_url . $display_image) . '" width="' . $thumbnail_width . '" alt="' . htmlspecialchars($image) . '">';
        $html .= '</a>';
    }
    $html .= '</div>';
    
    return $html;
}

// Загрузка и обработка изображения
function uploadImage($file_input_name, $destination_dir, $max_size = 5242880, $max_width = 1200, $max_height = 1200) {
    $errors = [];
    
    // Проверка загружен ли файл
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Ошибка при загрузке файла'];
    }
    
    $file = $_FILES[$file_input_name];
    
    // Проверка типа файла
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed_types)) {
        return ['success' => false, 'error' => 'Недопустимый тип файла.允допускаются: JPG, PNG, GIF, WebP'];
    }
    
    // Проверка размера файла
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'Размер файла превышает ' . ($max_size / 1048576) . ' МБ'];
    }
    
    // Создать директорию если её нет
    if (!is_dir($destination_dir)) {
        mkdir($destination_dir, 0755, true);
    }
    
    // Генерировать уникальное имя файла
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $filepath = $destination_dir . '/' . $filename;
    
    // Переместить загруженный файл
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'error' => 'Не удалось сохранить файл'];
    }
    
    // Проверить размер изображения и изменить если необходимо
    $image_info = getimagesize($filepath);
    if ($image_info === false) {
        unlink($filepath);
        return ['success' => false, 'error' => 'Невалидное изображение'];
    }
    
    if (!isGdExtensionEnabled()) {
        unlink($filepath);
        return ['success' => false, 'error' => 'Расширение GD не включено. Включите php_gd2 в php.ini и перезапустите Apache.'];
    }

    $width = $image_info[0];
    $height = $image_info[1];
    
    // Ресайз если размер превышает максимальный
    if ($width > $max_width || $height > $max_height) {
        if (!resizeImage($filepath, $filepath, $max_width, $max_height, $image_info[2])) {
            unlink($filepath);
            return ['success' => false, 'error' => 'Не удалось обработать изображение'];
        }
    }
    
    // Создать миниатюру
    $thumbnail_path = $destination_dir . '/thumb_' . $filename;
    if (!createThumbnail($filepath, $thumbnail_path, 150, 150, $image_info[2])) {
        // Если не удалось создать миниатюру, просто продолжаем с оригиналом
        _log('Не удалось создать миниатюру для ' . $filename, 'gallery');
    }
    
    return ['success' => true, 'filename' => $filename];
}

// Изменить размер изображения
function resizeImage($source_path, $dest_path, $max_width, $max_height, $image_type) {
    $image_info = getimagesize($source_path);
    $width = $image_info[0];
    $height = $image_info[1];
    
    // Вычислить новый размер с сохранением соотношения сторон
    $ratio = $width / $height;
    
    if ($width > $max_width) {
        $width = $max_width;
        $height = round($width / $ratio);
    }
    
    if ($height > $max_height) {
        $height = $max_height;
        $width = round($height * $ratio);
    }
    
    if (!isGdExtensionEnabled()) {
        return false;
    }

    // Создать новое изображение
    $new_image = imagecreatetruecolor($width, $height);
    
    // Загрузить исходное изображение
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($source_path);
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($source_path);
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    if (!$source) {
        return false;
    }
    
    // Копировать и переразмерить изображение
    imagecopyresampled($new_image, $source, 0, 0, 0, 0, $width, $height, $image_info[0], $image_info[1]);
    
    // Сохранить новое изображение
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            imagejpeg($new_image, $dest_path, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($new_image, $dest_path);
            break;
        case IMAGETYPE_GIF:
            imagegif($new_image, $dest_path);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($new_image, $dest_path, 90);
            break;
    }
    
    imagedestroy($new_image);
    imagedestroy($source);
    
    return true;
}

// Создать миниатюру
function createThumbnail($source_path, $dest_path, $thumb_width, $thumb_height, $image_type) {
    $image_info = getimagesize($source_path);
    $width = $image_info[0];
    $height = $image_info[1];
    
    // Вычислить соотношение сторон для обрезки
    $ratio_original = $width / $height;
    $ratio_thumb = $thumb_width / $thumb_height;
    
    // Определить размер области для обрезки
    if ($ratio_original > $ratio_thumb) {
        $crop_width = round($height * $ratio_thumb);
        $crop_height = $height;
        $crop_x = round(($width - $crop_width) / 2);
        $crop_y = 0;
    } else {
        $crop_width = $width;
        $crop_height = round($width / $ratio_thumb);
        $crop_x = 0;
        $crop_y = round(($height - $crop_height) / 2);
    }
    
    if (!isGdExtensionEnabled()) {
        return false;
    }

    // Создать новое изображение для миниатюры
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    
    // Загрузить исходное изображение
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($source_path);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($source_path);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    if (!$source) {
        return false;
    }
    
    // Скопировать и переразмерить область из исходного изображения
    imagecopyresampled($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
    
    // Сохранить миниатюру
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $dest_path, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $dest_path);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $dest_path);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($thumb, $dest_path, 85);
            break;
    }
    
    imagedestroy($thumb);
    imagedestroy($source);
    
    return true;
}