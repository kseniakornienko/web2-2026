<?php
//функция для примера, осуществляющая вывод текстовой информации в файл
//использовать для логирования, можно выводить даже массив
//пример в index.php


function _log($s, $suffix = '')
{

    if (is_array($s) || is_object($s)) $s = print_r($s, 1);
    $s = "### " . date("d.m.Y H:i:s") . "\r\n" . $s . "\r\n\r\n\r\n";

    if (mb_strlen($suffix))
        $suffix = "_" . $suffix;

    _writeToFile($_SERVER['DOCUMENT_ROOT'] . "/_log/logs" . $suffix . ".log", $s, "a+");

    return $s;
}

function _writeToFile($fileName, $content, $mode = "w")
{
    $dir = mb_substr($fileName, 0, strrpos($fileName, "/"));
    if (!is_dir($dir)) {
        _makeDir($dir);
    }

    if ($mode != "r") {
        $fh = fopen($fileName, $mode);
        if (fwrite($fh, $content)) {
            fclose($fh);
            @chmod($fileName, 0644);
            return true;
        }
    }

    return false;
}

function _makeDir($dir, $is_root = true, $root = '')
{
    $dir = rtrim($dir, "/");
    if (is_dir($dir)) return true;
    if (mb_strlen($dir) <= mb_strlen($_SERVER['DOCUMENT_ROOT']))
        return true;
    if (str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) == $dir)
        return true;

    if ($is_root) {
        $dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
        $root = $_SERVER['DOCUMENT_ROOT'];
    }
    $dir_parts = explode("/", $dir);

    foreach ($dir_parts as $step => $value) {
        if ($value != '') {
            $root = $root . "/" . $value;

            if (!is_dir($root)) {
                mkdir($root, 0755);
                chmod($root, 0755);
            }
        }
    }
    return $root;
}

// Логирование запросов к index.php
function logPageRequest() {
    $log_entry = date("d.m.Y H:i:s") . " - " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " - IP: " . $_SERVER['REMOTE_ADDR'];

    // Использовать путь относительно скрипта вместо DOCUMENT_ROOT
    $script_dir = dirname($_SERVER['SCRIPT_FILENAME']);
    $log_file = $script_dir . "/_log/log.txt";

    // Создать директорию если её нет
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        _makeDir($log_dir);
    }

    // Простая запись в файл
    $result = file_put_contents($log_file, $log_entry . "\n", FILE_APPEND | LOCK_EX);
    if ($result === false) {
        return false;
    }

    // Проверить количество строк в файле
    rotateLogFile($log_file);

    return true;
}

// Ротация логов - переименование после каждых 10 записей
function rotateLogFile($log_file) {
    if (!file_exists($log_file)) {
        return;
    }

    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $line_count = count($lines);

    if ($line_count < 10) {
        return;
    }

    // Найти следующий номер для архива
    $log_dir = dirname($log_file);
    $next_number = 0;
    $files = scandir($log_dir);
    foreach ($files as $file) {
        if (preg_match('/^log(\d+)\.txt$/', $file, $matches)) {
            $number = (int)$matches[1];
            if ($number >= $next_number) {
                $next_number = $number + 1;
            }
        }
    }

    $archive_file = $log_dir . "/log" . $next_number . ".txt";
    $archive_lines = array_slice($lines, 0, 10);
    file_put_contents($archive_file, implode("\n", $archive_lines) . "\n");

    $remaining_lines = array_slice($lines, 10);
    if (!empty($remaining_lines)) {
        file_put_contents($log_file, implode("\n", $remaining_lines) . "\n", LOCK_EX);
    } else {
        file_put_contents($log_file, "", LOCK_EX);
    }

    chmod($archive_file, 0644);
    chmod($log_file, 0644);
}