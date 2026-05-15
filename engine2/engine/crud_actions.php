<?php
function doFeedbackAction($action, $data = []) {
    $result = ['success' => false, 'message' => '', 'data' => null];
    
    try {
        switch ($action) {
            case 'create':
                if (empty($data['product_id']) || empty($data['author']) || empty($data['rating']) || empty($data['comment'])) {
                    $result['message'] = 'Все поля обязательны для заполнения';
                    return $result;
                }
                
                if ($data['rating'] < 1 || $data['rating'] > 5) {
                    $result['message'] = 'Рейтинг должен быть от 1 до 5';
                    return $result;
                }
                
                $review_id = createReview(
                    $data['product_id'],
                    htmlspecialchars($data['author']),
                    (int)$data['rating'],
                    htmlspecialchars($data['comment'])
                );
                
                if ($review_id) {
                    $result['success'] = true;
                    $result['message'] = 'Отзыв добавлен и ожидает модерации';
                    $result['data'] = ['id' => $review_id];
                    _log("Создан отзыв для товара ID: {$data['product_id']}", 'reviews');
                } else {
                    $result['message'] = 'Не удалось добавить отзыв';
                }
                break;
                
            case 'read':
                if (empty($data['product_id'])) {
                    $result['message'] = 'ID товара обязателен';
                    return $result;
                }
                
                $reviews = getReviewsByProductId($data['product_id'], !isset($data['show_all']) || !$data['show_all']);
                $result['success'] = true;
                $result['data'] = $reviews;
                break;
                
            case 'update':
                if (empty($data['id']) || empty($data['author']) || empty($data['rating']) || empty($data['comment'])) {
                    $result['message'] = 'Недостаточно данных для обновления';
                    return $result;
                }
                
                $is_approved = isset($data['is_approved']) ? (bool)$data['is_approved'] : false;
                $updated = updateReview(
                    $data['id'],
                    htmlspecialchars($data['author']),
                    (int)$data['rating'],
                    htmlspecialchars($data['comment']),
                    $is_approved
                );
                
                if ($updated) {
                    $result['success'] = true;
                    $result['message'] = 'Отзыв обновлен';
                    _log("Обновлен отзыв ID: {$data['id']}", 'reviews');
                } else {
                    $result['message'] = 'Не удалось обновить отзыв';
                }
                break;
                
            case 'delete':
                if (empty($data['id'])) {
                    $result['message'] = 'ID отзыва обязателен';
                    return $result;
                }
                
                $deleted = deleteReview($data['id']);
                if ($deleted) {
                    $result['success'] = true;
                    $result['message'] = 'Отзыв удален';
                    _log("Удален отзыв ID: {$data['id']}", 'reviews');
                } else {
                    $result['message'] = 'Не удалось удалить отзыв';
                }
                break;
                
            case 'approve':
                if (empty($data['id'])) {
                    $result['message'] = 'ID отзыва обязателен';
                    return $result;
                }
                
                $approved = approveReview($data['id']);
                if ($approved) {
                    $result['success'] = true;
                    $result['message'] = 'Отзыв одобрен и опубликован';
                    _log("Одобрен отзыв ID: {$data['id']}", 'reviews');
                } else {
                    $result['message'] = 'Не удалось одобрить отзыв';
                }
                break;
                
            default:
                $result['message'] = 'Неизвестное действие';
        }
    } catch (Exception $e) {
        $result['message'] = 'Ошибка: ' . $e->getMessage();
        _log("Ошибка в doFeedbackAction: " . $e->getMessage(), 'crud_error');
    }
    
    return $result;
}

function handleFeedbackAjax() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback_action'])) {
        header('Content-Type: application/json');
        
        $action = $_POST['feedback_action'];
        $data = [];
        
        switch ($action) {
            case 'create':
                $data = [
                    'product_id' => $_POST['product_id'] ?? null,
                    'author' => $_POST['author'] ?? null,
                    'rating' => $_POST['rating'] ?? null,
                    'comment' => $_POST['comment'] ?? null
                ];
                break;
                
            case 'read':
                $data = [
                    'product_id' => $_GET['product_id'] ?? $_POST['product_id'] ?? null,
                    'show_all' => isset($_POST['show_all']) ? $_POST['show_all'] : false
                ];
                break;
                
            case 'update':
            case 'delete':
            case 'approve':
                $data = [
                    'id' => $_POST['id'] ?? null
                ];
                if ($action == 'update') {
                    $data['author'] = $_POST['author'] ?? null;
                    $data['rating'] = $_POST['rating'] ?? null;
                    $data['comment'] = $_POST['comment'] ?? null;
                    $data['is_approved'] = $_POST['is_approved'] ?? false;
                }
                break;
        }
        
        $result = doFeedbackAction($action, $data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>