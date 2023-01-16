<?php

use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\widgets\Breads;
use yii\helpers\Url;
use app\models\Seo;

if (!Seo::SetSeo(3)) $this->title = 'Корзина';
$promocode = $order->promo;

?>

    <main>
        <div class="page cart page_cart">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?= Breads::widget([
                            'path' => [
                                'Корзина' => ''
                            ],
                            'home' => Url::to(['catalog/index'])
                        ]) ?>
                        <h1>Корзина</h1>
                    </div>
                </div>
                <div class="page_cart_body">
                    <?php if ($totalCount != 0) { ?>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="popup_cart_cart_inner2">

                                <div class="popup_cart_cart_inner_item clearfix" style="border-bottom:none">
                                    <a class="popup_cart_cart_inner_item_photo"
                                    ></a>
                                    <div class="popup_cart_cart_inner_item_info">
                                        <div class="table">
                                            <div class="table_cell">
                                                <div class="popup_cart_cart_inner_item_info_top clearfix">
                                                    <div class="cart_page_in_type">
                                                        <div class="table">
                                                            <div class="table_cell"> Тип</div>
                                                        </div>
                                                    </div>
                                                    <div class="cart_page_in_name">
                                                        <div class="table">
                                                            <div class="table_cell"> Название
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="cart_page_in_ven">
                                                        <div class="table">
                                                            <div class="table_cell">Артикул</div>
                                                        </div>
                                                    </div>
                                                    <div class="cart_page_in_size">
                                                        <div class="table">
                                                            <div class="table_cell">
                                                                <span>Размер</span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="popup_cart_cart_inner_item_count">
                                        <div class="popup_cart_cart_inner_item_count_center">
                                            <div class="prod_body_top_left_top prod_form_item">
                                                <div class="table">
                                                    <div class="table_cell">
                                                        Цвет
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="popup_cart_cart_inner_item_price">
                                        <div class="popup_cart_cart_inner_item_info_bot">
                                            <div class="table">
                                                <div class="table_cell">
                                                    Цена
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="popup_cart_cart_inner_item_remove">
                                        <div class="table">
                                            <div class="table_cell">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="popup_cart_cart_inner">

                            </div>
                            <div class="popup_cart_res">
                                <div class="popup_cart_cart_inner_item_info_bot">
                                    Всего товаров: <span class="total_price"><?= $totalCount ?> </span> шт.
                                </div>
                                <div class="popup_cart_cart_inner_item_info_bot">
                                    На сумму: <span class="result_price"><?= $resultPrice ?> </span> руб.
                                </div>
                                <div> <?php if (!empty($promocode)): ?>
                                        Код: <?= $promocode->code ?>, скидка: <?= $promocode->discount ?> %
                                    <?php endif; ?></div>
                            </div>
                        </div>

                    </div>


                    <!--            <div class="row">-->
                    <!--                <div class="col-sm-3">-->
                    <!--                --><?php //$form = ActiveForm::begin([
                    //                    'method' => 'post'
                    //                ]) ?>
                    <!--                --><? //= $form->field($promo, 'captcha')->widget(Captcha::classname()) ?>
                    <!--                --><? //= $form->field($promo, 'code') ?>
                    <!--                --><? //= Html::submitButton("Применить код", ['class' => 'btn btn-primary']) ?>
                    <!--                --><?php //ActiveForm::end() ?>
                    <!--                </div>-->
                    <!---->
                    <!--            </div>-->


                    <?= $this->render('form', ['order' => $order, 'delivery_methods' => $delivery_methods, 'pay_methods' => $pay_methods, 'regions' => $regions, 'promocode' => $promocode, 'totalCount' => $totalCount, 'resultPrice' => $resultPrice, 'userdiscount' => $userdiscount]) ?>
                </div>
                <?php } else { ?>
                    Корзина пуста
                <?php } ?>
            </div>
        </div>

        </div>
    </main>

<?php
$script = <<< JS

$.post('/cart/ajax_get_page', { promo: $('input[name="Order[promocode]"]').val() }, function(data){
    $('.popup_cart_cart_inner').html(data.content);
}, 'json').fail(function(xhr, text, error){
    console.log(error);
}).done(function(){
$('body').find('.color_picker select').styler({
            'selectSmartPositioning': false,
        });
});

JS;

$this->registerJS($script);
