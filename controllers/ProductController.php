<?php


class ProductController
{
    /**Action для просмотра товара
     * @param $id
     * @return bool
     */
    public function actionView ($id) {

        $categories = array();
        $categories = Category::getCategoriesList();

        $product = array();
        $product = Product::getProductById($id);
        require_once (ROOT.'/views/product/view.php');
        return true;
    }
}