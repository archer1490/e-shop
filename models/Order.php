<?php

/**
 * Class Order - Модель для работы с заказами
 */
class Order
{
    /**Сохраняет информацию о заказе
     * @param $userName
     * @param $userPhone
     * @param $userComment
     * @param $userId
     * @param $productsInCart
     * @return bool
     */
    public static function save ($userName, $userPhone, $userComment, $userId, $productsInCart)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products) VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';

        $products = json_encode($productsInCart);
        $result = $db->prepare($sql);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->bindParam(':products', $products, PDO::PARAM_STR);
        return $result->execute();
    }

    /**Возвращает список заказов
     * @return mixed
     */
    public static function getOrdersList() {
        $db = Db::getConnection();

        $result = $db->query('SELECT id, user_name, user_phone, status, date FROM product_order');
        $productsList = array();

        $i = 0;

        while ($row = $result->fetch()) {
            $ordersList[$i]['id'] = $row['id'];
            $ordersList[$i]['user_name'] = $row['user_name'];
            $ordersList[$i]['user_phone'] = $row['user_phone'];
            $ordersList[$i]['date'] = $row['date'];
            $ordersList[$i]['status'] = $row['status'];
            $i++;
        }
        return $ordersList;
    }

    /**Удаляет заказ с указанным id
     */
    public static function deleteOrderById($id) {
        $db = Db::getConnection();

        $sql = 'DELETE FROM product_order WHERE id = :id';
        //Получение и возврат результата с помощью подготовленного запроса
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**Возвращает информацию о заказе с указанным id
     * @param bool $id
     * @return bool
     */
    public static function getOrderById($id = false) {
        if ($id) {
            $db = Db::getConnection();
            $product = array();
            $result = $db->prepare('SELECT * FROM product_order WHERE id = :id');
            $result->bindParam(':id', $id, PDO::PARAM_STR);
            return $result->execute();
            return $result->fetch();

        }
    }

    /**Обновляет информацию о заказе с указанным id
     * @param $id
     * @param $status
     * @return bool
     */
    public static function updateOrderById($id, $status) {
        $db = Db::getConnection();

        $sql = 'UPDATE product_order SET status = :status WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }


    /**Возвращает текстовую информацию о статусе заказа
     * @param $status
     * @return string
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'В обработке';
                break;
            case '3':
                return 'Доставляется';
                break;
            case '4':
                return 'Доставляется';
                break;
        }
    }
}