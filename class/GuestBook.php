<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 16:42
 */

class GuestBook {
    public static $idUser = 1; // id юзера, комудоступна выгрузка
    private static $jsonRating = null;
    private static $filename = ROOT . '/json/check_rate.json';

    /**
     * Средняя оценка
     * @param $id
     * @return float|int
     */
    public static function averageRating($id) {
        $num = 0;
        $json = self::getList();

        if (!is_array($json)) {
            return 0;
        }

        foreach ($json as $user) {
            foreach ($user as $value) {
                if ($value == $id) {
                    $num++;
                }
            }
        }

        $data = self::getAllAverage()[$id];
        return $data == 0 ? 0 : $data / $num;
    }

    /**
     * Получаем список
     * @return bool
     */
    public static function getList($idUser = false) {
        if (self::$jsonRating === null) {
            $file = file_get_contents(self::$filename, true);
            self::$jsonRating = json_decode($file, true);
        }

        if ($idUser == true && array_key_exists($_SESSION['user'], self::$jsonRating)) {
            return self::$jsonRating[$_SESSION['user']];
        }
        return self::$jsonRating;
    }

    /**
     * Записываем в json файл
     * @param $id
     * @param $rat
     */
    public static function setList($id, $rat) {

        $allList = self::getList();
        $allList[$_SESSION['user']][] = $id;
        file_put_contents(self::$filename, json_encode($allList));

        $db = Db::getInstance();
        $sql = $db->prepare("UPDATE quest_book SET rating = rating + ? WHERE id = ?");
        $sql->execute([$rat, $id]);

        $_SESSION['message'] = ['Вы успешно поставили оценку!'];
        BaseClass::redirect('/');
    }

    /**
     * Редактируем сообщение
     * @param $msg
     * @param $id
     */
    public static function setEdit($msg, $id) {

        $db = Db::getInstance();
        $sql = $db->prepare("UPDATE quest_book SET message = ? WHERE id = ?");
        $sql->execute([$msg, $id]);
        BaseClass::redirect('/');
    }

    /**
     * Чекаем, чьё сообщение
     * @param $id
     * @return bool
     */
    public static function checkOwner($id) {
        $db = Db::getInstance();
        $sql = $db->prepare('SELECT path_foto, message FROM quest_book WHERE id = ? AND id_user = ?');
        $sql->execute([$id, $_SESSION['user']]);
        $result = $sql->fetch();
        if ($result == false) {
            return false;
        }
        return $result;
    }

    /**
     * Возвращаем сообщения
     * @return array
     */
    public static function getListBook() {
        $db = Db::getInstance();

        $sql = $db->prepare("SELECT quest_book.*, users.login FROM quest_book, users WHERE quest_book.id_user = users.id ORDER BY id DESC");
        $sql->execute();

        return $sql->fetchAll();
    }

    public static function myHtml($str) {
        $str = trim($str);
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * Записть сообщения
     * @param $msg
     * @param null $path
     */
    public static function setMessage($msg, $path = null) {
        $db = Db::getInstance();
        $sql = $db->prepare("INSERT INTO quest_book (id_user, message, time_message, path_foto) VALUES (?, ?, ?, ?)");
        $sql->execute([$_SESSION['user'], $msg, time(), trim($path)]);
    }

    /**
     * Загрузка фото
     * @param null $file
     * @return array|bool|string
     */
    public static function uploadFile($file = null) {

        if (!empty($file)) {

            $handle = new Upload($file, 'ru_RU');
            if ($handle->uploaded) {

                $name = BaseClass::hash(microtime());
                $handle->file_new_name_body = $name;
                $handle->image_resize = true;
                $handle->image_x = 600;
                $handle->image_ratio_y = true;
                $handle->image_min_width = 100;
                $handle->image_min_height = 100;
                $handle->file_max_size = 1024 * 1000; // 1mb
                $handle->allowed = array('image/*');
                $handle->process(ROOT . '/file');

                $path = 'file/' . $name . '.' . $handle->file_src_name_ext;

                if ($handle->processed) {
                    return $path;
                }
                $_SESSION['message'] = [$handle->error];
                return false;
            }
        }
    }

    /**
     * Возвращаем всё, для записи в файл
     * @return array
     */
    public static function getAllBook() {
        $db = Db::getInstance();
        $data = $db->query('SELECT * FROM quest_book')->fetchAll();
        return $data;
    }
    /**
     * Все оценки
     * @return array
     */
    private static function getAllAverage() {
        $db = Db::getInstance();
        $data = $db->query('SELECT id, rating FROM quest_book')->fetchAll(PDO::FETCH_KEY_PAIR);
        return $data;
    }
}