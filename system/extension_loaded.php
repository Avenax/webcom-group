<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 14:41
 */

if (intval(phpversion() < 7)) {
    echo '<pre>';
    echo 'Установите php 7 >=';
    echo '<pre>';
    exit();
}

// Проверяем подключенные модули
$white_extension_loaded = [
    'PDO',
    'pgsql',
    // etc...
];
$array_control = [];
foreach ($white_extension_loaded as $control) {
    if (!extension_loaded($control)) {
        $array_control[] = $control;
    }
}

if (!empty($array_control)) {
    echo '<pre>';
    echo 'Загрузите расширение: ';
    foreach ($array_control as $control) {
        echo $control . ' , ';
    }
    echo '<pre>';
    exit();
}