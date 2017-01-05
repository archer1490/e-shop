<?php

/**
 * Class Router - для работы с маршрутами
 */
class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Возвращает строку запроса
     * @return string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        //получаем строку запроса
        $uri = $this->getURI();
        //echo $uri;
        //Проверяем наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {

            //сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {


                //получаем внутрений путь из внешнего согласно правилу

                $interalRoute = preg_replace("~$uriPattern~", $path, $uri);
                //определяем контроллер, метод (action) и параметры
                $segments = explode('/', $interalRoute);
                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);
                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;
                // Подключаем класс файла-контроллера
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                // Создаем объект, вызываем метод (action)
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if ($result != null) {
                   die;
                }
            }
        }
    }
}