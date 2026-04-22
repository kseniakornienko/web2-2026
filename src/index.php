<?php

$pageTitle = "Моя первая PHP страница";
$heading = "Добро пожаловать на мой сайт!";
$currentYear = date("Y");


function getFormattedTime() {
    $hours = date("H");
    $minutes = date("i");
    
    if ($hours % 10 == 1 && $hours % 100 != 11) {
        $hoursText = "час";
    } elseif (($hours % 10 >= 2 && $hours % 10 <= 4) && ($hours % 100 < 10 || $hours % 100 >= 20)) {
        $hoursText = "часа";
    } else {
        $hoursText = "часов";
    }
    
    if ($minutes % 10 == 1 && $minutes % 100 != 11) {
        $minutesText = "минута";
    } elseif (($minutes % 10 >= 2 && $minutes % 10 <= 4) && ($minutes % 100 < 10 || $minutes % 100 >= 20)) {
        $minutesText = "минуты";
    } else {
        $minutesText = "минут";
    }
    
    return $hours . " " . $hoursText . " " . $minutes . " " . $minutesText;
}
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
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        
        .time {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 15px;
            margin: 30px 0;
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }
        
        .year {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #888;
            font-size: 1.2em;
        }
        
        .info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $heading; ?></h1>
        
        <div class="time">
            <?php echo getFormattedTime(); ?>
        </div>
        
        <div class="info">
            <p>Это страница сгенерирована с помощью PHP</p>
            <p>Текущая дата: <?php echo date("d.m.Y"); ?></p>
        </div>
        
        <div class="year">
            © <?php echo $currentYear; ?> Все права защищены
        </div>
    </div>
</body>
</html>