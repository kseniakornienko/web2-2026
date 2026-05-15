<?php
function getReviewsByProductId($product_id, $onlyApproved = true) {
    $pdo = getDBConnection();
    if ($onlyApproved) {
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? AND is_approved = TRUE ORDER BY created_at DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
    }
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

function getReviewById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createReview($product_id, $author, $rating, $comment) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, author, rating, comment, is_approved) VALUES (?, ?, ?, ?, ?)");
    // По умолчанию отзывы требуют модерации
    return $stmt->execute([$product_id, $author, $rating, $comment, false]);
}

function updateReview($id, $author, $rating, $comment, $is_approved) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE reviews SET author = ?, rating = ?, comment = ?, is_approved = ? WHERE id = ?");
    return $stmt->execute([$author, $rating, $comment, $is_approved, $id]);
}

function deleteReview($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    return $stmt->execute([$id]);
}

function approveReview($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE reviews SET is_approved = TRUE WHERE id = ?");
    return $stmt->execute([$id]);
}

function getAverageRating($product_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE product_id = ? AND is_approved = TRUE");
    $stmt->execute([$product_id]);
    $result = $stmt->fetch();
    return [
        'average' => round($result['avg_rating'] ?? 0, 1),
        'total' => $result['total'] ?? 0
    ];
}
?>