<?php
define('BASE_URL', '/web2-2026/engine2/public/');
define('TEMPLATES_DIR', '../templates/');
define('LAYOUTS_DIR', 'layouts/');
define('GALLERY_DIR', $_SERVER['DOCUMENT_ROOT'] . '/web2-2026/gallery/');
define('GALLERY_URL', BASE_URL . '../../gallery/');
define('PRODUCT_IMAGES_DIR', $_SERVER['DOCUMENT_ROOT'] . '/web2-2026/engine2/public/img/products/');
define('PRODUCT_IMAGES_URL', BASE_URL . 'img/products/');

include "../engine/bux.php";
include "../engine/functions.php";
include "../engine/catalog.php";
include "../engine/log.php";
include "../engine/product_model.php";
include "../engine/review_model.php";
include "../engine/crud_actions.php";
include "../config/db.php";