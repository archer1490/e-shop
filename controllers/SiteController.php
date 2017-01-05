<?php

//include_once ROOT.'/models/Category.php';
//include_once ROOT.'/models/Product.php';
class SiteController
{
    /**Action для страницы товара
     * @return bool
     */
    public function actionIndex() {
        $categories = array();
        $categories = Category::getCategoriesList();

        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(6);
        $recomendedProducts = array();
        $recomendedProducts = Product::getRecomendedProducts();
        //print_r($recomendedProducts);
        require_once(ROOT.'/views/site/index.php');
        return true;
    }


}