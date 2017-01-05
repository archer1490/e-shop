<?php include ROOT.'/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Каталог</h2>
                    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
                        <?php foreach ($categories as $categoryItem): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="/category/<?php echo $categoryItem['id'];?>"
                                           class="<?php if ($categoryId == $categoryItem['id']) echo 'active' ?>"
                                        >
                                            <?php echo $categoryItem['name'];?>
                                        </a></h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div><!--/category-products-->

                </div>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="product-details"><!--product-details-->
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="view-product">
                                <img src="../..<?php echo Product::getImage($product['id']); ?>" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <?php if($product['is_new']): ?>
                                <img src="../../template/images/product-details/new.jpg" class="newarrival" alt="" />
                                <?php endif; ?>
                                <h2><?php echo $product['name']; ?></h2>
                                <p>Код товара: 1089772</p>
                                <span>
                                            <span>US $<?php echo $product['price']; ?></span>

                                    <a href="/cart/add/<?php echo $product['id']; ?>" class="btn btn-fefault cart"></i>В корзину</a>
                                        </span>
                                <p><b>Наличие:</b><?php echo Product::getAvailabilityText($product['availability']); ?> </p>
                                <p><b>Производитель:</b><?php echo $product['brand']; ?></p>
                            </div><!--/product-information-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Описание товара</h5>
                            <p><?php echo $product['description']; ?></p>
                        </div>
                    </div>
                </div><!--/product-details-->

            </div>
        </div>
    </div>
</section>


<br/>
<br/>
<?php include ROOT.'/views/layouts/footer.php'; ?>
