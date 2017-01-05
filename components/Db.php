<?php

/**
 * Class Db - устанавливает соединение с базой данных
 */
class Db
{
    public static function getConnection()
    {
        $paramsPath = ROOT.'/config/db_params.php';
        $params = include($paramsPath);

        try {
            $db = new PDO($params['dsn'], $params['user'], $params['password']);
            $db->query( "SET CHARSET utf8" );
            return $db;

        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }
}