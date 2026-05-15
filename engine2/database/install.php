<?php
/**
 * Простая установка БД - перейдите на эту страницу один раз
 */

// Параметры подключения
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'shop';

try {
    // Подключаемся к MySQL
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создаем БД
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
    // Создаем таблицу товаров
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            image VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Создаем таблицу отзывов
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            author VARCHAR(100) NOT NULL,
            rating TINYINT NOT NULL,
            comment TEXT NOT NULL,
            is_approved BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    
    // Проверяем, есть ли товары
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $count = $stmt->fetchColumn();
    
    // Если товаров нет - добавляем
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO products (name, description, price, image) VALUES
            ('Яблоко сочное', 'Свежие сочные яблоки. Хрустящие и сладкие.', 24, 'apple.jpg'),
            ('Пицца Маргарита', 'Классическая итальянская пицца с моцареллой и базиликом.', 1, 'pizza.jpeg'),
            ('Чай зеленый', 'Ароматный зеленый чай. Бодрит и полезен.', 12, 'tea.png'),
            ('Апельсины', 'Сладкие апельсины, богатые витамином C.', 45, 'orange.png'),
            ('Бананы', 'Спелые бананы. Источник калия.', 30, 'banana.jpg'),
            ('Виноград', 'Сладкий виноград без косточек.', 60, 'grape.jpg')
        ");
        
        // Добавляем отзывы
        $pdo->exec("
            INSERT INTO reviews (product_id, author, rating, comment) VALUES
            (1, 'Анна', 5, 'Отличные яблоки! Очень вкусные!'),
            (1, 'Михаил', 4, 'Хорошие яблоки, сочные.'),
            (2, 'Иван', 5, 'Лучшая пицца!'),
            (3, 'Сергей', 5, 'Чай просто супер!'),
            (4, 'Дмитрий', 5, 'Апельсины сладкие и сочные.'),
            (5, 'Мария', 4, 'Бананы хорошие.'),
            (6, 'Алексей', 5, 'Отличный виноград!')
        ");
    }
    
    echo "<h2 style='color: green;'>✅ База данных успешно создана!</h2>";
    echo "<p>База: <strong>$dbname</strong></p>";
    echo "<p>Таблицы: products, reviews</p>";
    echo "<p>Добавлено товаров: 6, отзывов: 7</p>";
    echo "<hr>";
    echo "<a href='../public/index.php?page=catalog'>Перейти в каталог</a><br>";
    echo "<a href='../public/index.php'>На главную</a>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Ошибка!</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>