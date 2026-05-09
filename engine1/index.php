<?php
define('TEMPLATES_DIR', 'templates/');
define('LAYOUTS_DIR', 'layouts/');

$page = 'index';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$params = [];

switch ($page) {
    case 'index':
        $params['title'] = 'Главная';
        break;

    case 'catalog':
        $params['title'] = 'Каталог';
        $params['catalog'] = getCatalog();
        break;

    case 'about':
        $params['title'] = 'about';
        $params['phone'] = 444333;
        break;

    case 'apicatalog':
        echo json_encode(getCatalog(), JSON_UNESCAPED_UNICODE);
        die();

    default:
        echo "404";
        die();
}

//echo render($page, $params);


function getCatalog() {
    return [
        [
            'name' => 'Яблоко',
            'price' => 24,
            'image' => 'apple.png'
        ],
        [
            'name' => 'Банан',
            'price' => 1,
            'image' => 'banana.png'
        ],
        [
            'name' => 'Апельсин',
            'price' => 12,
            'image' => 'orange.png'
        ],
    ];
}

function getMenu() {
    return [
        [
            'title' => 'Главная',
            'link' => '/3'
        ],
        [
            'title' => 'Каталог',
            'link' => '/3/?page=catalog'
        ],
        [
            'title' => 'О нас',
            'link' => '/3/?page=about'
        ],
        [
            'title' => 'Задание 3',
            'link' => '/3/?page=zadanie'
        ],
    ];
}


function render($page, $params = []) {
    return renderTemplate(LAYOUTS_DIR . 'main', [
        'title' => $params['title'],
        'menu' => renderTemplate('menu', ['menus'=> getMenu()] ),
        'content' => renderTemplate($page, $params)
    ]);
}

//$page = 'index';
//
//$params = [
//    'test' => 'test',
//    'title' => 'Главная',
//    'phone' => '+7 495 12-23-12'
//];

function renderTemplate($page, $params = []) {

    /*    foreach ($params as $key => $value) {
            $$key = $value;
        }*/
    extract($params);

    ob_start();
    include TEMPLATES_DIR . $page . ".php";
    return ob_get_clean();
}

//echo renderTemplate('index', $params);

echo renderTemplate(LAYOUTS_DIR . 'main', [
    'title' => $params['title'],
    'menu' => renderTemplate('menu'),
    'content' => renderTemplate($page, $params)
]);
