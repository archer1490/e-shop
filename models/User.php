<?php

/**
 * Модель для работы с пользователем*/
class User
{
    /**
     * Регистрация пользователя
     * @param $name
     * @param $email
     * @param $password
     * @return bool
     */
    public static function register($name, $email, $password) {
        $db = Db::getConnection();

        $sql = 'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)';
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();

    }

    /**Проверяет существует ли пользователь с задаными email и пароль
     * @param $email
     * @param $password
     * @return bool
     */
    public static function checkUserData($email, $password) {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        $user = $result->fetch();
        if($user) {
            return true;
        }
        return false;
    }

    /**запоминает данные пользователя в сессию
     * @param $userId
     */
    public static function auth($userId) {
        //session_start();
        $_SESSION['user'] = $userId;
    }

    /**
     * Проверяет авторизирован ли пользователь
     * @return mixed
     */
    public static function checkLogged() {
        //session_start();
        //Если сессия есть, возвращаем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        header('Location: /user/login');
    }

    /**проверяет является ли пользователь гостем (не авторизирован)
     * @return bool
     */
    public static function isGuest() {

        //session_start();
        if (isset($_SESSION['user'])){
            return false;
        }
        return true;
    }

    /**проверяет имя, не меньше чем 2 символа
     * @param $name
     * @return bool
     */
    public static function checkName($name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**проверяет имя, не меньше чем 6 символов
     * @param $password
     * @return bool
     */
    public static function checkPassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }


    /**Проверяет коректность email
     * @param $email
     * @return bool
     */
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет телефон, не меньше 10 символов
     * @param $phone
     * @return bool
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

    /**Проверяет существует ли пользователь у указанным email
     * @param $email
     * @return bool
     */
    public static function checkEmailExists($email) {
        $db = Db::getConnection();
        $sql = 'SELECT COUNT (*) FROM user WHERE email = :email';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if($result->fetchColumn()) {
            return true;
        }
        return false;
    }

    /**Возвращает данные о пользователе по id
     * @param $id
     * @return mixed
     */
    public static function getUserById($id = false) {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE id = :id';
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
        }
    }
