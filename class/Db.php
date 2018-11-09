<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 15:03
 */

class Db {

    protected static $instance = null;

    private function __construct() {
        //
    }

    private function __clone() {
        //
    }

    /**
     * Устанавливает соединение с базой данных
     * @return null|PDO
     */
    public static function getInstance() {
        if (self::$instance === null) {

            $params = self::getConnect();

            $opt = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $params['char'],
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => TRUE,
            );
            // postgreSQL
            self::$instance = new PDO('pgsql:host=' . $params['host'] . ' dbname=' . $params['dbname'], $params['user'], $params['password'], $opt);
        }
        return self::$instance;
    }

    private static function getConnect() {
        // Получаем параметры подключения из файла
        return require_once ROOT . '/system/db_params.php';
    }
}