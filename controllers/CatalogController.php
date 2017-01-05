<?php

/**
 * Class CatalogController - каталогконтроллер
 */
class CatalogController
{
    /**Action для каталога товаров
     * @return bool
     */
    public function actionIndex() {
        $categories = array();
        $categories = Category::getCategoriesList();

        $categoryProducts = array();
        $categoryProducts = Product::getLatestProducts(6);
        require_once(ROOT.'/views/catalog/index.php');
        return true;
    }

    /**Action для просмотра товаров в категориях
     * @param $categoryId
     * @param int $page
     * @return bool
     */
    public function actionCategory($categoryId, $page = 1) {
        $categories = array();
        $categories = Category::getCategoriesList();
        $categoryProducts = array();
        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);
        $total = Product::getTotalProductsInCategory($categoryId);
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');
        require_once (ROOT.'/views/catalog/category.php');
        return true;
    }
}

