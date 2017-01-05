<?php


abstract class AdminBase
{
    /**Проверяет является ли пользователь админом
     * @return bool
     */
    public static function checkAdmin() {

        $userId = User::checkLogged();

        $user = User::getUserById($userId);

        //Если пользователь - администратор, пускаем его в админпанель

        if ($user['role'] == 'admin') {
            return true;
        }

        die('Доступ запрещен');
    }
}