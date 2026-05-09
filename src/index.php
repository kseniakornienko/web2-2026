<?php
// index.php - Product catalog page
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="assets/styles/catalog.css">
</head>
<body>
    <header>
        <h1>Product Catalog</h1>
    </header>
    <main>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" onclick="openProduct(<?php echo $product['id']; ?>)">
                    <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <p class="description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="assets/scripts/catalog.js"></script>
</body>
</html>