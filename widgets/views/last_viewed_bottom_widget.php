<?php

use yii\helpers\Url;

?>
    <div class="row product_last_prods">
        <span class="product_last_prods_header">Просмотренные товары</span>
        <div class="products_container">

            <?php foreach ($products as $product): ?>
                <div class="col-sm-6 col-md-3">
                    <div class="products_container_outer last_prod_bottom">
                        <a href="/<?= $product->model->slug ?>">
                            <div class="products_container_inner">
                                <?php if ($badge = $product->badge): ?>
                                    <div class="badge_ <?= $badge->class ?>"
                                         style="background-image: url('<?= \yii\helpers\Url::to('@web/images/badges/') . $badge->image ?>');<?= $badge->css ?>">
                                        <div><?= $badge->text ?></div>
                                    </div>
                                <?php endif; ?>
                                <div class="product_list_cont">
                                    <?php
                                    if ($product->image->medium == '') {
                                        $prod_img = '/web/img/no_small.jpg';
                                    } else {
                                        $prod_img = $product->image->medium;
                                    }
                                    ?>
                                    <div class="product_list_cont_img"
                                         style="background-image: url('<?= $prod_img ?>');"
                                         data-first_img="<?= $prod_img ?>"
                                         data-second_img="<?php $images = $product->images;
                                         if (isset($images[1])) echo $images[1]->medium; else echo $prod_img ?>"></div>
                                    <div class="underthumbcont">
                                        <div class="table">
                                            <div class="table_cell">
                                                <div class="product_list_cont_name">
                                                    <?= $product->type ?> <?= $product->model->vendorcode ?>
                                                </div>

                                                <div class="product_list_cont_oldprice">
                                                    <?php if ($product->model->discount): ?>
                                                        <span><?= $product->model->final_price ?> руб.</span> (-<?= $product->model->discount ?>%)
                                                    <?php endif ?>
                                                </div>
                                                <div class="product_list_cont_price"><?= $product->model->price ?> руб.</div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>


                </div>
            <?php endforeach; ?>
        </div>
    </div>


<?php

$script = <<< JS

$('.products_container_outer').hover(function () {
    old_img = $(this).find('.product_list_cont_img').data().first_img;
    new_img = $(this).find('.product_list_cont_img').data().second_img;
    $(this).find('.product_list_cont_img').css('background-image', 'url(' + new_img + ')')
}, function () {
    $(this).find('.product_list_cont_img').css('background-image', 'url(' + old_img + ')')
});
$('.product_list_cont_size_inner ul li').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    return false;
});

$('.product_list_cont_size_inner ul li.active').click(function () {
    $(this).parent('ul').children('li.active').removeClass('selected')
    $(this).addClass('selected');
})

JS;
$this->registerJS($script, yii\web\View::POS_READY, 'last_viewed_bottom_widget');
