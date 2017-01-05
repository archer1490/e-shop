<?php

/**
 * Class AdminCategoryController - управление категориями в админпанели
 */
class AdminCategoryController extends AdminBase
{
    /**Асtion для устаницы "управление категориями"
     * @return bool
     */
    public function actionIndex() {
        //Проверка прав доступа
        self::checkAdmin();

        $categoriesList = Category::getCategoriesListAdmin();

        require_once (ROOT. '/views/admin_category/index.php');
        return true;
    }

    /**Асtion для устаницы "удаления категории"
     * @param $id
     * @return bool
     */
    public function actionDelete($id) {

        self::checkAdmin();

        if (isset($_POST['submit'])) {

            //Product::deleteProductById($id);
            Category::deleteCategoryById($id);

            header("Location: /admin/product");
        }
        require_once (ROOT. '/views/admin_category/delete.php');
        return true;
    }

    /**Асtion для устаницы "добавление категории"
     * @return bool
     */
    public function actionCreate() {
        //Проверка прав доступа
        self::checkAdmin();

        //$categoriesList = Category::getCategoriesListAdmin();

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $sort_order = $_POST['sort_order'];
            $status = $_POST['status'];

            //ошибки в форме
            $errors = false;

            //Валидация полей
            if(!isset($name) || empty($name)) {
                $errors[] = 'Заполните поле название';
            }

            //Если ошибок нет - добавляем новый товар
            if ($errors == false) {
                //добавляем новый товар
                /*
                $id = Product::createProduct($options);

                if ($id) {
                    header("Location: /admin/product");
                }
                */
                Category::createCategory($name, $sort_order, $status);
                header("Location: /admin/category");
            }

        }
        require_once (ROOT. '/views/admin_category/create.php');
        return true;
    }

    /**Асtion для устаницы "редактирование категории"
     * @param $id
     * @return bool
     */
    public function actionUpdate($id)
    {
        //Проверка прав доступа
        self::checkAdmin();

        //$product = Product::getProductById($id);

        $category = Category::getCategoryById($id);

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $sort_order = $_POST['sort_order'];
            $status = $_POST['status'];

            //ошибки в форме
            $errors = false;

            //Валидация полей
            if (!isset($name) || empty($name)) {
                $errors[] = 'Заполните поле название';
            }

            //Если ошибок нет - обновляем информацию о товаре
            if ($errors == false) {
                if (Product::updateCategoryById($id, $name, $sort_order, $status)) {
                    header("Location: /admin/category");
                }
            }

        }
        require_once(ROOT . '/views/admin_category/update.php');
        return true;
    }
}