<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 14:35
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
define('ROOT', dirname(__FILE__));
date_default_timezone_set('Europe/Moscow');
header("Content-type: text/html");
session_start();

include_once ROOT . '/system/extension_loaded.php';
include_once ROOT . '/system/autoload.php';


$router = new Router();
$router->run();
