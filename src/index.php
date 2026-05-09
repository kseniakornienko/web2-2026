<?php

$pageTitle = "PHP Задачи";
$heading = "Выполнение заданий на PHP";
$currentYear = date("Y");
$a = 12;
$b = -5;

// Задача 1
function processTwoNumbers($a, $b) {
    if ($a >= 0 && $b >= 0) {
        return "Оба положительные (≥0). Разность: " . ($a - $b);
    } elseif ($a < 0 && $b < 0) {
        return "Оба отрицательные. Произведение: " . ($a * $b);
    } else {
        return "Разные знаки. Сумма: " . ($a + $b);
    }
}

// Задача 3
function add($arg1, $arg2) {
    return $arg1 + $arg2;
}

function subtract($arg1, $arg2) {
    return $arg1 - $arg2;
}

function multiply($arg1, $arg2) {
    return $arg1 * $arg2;
}

function divide($arg1, $arg2) {
    if ($arg2 == 0) {
        return "Ошибка: деление на ноль";
    }
    return $arg1 / $arg2;
}

// Задача 4
function mathOperation($arg1, $arg2, $operation) {
    switch ($operation) {
        case "add":
        case "сумма":
        case "+":
            return add($arg1, $arg2);
        case "subtract":
        case "разность":
        case "-":
            return subtract($arg1, $arg2);
        case "multiply":
        case "произведение":
        case "*":
            return multiply($arg1, $arg2);
        case "divide":
        case "деление":
        case "/":
            return divide($arg1, $arg2);
        default:
            return "Неизвестная операция: " . $operation;
    }
}

// Задача 6
function power($val, $pow) {
    if ($pow == 0) {
        return 1;
    }
    if ($pow < 0) {
        return 1 / power($val, -$pow);
    }
    return $val * power($val, $pow - 1);
}

$task1Result = processTwoNumbers($a, $b);
$originalA = $a;
$originalB = $b;

// Задача 2
$a = rand(0, 15);
$numbersSequence = [];
switch ($a) {
    case 0: $numbersSequence[] = 0;
    case 1: $numbersSequence[] = 1;
    case 2: $numbersSequence[] = 2;
    case 3: $numbersSequence[] = 3;
    case 4: $numbersSequence[] = 4;
    case 5: $numbersSequence[] = 5;
    case 6: $numbersSequence[] = 6;
    case 7: $numbersSequence[] = 7;
    case 8: $numbersSequence[] = 8;
    case 9: $numbersSequence[] = 9;
    case 10: $numbersSequence[] = 10;
    case 11: $numbersSequence[] = 11;
    case 12: $numbersSequence[] = 12;
    case 13: $numbersSequence[] = 13;
    case 14: $numbersSequence[] = 14;
    case 15: $numbersSequence[] = 15;
}

$task3Examples = [
    "10 + 5 = " . add(10, 5),
    "20 - 8 = " . subtract(20, 8),
    "6 * 7 = " . multiply(6, 7),
    "30 / 6 = " . divide(30, 6)
];

$task4Examples = [
    "mathOperation(15, 3, 'add') = " . mathOperation(15, 3, "add"),
    "mathOperation(20, 8, 'subtract') = " . mathOperation(20, 8, "subtract"),
    "mathOperation(4, 5, 'multiply') = " . mathOperation(4, 5, "multiply"),
    "mathOperation(100, 5, 'divide') = " . mathOperation(100, 5, "divide")
];

$year1 = date("Y");
$year2 = strval(strtotime("now") - strtotime("1970-01-01")) > 0 ? date("Y") : null;
$year3 = getdate()["year"];

$powerExample = power(2, 8);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-align: center;
        }
        
        h2 {
            color: #667eea;
            font-size: 1.5em;
            margin-bottom: 15px;
            margin-top: 5px;
        }
        
        .section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .section p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .result {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            border: 1px solid #eee;
            font-weight: bold;
            color: #333;
        }
        
        .result p {
            margin-bottom: 8px;
        }
        
        .year {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #888;
            font-size: 1.2em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $heading; ?></h1>
        
        <div class="section">
            <h2>Задача 1: Условия для двух чисел</h2>
            <p><strong>$a = <?php echo $originalA; ?>, $b = <?php echo $originalB; ?></strong></p>
            <div class="result">
                <?php echo $task1Result; ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Задача 2: Switch для вывода чисел</h2>
            <p><strong>Переменная $a = <?php echo $a; ?>, числа от $a до 15:</strong></p>
            <div class="result">
                <?php echo implode(", ", $numbersSequence); ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Задача 3: Четыре арифметические операции</h2>
            <div class="result">
                <?php foreach ($task3Examples as $example): ?>
                    <p><?php echo $example; ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Задача 4: Функция mathOperation</h2>
            <div class="result">
                <?php foreach ($task4Examples as $example): ?>
                    <p><?php echo $example; ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Задача 5: Текущий год (3 способа)</h2>
            <div class="result">
                <p><strong>Способ 1 (date):</strong> <?php echo $year1; ?></p>
                <p><strong>Способ 2 (strtotime + date):</strong> <?php echo date("Y", strtotime("now")); ?></p>
                <p><strong>Способ 3 (getdate):</strong> <?php echo $year3; ?></p>
            </div>
        </div>
        
        <div class="section">
            <h2>Задача 6: Рекурсивное возведение в степень</h2>
            <p><strong>power(2, 8) = <?php echo $powerExample; ?></strong></p>
            <div class="result">
                <p>power(3, 4) = <?php echo power(3, 4); ?></p>
                <p>power(5, 3) = <?php echo power(5, 3); ?></p>
                <p>power(10, 2) = <?php echo power(10, 2); ?></p>
            </div>
        </div>
        
        <div class="year">
            © <?php echo $currentYear; ?> Все права защищены
        </div>
    </div>
</body>
</html>