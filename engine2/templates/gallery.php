<h2>Галерея фотографий</h2>

<?php if (!empty($error)): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="success-message">Изображение успешно загружено!</div>
<?php endif; ?>

<div class="upload-form">
    <h3>Загрузить новое изображение</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="gallery_image" accept="image/*" required>
        <input type="hidden" name="upload_gallery" value="1">
        <input type="submit" value="Загрузить">
    </form>
    <small>Допускаемые форматы: JPG, PNG, GIF, WebP (максимум 5 МБ)</small>
</div>

<?php echo $gallery_html; ?>
