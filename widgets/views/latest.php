<?php

use yii\helpers\Url;

if (!count($data)) return;

?>

    <div class="mainpage_news">
        <h2>Новинки</h2>
        <div class="products_container">
            <div class="container">
                <div class="row">
                    <?php foreach ($data as $product): ?>
                        <div class="col-sm-6 col-md-3">
                            <a href="/<?= $product->model->slug ?>">
                                <div class="products_container_outer">
                                    <div class="products_container_inner">
                                        <?php if ($badge = $product->badge): ?>
                                            <div class="badge_ <?= $badge->class ?>"
                                                 style="background-image: url('<?= \yii\helpers\Url::to('@web/images/badges/') . $badge->image ?>');<?= $badge->css ?>">
                                                <div><?= $badge->text ?></div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="product_list_cont">
                                            <div class="product_list_cont_img"
                                                 style="background-image: url('<?= $product->image->medium ?>');"
                                                 data-first_img="<?= $product->image->medium ?>"
                                                 data-second_img="<?php $images = $product->images;
                                                 if (isset($images[1])) echo $images[1]->medium; else echo $product->image->medium ?>"></div>
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
                                            <div class="product_list_cont_size">
                                                <?= count($product->model->sizeRange->sizes) ? 'Размер:' : '' ?>
                                                <div class="product_list_cont_size_inner">
                                                    <ul>
                                                        <?php if ($product->model->sizes) { ?>
                                                            <?php $avalibleSizes = \yii\helpers\ArrayHelper::index($product->model->sizes, 'id');
                                                            $first = true;
                                                            $select = '';
                                                            foreach ($product->model->sizeRange->sizes as $size): ?>
                                                                <li data-size-id="<?= $size->id ?>" <?php
                                                                $sizetitle = 'title="На данный момент товара нет в наличии"';
                                                                if (isset($avalibleSizes[$size->id])) {
                                                                    if ($first) {
                                                                        $first = false;
                                                                        $select = 'selected';
                                                                    } else $select = '';
                                                                    echo 'class="active ' . $select . '"';
                                                                    $sizetitle = '';
                                                                } echo $sizetitle;?>><?= $size->name ?></li>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <?php if($product->model->colors[0]['name'] != 'Как на фото'){ ?>
                                                    <div class="product_list_cont_color">
                                                        Цвета:
                                                        <div class="product_list_cont_color_inner">
                                                            <?php
                                                            foreach ($product->model->colors as $color){
                                                                $border_color = '';
                                                                $bgmulti = '';
                                                                if($color['name'] == 'Мультиколор'){
                                                                    $bgmulti = 'background: url(/web/img/multi.jpg) no-repeat center center;background-size: contain;';
                                                                }
                                                                if($color['code'] == '#ffffff'){
                                                                    $border_color = 'border: 1px solid #ccc;';
                                                                }
                                                                echo '<div class="thumb_color" title="'.$color['name'].'" style="background-color:'.$color['code'].';'.$border_color.''.$bgmulti.'"></div>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                            <div class="product_list_cont_button">
                                                <a data-product="<?= $product->id ?>" href="##"
                                                   class="incart_list button">в корзину</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <a href="<?= Url::to(['/catalog/latest']) ?>" class="standart_button news_button">Все новинки</a>
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
$this->registerJS($script, yii\web\View::POS_READY, 'thumbnail');