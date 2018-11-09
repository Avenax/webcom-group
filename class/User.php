<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 16:04
 */

class User extends BaseClass {

    /**
     * Регистрация
     * @param $login
     * @param $password
     */
    public static function setRegister($login, $password)
    {
        $db = Db::getInstance();
        $sql = $db->prepare('INSERT INTO users (login, password) VALUES (?, ?)');
        $sql->execute([trim($login), self::hash($password)]);
        $id = $db->lastInsertId();

        self::setAuth($id);
        self::redirect('/');
    }

    /**
     * Проверяем логин
     * @param $login
     * @return bool
     */
    public static function checkRegUserData($login) {
        $db = Db::getInstance();
        $sql = $db->prepare('SELECT COUNT(*) FROM users WHERE login = ? LIMIT 1');
        $sql->execute([trim($login)]);
        $user = $sql->fetchColumn();

        if ($user == true) {
            return false;
        }
        return true;
    }

    /**
     * Чекаем в БД
     * @param $login
     * @param $password
     * @return bool
     */
    public static function checkUserData($login, $password)
    {
        $db = Db::getInstance();
        $sql = $db->prepare('SELECT id FROM users WHERE login = ? AND password = ? LIMIT 1');
        $sql->execute([trim($login), self::hash($password)]);
        $user = $sql->fetch();

        if ($user == true) {
            return $user['id'];
        }
        return false;
    }

    /**
     * Сохраняем юзера в сессии
     * @param $userId
     */
    public static function setAuth($userId) {
        $_SESSION['user'] = $userId;
    }

    /**
     * Aвторизован юзер или нет
     * @return bool
     */
    public static function isAuth() {
        if (!empty($_SESSION['user'])) {
            return true;
        }
        return false;
    }
}