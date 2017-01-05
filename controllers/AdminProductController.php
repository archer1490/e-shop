<?php

/**
 * Class AdminProductController - управление товарами в админпанели
 */
class AdminProductController extends AdminBase
{
    /**Action для страницы "управление товарами"
     * @return bool
     */
    public function actionIndex() {
        //Проверка прав доступа
        self::checkAdmin();

        $productsList = Product::getProductsList();

        require_once (ROOT. '/views/admin_product/index.php');
        return true;
    }

    /**Action для страницы "удаление товара"
     * @param $id
     * @return bool
     */
    public function actionDelete($id) {

        self::checkAdmin();

        if (isset($_POST['submit'])) {

            Product::deleteProductById($id);

            header("Location: /admin/product");
        }
        require_once (ROOT. '/views/admin_product/delete.php');
        return true;
    }

    /**Action для страницы "добавление товара"
     * @return bool
     */
    public function actionCreate() {
        //Проверка прав доступа
        self::checkAdmin();

        $categoriesList = Category::getCategoriesListAdmin();

        if (isset($_POST['submit'])) {
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recomended'] = $_POST['is_recomended'];
            $options['status'] = $_POST['status'];

            //ошибки в форме
            $errors = false;

            //Валидация полей
            if(!isset($options['name']) || empty($options['name'])) {
                $errors[] = 'Заполните поле название';
            }

            //Если ошибок нет - добавляем новый товар
            if ($errors == false) {
                //добавляем новый товар
                $id = Product::createProduct($options);

                if ($id) {
                    // Проверим, загружалось ли через форму изображение
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        // Если загружалось, переместим его в нужную папке, дадим новое имя
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                };
                // Перенаправляем пользователя на страницу управлениями товарами
                header("Location: /admin/product");
            }
            }


        require_once (ROOT. '/views/admin_product/create.php');
        return true;
    }

    /**Action для страницы "редактирование товара"
     * @param $id
     * @return bool
     */
    public function actionUpdate($id)
    {
        //Проверка прав доступа
        self::checkAdmin();

        $product = Product::getProductById($id);

        $categoriesList = Category::getCategoriesListAdmin();

        if (isset($_POST['submit'])) {
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recomended'] = $_POST['is_recomended'];
            $options['status'] = $_POST['status'];

            //ошибки в форме
            $errors = false;

            //Валидация полей
            if (!isset($options['name']) || empty($options['name'])) {
                $errors[] = 'Заполните поле название';
            }

            //Если ошибок нет - обновляем информацию о товаре
            if ($errors == false) {
                if (Product::updateProductById($id, $options)) {
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        // Если загружалось, переместим его в нужную папке, дадим новое имя
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                    header("Location: /admin/product");
                }
            }

        }
            require_once(ROOT . '/views/admin_product/update.php');
            return true;
        }
}
