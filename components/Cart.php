<?php

/**
 * Class Cart - Класс для работы с корзиной
 */
class Cart
{
    /** Добавляет товар в корзину
     * @param $id
     */
    public static function addProduct($id){

        $id = intval($id);

        //пустой массив для товаров в корзине
        $productsInCart = array();

        //Если в корзине уже есть товары (они хранятся в сессии)
        if(isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];
        }

        //Если товар есть в корзине, но был добавлен еще раз, увеличиваем количество
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id]++;
        } else {
            //Добавляем товар в корзину
            $productsInCart[$id] = 1;
        }

        $_SESSION['products'] = $productsInCart;
        //print_r( $_SESSION['products']);
    }

    /**Возвращает количество товаров в корзине
     * @return int
     */
    public static function countItem() {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    /**Проверяет есть ли товары в корзине
     * @return bool
     */
    public static function getProducts() {
        if (isset($_SESSION['products'])) {
            return  $_SESSION['products'];
        }
        return false;
    }

    /**Возвращает общую стоимость товаров в корзине
     * @param $products
     * @return int
     */
    public static function getTotalPrice($products) {

        $productsInCart = self::getProducts();

        $total = 0;

        if ($productsInCart) {
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }
    return $total;
    }

    /**
     * Очищает корзину
     */
    public static function clear() {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }

    /**
     * Удаляет товар с указанным id из корзины
     * @param integer $id
     */
    public static function deleteProduct($id) {
        //Получаем массив с идентификаторами и коичеством товаров в корзине
        $productsInCart = self::getProducts();
        //Удаляем элемент с указанным Id
        unset($productsInCart[$id]);
        //Записываем массив товаров в сессию
        $_SESSION['products'] = $productsInCart;
    }
}

