<?php
// product.php - Product detail page
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID");
}

$productId = (int)$_GET['id'];

try {
    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found");
    }

    // Get reviews
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
    $stmt->execute([$productId]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $author = trim($_POST['author']);
    $reviewText = trim($_POST['review_text']);
    $rating = (int)$_POST['rating'];

    if (empty($author) || empty($reviewText) || $rating < 1 || $rating > 5) {
        $error = "Please fill all fields correctly.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (product_id, author, review_text, rating) VALUES (?, ?, ?, ?)");
            $stmt->execute([$productId, $author, $reviewText, $rating]);
            header("Location: product.php?id=$productId");
            exit;
        } catch (PDOException $e) {
            $error = "Error adding review: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="assets/styles/product.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <a href="index.php">← Back to Catalog</a>
    </header>
    <main>
        <div class="product-detail">
            <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
        </div>

        <div class="reviews-section">
            <h3>Reviews</h3>
            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <div class="review-header">
                                <strong><?php echo htmlspecialchars($review['author']); ?></strong>
                                <span class="rating"><?php echo str_repeat('★', $review['rating']); ?></span>
                                <span class="date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>

            <div class="add-review">
                <h4>Add a Review</h4>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="post">
                    <input type="text" name="author" placeholder="Your name" required>
                    <textarea name="review_text" placeholder="Your review" required></textarea>
                    <select name="rating" required>
                        <option value="">Select rating</option>
                        <option value="5">5 stars</option>
                        <option value="4">4 stars</option>
                        <option value="3">3 stars</option>
                        <option value="2">2 stars</option>
                        <option value="1">1 star</option>
                    </select>
                    <button type="submit" name="submit_review">Submit Review</button>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/scripts/product.js"></script>
</body>
</html>