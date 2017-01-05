<?php
class UserController
{
    /**Action для регистрации пользователей
     * @return bool
     */
    public function actionRegister()
    {

        $name = '';
        $email = '';
        $password = '';

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;


            if (!User::checkName($name)) {
                $errors[] = 'Имя должно быть не меньше 2-х символов';
            }

            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }

            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль должен быть не меньше 6 символов';
            }
            if (!User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            if ($errors == false) {
                $result = User::register($name, $email, $password);
            }
        }


        require_once(ROOT . '/views/user/register.php');
        return true;

    }

    /**Action для входа пользователя на сайт
     * @return bool
     */
    public function actionLogin()
    {

        $email = '';
        $password = '';

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            echo $email;
            echo $password;

            $errors = false;


            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }

            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль должен быть не меньше 6 символов';
            }
            $userId = User::checkUserData($email, $password);
            var_dump($userId);
            if ($userId == false) {
                //Если данные не правильные
                $errors[] = 'Неправильные данные входа на сайт';
            } else {
                //Если данные правильные запоминаем в сессию
                User::auth($userId);
                //Перенаправляем пользователя в личный кабинет
                header("Location: /cabinet/");
            }
        }
        require_once (ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * удаляет данные пользователя из сессии
     */
    public function actionLogout() {

        session_start();
        unset($_SESSION['user']);
        header('Location: /');
    }


}



