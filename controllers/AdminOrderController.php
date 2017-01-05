<?php

/**
 * Class AdminOrderController - управление заказами в админпанели
 */
class AdminOrderController extends AdminBase
{
    /**Action для страницы "управление заказами"
     * @return bool
     */
    public function actionIndex() {
        //Проверка прав доступа
        self::checkAdmin();

        $ordersList = Order::getOrdersList();

        require_once (ROOT. '/views/admin_order/index.php');
        return true;
    }

    /**Action для страницы "удаление заказа"
     * @param $id
     * @return bool
     */
    public function actionDelete($id) {

        self::checkAdmin();

        if (isset($_POST['submit'])) {


            Order::deleteOrderById($id);
            header("Location: /admin/order");
        }
        require_once (ROOT. '/views/admin_order/delete.php');
        return true;
    }

    /**Action для страницы "просмотр заказа"
     * @param $id
     * @return bool
     */
    public function actionView($id) {
        self::checkAdmin();

        $order = Order::getOrderById($id);
        $productsQuantity = json_decode($order['products'], true);
        $productsIds = array_keys($productsQuantity);

        $products = Product::getProductsBYIds($productsIds);

        require_once (ROOT. '/views/admin_order/view.php');
        return true;
    }

    /**Action для страницы "редактирование заказа"
     * @param $id
     * @return bool
     */
    public function actionUpdate($id)
    {
        //Проверка прав доступа
        self::checkAdmin();

        $order = Order::getOrderById($id);

        $categoriesList = Category::getCategoriesListAdmin();

        if (isset($_POST['submit'])) {

            $status = $_POST['status'];
            Order::updateOrderById($id, $status);
            header("Location: /admin/order");
        }
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }
}