<?php

/**
 * Class AdminController для админстраницы
 */
class AdminController extends AdminBase
{
    /**Action для админстраницы
     * @return bool
     */
    public function actionIndex() {

        //проверка прав доступа
        self::checkAdmin();

        //подключаем вид
        require_once (ROOT . '/views/admin/index.php');
        return true;

    }
}