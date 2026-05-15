<h2>Управление отзывами (CRUD)</h2>

<style>
.reviews-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.reviews-table th,
.reviews-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
    vertical-align: top;
}

.reviews-table th {
    background-color: #f2f2f2;
}

.status-pending {
    color: orange;
}

.status-approved {
    color: green;
}

.btn-approve {
    background-color: #28a745;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.btn-edit {
    background-color: #ffc107;
    color: #333;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    width: 50%;
    border-radius: 10px;
}
</style>

<?php
// Простая админка для демонстрации CRUD операций
$pdo = getDBConnection();
$stmt = $pdo->query("
    SELECT r.*, p.name as product_name 
    FROM reviews r 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC
");
$all_reviews = $stmt->fetchAll();
?>

<table class="reviews-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Товар</th>
            <th>Автор</th>
            <th>Рейтинг</th>
            <th>Комментарий</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($all_reviews as $review): ?>
        <tr data-review-id="<?= $review['id'] ?>">
            <td><?= $review['id'] ?></td>
            <td><?= htmlspecialchars($review['product_name']) ?></td>
            <td><?= htmlspecialchars($review['author']) ?></td>
            <td><?= $review['rating'] ?> ★</td>
            <td><?= htmlspecialchars(mb_substr($review['comment'], 0, 100)) ?>...</td>
            <td class="<?= $review['is_approved'] ? 'status-approved' : 'status-pending' ?>">
                <?= $review['is_approved'] ? 'Одобрен' : 'На модерации' ?>
            </td>
            <td><?= date('d.m.Y', strtotime($review['created_at'])) ?></td>
            <td>
                <?php if (!$review['is_approved']): ?>
                    <button class="btn-approve" onclick="approveReview(<?= $review['id'] ?>)">Одобрить</button>
                <?php endif; ?>
                <button class="btn-edit" onclick="editReview(<?= $review['id'] ?>)">Редактировать</button>
                <button class="btn-delete" onclick="deleteReview(<?= $review['id'] ?>)">Удалить</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Модальное окно для редактирования -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Редактирование отзыва</h3>
        <form id="editForm">
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
                <label>Автор</label>
                <input type="text" id="edit_author" name="author" required>
            </div>
            <div class="form-group">
                <label>Рейтинг</label>
                <select id="edit_rating" name="rating" required>
                    <option value="5">5 - Отлично</option>
                    <option value="4">4 - Хорошо</option>
                    <option value="3">3 - Средне</option>
                    <option value="2">2 - Плохо</option>
                    <option value="1">1 - Ужасно</option>
                </select>
            </div>
            <div class="form-group">
                <label>Комментарий</label>
                <textarea id="edit_comment" name="comment" required rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="edit_approved" name="is_approved" value="1">
                    Одобрен
                </label>
            </div>
            <button type="submit" class="btn-submit">Сохранить</button>
            <button type="button" onclick="closeModal()" class="btn-back">Отмена</button>
        </form>
    </div>
</div>

<script>
function approveReview(id) {
    if (confirm('Одобрить этот отзыв?')) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `feedback_action=approve&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Ошибка: ' + data.message);
            }
        });
    }
}

function deleteReview(id) {
    if (confirm('Удалить этот отзыв? Это действие необратимо.')) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `feedback_action=delete&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Ошибка: ' + data.message);
            }
        });
    }
}

function editReview(id) {
    // Получаем данные отзыва
    fetch(`?product_id=1&feedback_action=read&show_all=true`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `feedback_action=read&product_id=1&show_all=true`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const review = data.data.find(r => r.id == id);
            if (review) {
                document.getElementById('edit_id').value = review.id;
                document.getElementById('edit_author').value = review.author;
                document.getElementById('edit_rating').value = review.rating;
                document.getElementById('edit_comment').value = review.comment;
                document.getElementById('edit_approved').checked = review.is_approved == 1;
                document.getElementById('editModal').style.display = 'block';
            }
        }
    });
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('feedback_action', 'update');
    
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
    });
});

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>