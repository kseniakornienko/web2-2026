<style>
.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-image img {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
}

.product-info h1 {
    margin-top: 0;
    color: #333;
}

.product-info .price {
    font-size: 2em;
    color: #e44d26;
    font-weight: bold;
    margin: 20px 0;
}

.product-info .description {
    line-height: 1.6;
    color: #666;
    margin: 20px 0;
}

.rating {
    margin: 20px 0;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 5px;
}

.stars {
    color: #ffc107;
    font-size: 1.2em;
}

.reviews-section {
    margin-top: 40px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.review-form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.review-form h3 {
    margin-top: 0;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, 
.form-group textarea, 
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    min-height: 100px;
}

.btn-submit {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #218838;
}

.review {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.review:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.review-author {
    font-weight: bold;
    color: #333;
}

.review-date {
    color: #999;
    font-size: 0.9em;
}

.review-rating {
    color: #ffc107;
    margin: 5px 0;
}

.review-comment {
    color: #666;
    line-height: 1.5;
    margin-top: 10px;
}

.success-message {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.btn-back {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 15px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn-back:hover {
    background-color: #5a6268;
}

.loading {
    text-align: center;
    padding: 20px;
}

.hidden {
    display: none;
}
</style>

<a href="<?= BASE_URL ?>index.php?page=catalog" class="btn-back">← Назад к каталогу</a>

<div class="product-detail">
    <div class="product-image">
        <img src="<?= getProductImageUrl($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-info">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        
        <?php if ($rating_info['total'] > 0): ?>
        <div class="rating">
            <div class="stars">
                <?php 
                $full_stars = floor($rating_info['average']);
                $half_star = ($rating_info['average'] - $full_stars) >= 0.5;
                for ($i = 1; $i <= 5; $i++):
                    if ($i <= $full_stars):
                        echo '★';
                    elseif ($half_star && $i == $full_stars + 1):
                        echo '½';
                    else:
                        echo '☆';
                    endif;
                endfor;
                ?>
                <span> (<?= $rating_info['average'] ?> из 5, <?= $rating_info['total'] ?> отзывов)</span>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="price"><?= number_format($product['price'], 2) ?> ₽</div>
        <div class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
        <button class="btn-submit" onclick="alert('Функционал покупки в разработке')">Купить сейчас</button>
    </div>
</div>

<div class="reviews-section">
    <h2>Отзывы покупателей</h2>
    
    <div class="review-form">
        <h3>Оставить отзыв</h3>
        
        <?php if (isset($review_success)): ?>
            <div class="success-message"><?= htmlspecialchars($review_success) ?></div>
        <?php endif; ?>
        
        <?php if (isset($review_error)): ?>
            <div class="error-message"><?= htmlspecialchars($review_error) ?></div>
        <?php endif; ?>
        
        <form method="POST" id="reviewForm">
            <div class="form-group">
                <label for="author">Ваше имя *</label>
                <input type="text" id="author" name="author" required>
            </div>
            
            <div class="form-group">
                <label for="rating">Оценка *</label>
                <select id="rating" name="rating" required>
                    <option value="">Выберите оценку</option>
                    <option value="5">5 - Отлично</option>
                    <option value="4">4 - Хорошо</option>
                    <option value="3">3 - Средне</option>
                    <option value="2">2 - Плохо</option>
                    <option value="1">1 - Ужасно</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="comment">Комментарий *</label>
                <textarea id="comment" name="comment" required placeholder="Поделитесь впечатлениями о товаре..."></textarea>
            </div>
            
            <button type="submit" name="add_review" class="btn-submit">Отправить отзыв</button>
        </form>
    </div>
    
    <div id="reviewsList">
        <?php if (empty($reviews)): ?>
            <p>Пока нет отзывов. Будьте первым!</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <div class="review-header">
                        <span class="review-author"><?= htmlspecialchars($review['author']) ?></span>
                        <span class="review-date"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></span>
                    </div>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $review['rating'] ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </div>
                    <div class="review-comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- AJAX версия для отзывов (демонстрация работы doFeedbackAction через AJAX) -->
<script>
// Альтернативная версия с использованием AJAX и единой функции doFeedbackAction
// Раскомментируйте для использования AJAX вместо обычной формы

/*
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('feedback_action', 'create');
    formData.append('product_id', <?= $product['id'] ?>);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при отправке отзыва');
    });
});
*/
</script>