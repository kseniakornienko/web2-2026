<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="list-items" id="list-items">
        <?php
        require 'menu.php';
        $db = new SQLite3('db/menu.db');
        echo renderMenu($db);
        $db->close();
        ?>
    </div>
    <script type="module" src="script.js"></script>
</body>
</html>