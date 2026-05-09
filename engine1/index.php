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
        $params['title'] = 'О нас';
        $params['phone'] = 444333;
        break;

    case 'apicatalog':
        echo json_encode(getCatalog(), JSON_UNESCAPED_UNICODE);
        die();

    default:
        echo "404";
        die();
}



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
            'link' => '?page=index'
        ],
        [
            'title' => 'Каталог',
            'link' => '?page=catalog'
        ],
        [
            'title' => 'О нас',
            'link' => '?page=about'
        ],
        [
            'title' => 'Услуги',
            'link' => '#',
            'children' => [
                [
                    'title' => 'Доставка',
                    'link' => '#delivery'
                ],
                [
                    'title' => 'Оплата',
                    'link' => '#payment'
                ],
                [
                    'title' => 'Возврат',
                    'link' => '#return'
                ],
            ]
        ],
        [
            'title' => 'Контакты',
            'link' => '#',
            'children' => [
                [
                    'title' => 'Email',
                    'link' => 'mailto:info@example.com'
                ],
                [
                    'title' => 'Телефон',
                    'link' => 'tel:+7-495-123-45-67'
                ],
                [
                    'title' => 'Соцсети',
                    'link' => '#',
                    'children' => [
                        [
                            'title' => 'ВКонтакте',
                            'link' => '#vk'
                        ],
                        [
                            'title' => 'Telegram',
                            'link' => '#tg'
                        ],
                    ]
                ],
            ]
        ],
    ];
}


function render($page, $params = []) {
    return renderTemplate(LAYOUTS_DIR . 'main', [
        'title' => $params['title'],
        'menu' => renderTemplate('menu', ['menus' => getMenu()]),
        'content' => renderTemplate($page, $params)
    ]);
}

function renderTemplate($page, $params = []) {
    extract($params);
    ob_start();
    include TEMPLATES_DIR . $page . ".php";
    return ob_get_clean();
}

echo render($page, $params);
