<?php

/**
 * Class Product - модель для работы с товарами
 */
class Product
{
    const SHOW_BY_DEFAULT = 6; //дефолтное количество товаров на странице

    /**Возвращает массив последних товаров
     * @param int $count
     * @return array
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT) {


        $count = intval($count);
        //$page = intval($page);
        //$offset = ($page - 1) * $count;
        $db = Db::getConnection();

        $productList = array();

        $result = $db->prepare('SELECT id, name, price, image, is_new FROM product WHERE status = 1 ORDER BY id DESC LIMIT :count');
        $result->bindParam(':count', $count, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполнение коменды
        $result->execute();
        $i = 0;

        while ($row = $result->fetch()) {
            $productList[$i]['id'] = $row['id'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['image'] = $row['image'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productList;
    }

    /**Возвращает массив товаров в заданой категории
     * @param bool $categoryId
     * @param int $page
     * @return array
     */
    public static function getProductsListByCategory($categoryId = false, $page = 1) {
        if ($categoryId) {

            $page = intval($page);
            $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
            $limit = Product::SHOW_BY_DEFAULT;

            $db = Db::getConnection();
            $products = array();
            $result = $db->prepare('SELECT id, name, price, image, is_new FROM product WHERE status = 1 AND category_id = :categoryId  ORDER BY id DESC LIMIT :limit OFFSET :offset');
            $result->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $result->bindParam(':limit', $limit, PDO::PARAM_INT);
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);

            $result->setFetchMode(PDO::FETCH_ASSOC);

            // Выполнение коменды
            $result->execute();

            $i = 0;

            while ($row = $result->fetch()) {
                $products[$i]['id'] = $row['id'];
                $products[$i]['name'] = $row['name'];
                $products[$i]['image'] = $row['image'];
                $products[$i]['price'] = $row['price'];
                $products[$i]['is_new'] = $row['is_new'];
                $i++;
            }
            return $products;
        }
    }

    /**Возвращает массив рекомендуемых товаров
     * @return array
     */
    public static function getRecomendedProducts() {
        $db = Db::getConnection();

        $result = $db->query('SELECT id, name, price FROM product WHERE is_recomended = 1');
        $productsList = array();

        $i = 0;

        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $i++;
        }
        return $productsList;
    }
    /**Возвращает товар с указанным id
     * @param bool $id
     * @return mixed
     */
    public static function getProductById($id = false) {
        if ($id) {
            $db = Db::getConnection();
            $product = array();
            $result = $db->prepare('SELECT id, name, code, availability, description, brand, price, image, is_new, is_recomended, status FROM product WHERE status = 1 AND id = :id');

            $result->bindParam(':id', $id, PDO::PARAM_INT);

            $result->setFetchMode(PDO::FETCH_ASSOC);

            // Выполнение коменды
            $result->execute();

            return $result->fetch();

        }
    }

    /**Возвращает количество товаров в категории
     * @param $categoryId
     * @return mixed
     */
    public static function getTotalProductsInCategory($categoryId) {
        $db = Db::getConnection();
        $result = $db->prepare('SELECT count(id) AS count FROM product WHERE status = 1 AND category_id = :categoryId');
        $result->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $row = $result->fetch();
        return $row['count'];
    }

    /**Возвращает список товаров с указанными индентификторами
     * @param $idsArray
     * @return array
     */
    public static function getProductsBYIds($idsArray){
        $products = array();
        $db = Db::getConnection();
        $idsString = implode(' , ', $idsArray);

        $sql = 'SELECT * FROM product WHERE status = 1 AND id IN ('.$idsString.')';

        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $i = 0;

        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
        return $products;
    }

    /**Возвращает список всех товаров
     * @return array
     */
    public static function getProductsList() {
        $db = Db::getConnection();

        $result = $db->query('SELECT id, name, code, price FROM product ORDER BY id ASC');
        $productsList = array();

        $i = 0;

        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['code'] = $row['code'];
            $productsList[$i]['price'] = $row['price'];
            $i++;
        }
        return $productsList;
    }

    /**Удаляет товар с указанным id
     * @param $id
     * @return bool
     */
    public static function deleteProductById($id) {
        $db = Db::getConnection();

        $sql = 'DELETE FROM product WHERE id = :id';
        //Получение и возврат результата с помощью подготовленного запроса
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**Добавляет новый товар
     * @param $options
     * @return int|string
     */
    public static function createProduct($options) {
        $db = Db::getConnection();

        $sql = 'INSERT INTO product (name, category_id, code, price, availability, brand, description, is_new, is_recomended, status) VALUES (:name, :category_id, :code, :price, :availability, :brand, :description, :is_new, :is_recomended, :status)';

        $result = $db->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recomended', $options['is_recomended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        } else {
            return 0;
        }
    }

    /**Обновляет информацию о товаре
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateProductById($id, $options) {
        $db = Db::getConnection();

        $sql = 'UPDATE product SET name = :name, category_id = :category_id, code = :code, price = :price, availability = :availability, brand = :brand, description = :description, is_new = :description, is_recomended = :is_recomended, status = :status WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recomended', $options['is_recomended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        return $result->execute();
        }

    /**Возвращает текстое пояснение наличия товара:
     * @param $availability
     * @return string
     */
        public static function getAvailabilityText($availability) {
            switch ($availability) {
                case '1':
                    return 'В наличии';
                    break;
                case '0':
                    return 'Под заказ';
                    break;
            }
        }

    /**Возвращает путь к изображению товара
     * @param $id
     * @return string
     */
        public static function getImage($id) {
            $noImage = 'no-image.jpg';

            $path = '/upload/images/products/';

            $pathToProductImage = $path.$id.'.jpg';

            if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage)) {
                return $pathToProductImage;
            }

            return $path.$noImage;
        }
    }