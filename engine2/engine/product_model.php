<?php
function getAllProducts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    return $stmt->fetchAll();
}

function getProductById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getProductImageUrl($image) {
    if (file_exists(PRODUCT_IMAGES_DIR . $image)) {
        return PRODUCT_IMAGES_URL . $image;
    }
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/web2-2026/engine2/public/img/' . $image)) {
        return BASE_URL . 'img/' . $image;
    }
    return BASE_URL . 'img/placeholder.jpg';
}

function createProduct($name, $description, $price, $image) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $description, $price, $image]);
}

function updateProduct($id, $name, $description, $price, $image) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    return $stmt->execute([$name, $description, $price, $image, $id]);
}

function deleteProduct($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}
?>