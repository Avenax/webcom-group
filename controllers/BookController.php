<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 21:18
 */

class BookController {

    /**
     * Удаление
     * @param $id
     */
    public function actionDelete($id) {

        $check = GuestBook::checkOwner($id);
        if ($check != false) {
            if (!empty($check['path_foto'])) {
                unlink($check['path_foto']);
            }

            $db = Db::getInstance();
            $sql = $db->prepare("DELETE FROM quest_book WHERE id = ?");
            $sql->execute([$id]);
        }
        BaseClass::redirect('/');
    }

    /**
     * Оценка
     * @param $id
     * @param $rat
     */
    public function actionRating($id, $rat) {

        $list = GuestBook::getList(true);

        if (is_array($list) && in_array($id, $list)) {
            $_SESSION['message'] = ['Вы уже делали оценку!'];
        } else {
            GuestBook::setList($id, $rat);
        }
    }

}