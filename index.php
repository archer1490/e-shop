<?php

//FRONT CONTROLLER

// Общие настройки
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
//начинаем сессию
    session_start();
// Поключение файлов системы
    define('ROOT', dirname(__FILE__));
    require_once (ROOT.'/components/Autoload.php');
    //require_once(ROOT."/components/Router.php");
    //require_once(ROOT."/components/Db.php");

// 4. Вызов Router
    $router = new Router();
    $router->run();