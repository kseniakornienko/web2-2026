<h2>Каталог товаров</h2>

<style>
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.product-card img {
    max-width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
}

.product-card h3 {
    margin: 10px 0;
    font-size: 1.2em;
}

.product-card .price {
    color: #e44d26;
    font-size: 1.3em;
    font-weight: bold;
    margin: 10px 0;
}

.product-card .description {
    color: #666;
    font-size: 0.9em;
    margin: 10px 0;
    height: 60px;
    overflow: hidden;
}

.product-card .btn {
    display: inline-block;
    padding: 8px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.product-card .btn:hover {
    background-color: #0056b3;
}
</style>

<?php if (empty($products)): ?>
    <p>Товаров пока нет.</p>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= getProductImageUrl($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="price"><?= number_format($product['price'], 2) ?> ₽</div>
                <div class="description"><?= htmlspecialchars(mb_substr($product['description'], 0, 100)) ?>...</div>
                <a href="<?= BASE_URL ?>index.php?page=product&id=<?= $product['id'] ?>" class="btn">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>