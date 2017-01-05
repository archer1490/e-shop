<?php

/**
 * Class CabinetController - личный кабинет
 */
class CabinetController
{
    /**Action для личного кабинета
     * @return bool
     */
    public function actionIndex()
    {
        $userId = User::checkLogged();
        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }
}