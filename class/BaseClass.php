<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 15:55
 */

abstract class BaseClass {

    /**
     * Хэш
     * @param $str
     * @return string
     */
    public static function hash($str) {
        return md5($str);
    }

    /**
     * Показываем сообщения
     * @return bool|string
     */
    public static function message() {
        if (isset($_SESSION['message'])) {

            $message = '<div class="block">';

            foreach ($_SESSION['message'] as $msg) {
                $message .= '<span class="major">' . $msg . '</span>';
            }

            $message .= '</div>';

            unset($_SESSION['message']);
            return $message;
        }
        return false;
    }

    /**
     * Редирект
     * @param string $url
     */
    public static function redirect($url = '/') {
        header('location: ' . $url);
        exit();
    }

    /**
     * Проверяет имя: не меньше, чем 2 символа
     * @param $name
     * @return bool
     */
    public static function checkName($name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 6 символов
     * @param $password
     * @return bool
     */
    public static function checkPassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    public static function getTime($time = null) {

        if ($time == null) {
            $time = time();
        }

        $timep = "" . date("j M Y  H:i", intval($time)) . "";
        $time_p[0] = date("j n Y", intval($time));
        $time_p[1] = date("H:i", intval($time));
        $time_p[2] = date("H:i:s", intval($time));


        if ($time_p[0] == date("j n Y")) {
            $timep = date("H:i:s", $time);
        }


        if ($time_p[0] == date("j n Y")) {
            $timep = "Сегодня в $time_p[2]";
        }

        if ($time_p[0] == date("j n Y", time() - 60 * 60 * 24)) {
            $timep = "Вчера в $time_p[1]";
        }

        $timep = str_replace("Jan", "января", $timep);
        $timep = str_replace("Feb", "февраля", $timep);
        $timep = str_replace("Mar", "марта", $timep);
        $timep = str_replace("Apr", "апреля", $timep);
        $timep = str_replace("May", "мая", $timep);
        $timep = str_replace("Jun", "июня", $timep);
        $timep = str_replace("Jul", "июля", $timep);
        $timep = str_replace("Aug", "августа", $timep);
        $timep = str_replace("Sep", "сентября", $timep);
        $timep = str_replace("Oct", "октября", $timep);
        $timep = str_replace("Nov", "ноября", $timep);
        $timep = str_replace("Dec", "декабря", $timep);
        return $timep;
    }
}