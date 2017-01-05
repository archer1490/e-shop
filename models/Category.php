<?php

/**
 * Class Category - модель для работы с категориями
 */
class Category
{
    /**Возвращает список категорий для пользователя (только доступные категории)
     * @return array
     */
    public static function getCategoriesList() {
        $db = Db::getConnection();
        $categoryList = array();
        $result = $db->query('SELECT id, name FROM category WHERE status = 1 ORDER BY sort_order ASC');

        $i=0;
        while ($row = $result->fetch()) {
            $categoryList [$i]['id'] = $row['id'];
            $categoryList [$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

    /**Возвращает список категорий для администратора (все категории)
     * @return array
     */
    public static function getCategoriesListAdmin() {
        $db = Db::getConnection();
        $categoryList = array();
        $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');

        $i=0;
        while ($row = $result->fetch()) {
            $categoryList [$i]['id'] = $row['id'];
            $categoryList [$i]['name'] = $row['name'];
            $categoryList [$i]['sort_order'] = $row['sort_order'];
            $categoryList [$i]['status'] = $row['status'];
            $i++;
        }
        return $categoryList;
    }

    /**Возвращает информацию о категории с указанным id
     * @param $id
     * @return mixed
     */
    public static function getCategoryById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = 'SELECT * FROM category WHERE id = :id';
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        // Выполняем запрос
        $result->execute();
        // Возвращаем данные
        return $result->fetch();
    }

    //CRUD
    /**Удаляет категорию с указанным id
     * @param $id
     * @return bool
     */
    public static function deleteCategoryById($id) {
        $db = Db::getConnection();

        $sql = 'DELETE FROM product WHERE id = :id';
        //Получение и возврат результата с помощью подготовленного запроса
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**Создает категорию
     * @param $name
     * @param $sort_order
     * @param $status
     * @return bool
     */
    public static function createCategory($name, $sort_order, $status) {
        $db = Db::getConnection();

        $sql = 'INSERT INTO category (name, sort_order, status) VALUES (:name, :sort_order, :status)';

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

    /**Обновляет информацию о категории с указанным id
     * @param $id
     * @param $name
     * @param $sort_order
     * @param $status
     * @return bool
     */
    public static function updateCategoryById($id, $name, $sort_order, $status) {
        $db = Db::getConnection();

        $sql = 'UPDATE product SET name = :name, status_order = :status_order, status = :status WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':status_order', $sort_order, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

    /**Возвращает текстовую информацию о статусе категории
     * @param $status
     * @return string
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Отображается';
                break;
            case '0':
                return 'Скрыта';
                break;
        }
    }
}