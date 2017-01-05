<?php

/**
 * Class CartController - контроллер для корзины
 */
class CartController
{
    /**Добавляет товар в корзину
     * @param $id
     */
    public function actionAdd($id) {
        //добавляем товар в корзину
        Cart::addProduct($id);
        // Возвращаем пользователя на страницу с которой он пришел
        header('Location:/product/'.$id);
    }

    /**Action для корзины
     * @return bool
     */
    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        //получаем товары из корзины
        $productsInCart = Cart::getProducts();

        if ($productsInCart) {
            //получаем информацию о товарах из списка
            $productsIds = array_keys($productsInCart);
            $products = Product::getProductsBYIds($productsIds);

            //получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }

        require_once(ROOT . '/views/cart/index.php');
        return true;
    }

    /**
     * Удаляет товары из корзины
     * @param integer $id <p>Id товара</p>
     */
    public function actionDelete($id) {
        Cart::deleteProduct($id);
        // Возвращаем пользователя в корзину
        header("Location: /cart");
    }

    //Оформление заказа
    public function actionCheckout()
     {
         // Получием данные из корзины
         $productsInCart = Cart::getProducts();
         // Если товаров нет, отправляем пользователи искать товары на главную
         if ($productsInCart == false) {
             header("Location: /");
         }
         // Список категорий для левого меню
         $categories = Category::getCategoriesList();
         // Находим общую стоимость
         $productsIds = array_keys($productsInCart);
         $products = Product::getProductsBYIds($productsIds);
         $totalPrice = Cart::getTotalPrice($products);
         // Количество товаров
         $totalQuantity = Cart::countItem();
         // Поля для формы
         $userName = false;
         $userPhone = false;
         $userComment = false;
         // Статус успешного оформления заказа
         $result = false;
         // Проверяем является ли пользователь гостем
         if (!User::isGuest()) {
             // Если пользователь не гость
             // Получаем информацию о пользователе из БД
             $userId = User::checkLogged();
             $user = User::getUserById($userId);
             $userName = $user['name'];
         } else {
             // Если гость, поля формы останутся пустыми
             $userId = false;
         }
         // Обработка формы
         if (isset($_POST['submit'])) {
             // Если форма отправлена
             // Получаем данные из формы
             $userName = $_POST['userName'];
             $userPhone = $_POST['userPhone'];
             $userComment = $_POST['userComment'];
             // Флаг ошибок
             $errors = false;
             // Валидация полей
             if (!User::checkName($userName)) {
                 $errors[] = 'Неправильное имя';
             }
             if (!User::checkPhone($userPhone)) {
                 $errors[] = 'Неправильный телефон';
             }
             if ($errors == false) {
                 // Если ошибок нет
                 // Сохраняем заказ в базе данных
                 $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);
                 if ($result) {
                     // Очищаем корзину
                     Cart::clear();
                 }
             }
         }
         // Подключаем вид
         require_once(ROOT . '/views/cart/checkout.php');
         return true;
     }
}