<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 14:59
 */

function my_autoloader($class_name) {
    $array_paths = array(
        '/class/',
        '/controllers/',
    );

    foreach ($array_paths as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if (is_file($path)) {
            include_once $path;
        }
    }
}

spl_autoload_register('my_autoloader');
