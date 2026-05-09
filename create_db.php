<?php
// Создание базы данных и таблицы для меню
$db = new SQLite3('db/menu.db');

$db->exec("CREATE TABLE IF NOT EXISTS menu (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    parent_id INTEGER DEFAULT NULL,
    order_index INTEGER DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES menu(id)
)");

// Функция для вставки данных рекурсивно
function insertMenuItem($db, $name, $parent_id = null, $order = 0) {
    $stmt = $db->prepare("INSERT INTO menu (name, parent_id, order_index) VALUES (:name, :parent_id, :order)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':parent_id', $parent_id, SQLITE3_INTEGER);
    $stmt->bindValue(':order', $order, SQLITE3_INTEGER);
    $stmt->execute();
    return $db->lastInsertRowID();
}

// Вставка данных из script.js
$root_id = insertMenuItem($db, 'Каталог товаров');

$moiki_id = insertMenuItem($db, 'Мойки', $root_id, 1);
$ulgran_id = insertMenuItem($db, 'Ulgran', $moiki_id, 1);
insertMenuItem($db, 'Smth', $ulgran_id, 1);
insertMenuItem($db, 'Smth', $ulgran_id, 2);
insertMenuItem($db, 'Vigro Mramor', $moiki_id, 2);
$handmade_id = insertMenuItem($db, 'Handmade', $moiki_id, 3);
insertMenuItem($db, 'Smth', $handmade_id, 1);
insertMenuItem($db, 'Smth', $handmade_id, 2);
insertMenuItem($db, 'Vigro Glass', $moiki_id, 4);

$filtry_id = insertMenuItem($db, 'Фильтры', $root_id, 2);
$ulgran_f_id = insertMenuItem($db, 'Ulgran', $filtry_id, 1);
insertMenuItem($db, 'Smth', $ulgran_f_id, 1);
insertMenuItem($db, 'Smth', $ulgran_f_id, 2);
insertMenuItem($db, 'Vigro Mramor', $filtry_id, 2);

echo "База данных создана и данные вставлены.";
?>