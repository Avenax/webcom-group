<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 16:26
 */

return [
    // Главная страница
    'load' => 'site/load',
    'edit/([0-9]+)' => 'site/edit/$1',
    'delete/([0-9]+)' => 'book/delete/$1',
    'rating/([0-9]+)/([1-5])' => 'book/rating/$1/$2',
    'auth' => 'user/auth',
    'register' => 'user/register',
    'index.php' => 'site/index', // actionIndex в SiteController
    '' => 'site/index', // actionIndex в SiteController
];