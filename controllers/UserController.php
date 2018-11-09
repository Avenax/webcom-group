<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 18:50
 */

class UserController extends BaseClass {

    /**
     * Регистрация
     * @return bool
     */
    public function actionRegister() {
        $title = 'Регистрация';
        $err = [];
        if (filter_has_var(INPUT_POST, 'submit')) {

            if (self::checkName($_POST['login']) == false) {
                $err[] = 'Логин должен быть не меньше, чем 2 символа!';
            }

            if (self::checkPassword($_POST['password']) == false) {
                $err[] = 'Пароль должен быть не меньше, чем 6 символов!';
            }

            if ($_POST['password'] != $_POST['password2']) {
                $err[] = 'Пароли не совпадают!';
            }

            if (User::checkRegUserData($_POST['login']) == false) {
                $err[] = 'Этот логин занят!';
            }

            if (empty($err)) {
                User::setRegister($_POST['login'], $_POST['password']);
            }

            $_SESSION['message'] = $err;
            self::redirect('/register');
        }

        require_once(ROOT . '/view/head.html');
        require_once(ROOT . '/view/reg.html');
        require_once(ROOT . '/view/foot.html');
        return true;
    }

    /**
     * Вход
     * @return bool
     */
    public function actionAuth() {
        $title = 'Вход';
        $err = [];
        if (filter_has_var(INPUT_POST, 'submit')) {

            if (self::checkName($_POST['login']) == false) {
                $err[] = 'Логин должен быть не меньше, чем 2 символа!';
            }

            if (self::checkPassword($_POST['password']) == false) {
                $err[] = 'Пароль должен быть не меньше, чем 6 символов!';
            }

            $id = User::checkUserData($_POST['login'], $_POST['password']);
            if ($id == false) {
                $err[] = 'Неверный логин или пароль!';
            }

            if (empty($err)) {
                User::setAuth($id);
                self::redirect('/');
            }

            $_SESSION['message'] = $err;
            self::redirect('/auth');
        }

        require_once(ROOT . '/view/head.html');
        require_once(ROOT . '/view/auth.html');
        require_once(ROOT . '/view/foot.html');
        return true;
    }
}