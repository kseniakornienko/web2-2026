<?php
// Задание 1

function getNumberDescriptions(): string {
    $i = 0;
    $output = '';
    do {
        if ($i === 0) {
            $output .= "0 – это ноль.<br>";
        } else {
            $output .= $i . ' – ' . ($i % 2 === 0 ? 'чётное число.' : 'нечётное число.') . '<br>';
        }
        $i++;
    } while ($i <= 10);

    return $output;
}

echo '<h2>Задание 1: Числа от 0 до 10 с do…while</h2>';
echo getNumberDescriptions();

// Задание 2

function getRegions(): array {
    return [
        'Московская область' => [
            'Москва',
            'Зеленоград',
            'Клин',
        ],
        'Ленинградская область' => [
            'Санкт-Петербург',
            'Всеволожск',
            'Павловск',
            'Кронштадт',
        ],
        'Рязанская область' => [
            'Рязань',
            'Касимов',
            'Шацк',
            'Скопин',
        ],
    ];
}

echo '<h2>Задание 2: Области и города</h2>';
$regions = getRegions();
foreach ($regions as $region => $cities) {
    echo '<p><strong>' . htmlspecialchars($region, ENT_QUOTES, 'UTF-8') . ':</strong><br>';
    echo htmlspecialchars(implode(', ', $cities), ENT_QUOTES, 'UTF-8') . '.</p>' . "\n";
}

// Задание 3

function getTranslitMap(): array {
    return [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'kh',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    ];
}

// Задание 3

function transliterate(string $text): string {
    $map = getTranslitMap();
    $result = '';
    $length = mb_strlen($text, 'UTF-8');
    for ($i = 0; $i < $length; $i++) {
        $char = mb_substr($text, $i, 1, 'UTF-8');
        $lower = mb_strtolower($char, 'UTF-8');
        if (isset($map[$lower])) {
            $trans = $map[$lower];
            if ($char !== $lower) {
                $trans = mb_strtoupper(mb_substr($trans, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($trans, 1, null, 'UTF-8');
            }
            $result .= $trans;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

echo '<h2>Задание 3: Транслитерация</h2>';
echo '<p>Исходная строка: <strong>Привет, мир!</strong></p>';
echo '<p>Результат: <strong>' . htmlspecialchars(transliterate('Привет, мир!'), ENT_QUOTES, 'UTF-8') . '</strong></p>';

// Задание 6

function renderRegions(array $regions, string $startsWith = ''): string {
    $output = '';
    foreach ($regions as $region => $cities) {
        $filtered = [];
        foreach ($cities as $city) {
            if ($startsWith === '') {
                $filtered[] = $city;
            } elseif (mb_strtoupper(mb_substr($city, 0, 1, 'UTF-8'), 'UTF-8') === mb_strtoupper($startsWith, 'UTF-8')) {
                $filtered[] = $city;
            }
        }
        if (empty($filtered)) {
            continue;
        }
        $output .= '<p><strong>' . htmlspecialchars($region, ENT_QUOTES, 'UTF-8') . ':</strong><br>' . htmlspecialchars(implode(', ', $filtered), ENT_QUOTES, 'UTF-8') . '.</p>' . "\n";
    }
    return $output;
}

echo '<h2>Задание 6: Города, начинающиеся с буквы К</h2>';
echo renderRegions(getRegions(), 'К');
