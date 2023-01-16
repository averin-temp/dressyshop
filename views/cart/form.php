<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use app\models\User;

use yii\helpers\Url;

$promocode = $promocode ? $promocode->code : '';
$regions_base = $regions;
?>

    <div class="row cart_form">
        <?php $ajcheck = false;
        if (\Yii::$app->user->isGuest) {
            $ajcheck = true;
        } ?>
        <?php $form = ActiveForm::begin([
            'id' => 'order-form',
            'action' => '/order/order',
            'enableAjaxValidation' => $ajcheck,
//            'validationUrl' => Url::to(['/order/alidatefs']),
            'fieldConfig' => [
                'options' => [
                    'tag' => 'div'
                ]
            ]
        ]) ?>
        <div class="col-md-6 cart_form_inputs">
            <!--            <pre>-->
            <!--            --><?php //var_dump(\Yii::$app->user->identity->attributes);?>
            <!--            </pre>-->
            <h2>Оформление заказа<?php if (\Yii::$app->user->isGuest): ?><a href="##" class="haveacc"
                                                                            title="Нажмите для авторизации">У меня уже
                    есть аккаунт</a><?php endif; ?></h2>

            <?= $form->field($order, 'delivery_price', ['inputOptions' => ['type' => 'hidden', 'class' => 'delivery_price']])->label(false); ?>
            <?= $form->field($order, 'lastname', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->lastname]])->label('Фамилия*', ['class' => false]); ?>
            <?= $form->field($order, 'firstname', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->firstname]])->label('Имя*', ['class' => false]); ?>

            <?php
            //            echo $form->field($order, 'name')->begin();
            //            echo Html::activeLabel($order,'name',['label'=>'Имя']);
            //            echo Html::activeTextInput($order, 'name');
            //            echo Html::error($order,'name',['class'=>'help-block']);
            //            echo $form->field($order, 'name')->end();
            ?>

            <?= $form->field($order, 'patronymic', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->patronymic]])->label('Отчество*', ['class' => false]); ?>
            <?= $form->field($order, 'phone', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->phone]])->label('Телефон*', ['class' => false]); ?>
            <?php if (\Yii::$app->user->isGuest): ?>
                <?= $form->field($order, 'email', ['inputOptions' => ['type'=>'email','class' => false]])->label('Email*', ['class' => false]); ?>
            <?php else : ?>
                                <?= $form->field($order, 'email', ['inputOptions' => ['readonly' => 'readonly', 'class' => false, 'value' => \Yii::$app->user->identity->email]])->label('Email*', ['class' => false]); ?>
            <?php endif; ?>
            <?= $form->field($order, 'zip_code', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->zip_code]])->label('Индекс*', ['class' => false]); ?>
            <div class="form-group field-user-region">
                <label class="control-label" for="order-preferred_region">Регион*</label>
                <select id='order-region' class="form-control" name='Order[region]'>
                    <option <?= !\Yii::$app->user->identity->region ? 'selected' : '' ?> >Не выбрано</option>
                    <?php foreach ($regions_base as $item) { ?>
                        <option data-regid="<?= $item['id'] ?>" <?= $item['id'] == \Yii::$app->user->identity->region ? 'selected' : '' ?>
                                value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <?= $form->field($order, 'city', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->city]])->label('Город*', ['class' => false]); ?>
            <?= $form->field($order, 'adress', ['inputOptions' => ['class' => false, 'value' => \Yii::$app->user->identity->adress]])->label('Адрес*', ['class' => false]); ?>
            <?= $form->field($order, 'user_comment', ['inputOptions' => ['class' => false]])->label('Комментарий', ['class' => false])->textarea(['cols' => '30', 'rows' => '10']); ?>


            <?= Html::input('hidden', 'Order[user_id]', \Yii::$app->user->identity->id) ?>
            <!--            --><?//= Html::input('hidden','Order[promocode]', $promocode) ?>
        </div>
        <div class="col-md-6 cartradios">

            <h2>Доставка<span class="selecdelivery">Выберите регион доставки.</span></h2>

            <div class="cart_form_radios cart_form_radios_delivery">
                <?php $id_counter = 0;
                foreach ($delivery_methods as $delivery_method): $id_counter++ ?>
                    <div data-products_count="<?= $delivery_method->products_count ?>"
                         data-delivery_region="<?= $delivery_method->region ?>">
                        <?= Html::label($delivery_method->caption, 'delivery-' . $id_counter) ?>
                        <?php
                        $chkk = false;
                        if (!\Yii::$app->user->isGuest) {
                            if ($delivery_method->id == \Yii::$app->user->identity->preferred_delivery) {
                                $chkk = true;
                            } else {
                                $chkk = false;
                            }
                        }
                        ?><span class="delradioprice"></span>
                        <?= Html::radio('Order[delivery_id]', $chkk, ['id' => 'delivery-' . $id_counter, 'value' => $delivery_method->id, 'data-payprice' => $delivery_method->price, 'data-freepayprice' => $delivery_method->freesumm]) ?>

                        <?php if ($delivery_method->desc && $delivery_method->desc != '') { ?>
                            <div class="desc_deliva"><?= $message = nl2br(htmlspecialchars(addslashes(trim($delivery_method->desc)))) ?></div>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <h2>Оплата<span class="selecdeliverytype">Выберите способ доставки.</span></h2>

            <div class="cart_form_radios cart_form_radios_pay">
                <?php $id_counter = 0;
                foreach ($pay_methods as $pay_method): $id_counter++ ?>
                    <div>
                        <?= Html::label($pay_method->caption, 'pay-' . $id_counter) ?>

                        <?php
                        $chkk = false;
                        if (!\Yii::$app->user->isGuest) {
                            if ($pay_method->id == \Yii::$app->user->identity->preferred_pay_method) {
                                $chkk = true;
                            } else {
                                $chkk = false;
                            }
                        }
                        ?>
                        <?= Html::radio('Order[pay_method]', $chkk, ['id' => 'pay-' . $id_counter, 'data-deliverys' => $pay_method->delivery, 'value' => $pay_method->id, 'data-paytrue' => 'true',]) ?>
                        <?php if ($pay_method->desc && $pay_method->desc != '') { ?>
                            <div class="desc_deliva"><?= $message = nl2br(htmlspecialchars(addslashes(trim($pay_method->desc)))) ?></div>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cartinfo">
                * Ваша личная информация будет использована только для доставки и оформления аккаунта. <br> <br>
                При оформлении заказа впервые, Вам автоматически будет выдан доступ в личный кабинет, поэтому
                просим Вас ввести уникальный личный email адрес, который будет являться Вашим идентификатором
                на сайте.
            </div>
        </div>
    </div>
    <div class="row iagree cart_form_cheks">
        <div class="col-md-12">
            <div style="display: inline-block;     text-align: left!important;">
                <label for="iagree">С правилами обработки и доставки заказов согласен</label>
                <input checked required type="checkbox" name="iagree" id="iagree">
            </div>
        </div>
    </div>

    <div class="popup_cart_res popup_cart_resbot">
        <div class="popup_cart_cart_inner_item_info_bot">
            Стоимость товаров: <span class="result_price"><?= $resultPrice ?> </span> руб.
        </div>
        <div class="popup_cart_cart_inner_item_info_bot">
            Стоимость доставки: <span class="result_price_delivery"><?= $resultPrice ?></span> руб.
        </div>
        <div class="popup_cart_cart_inner_item_info_bot cart_bot_result">
            Итоговая стоимость заказа: <span class="result_price_final"><?= $resultPrice ?> </span> руб.
        </div>
        <div> <?php if (!empty($promocode)): ?>
                Код: <?= $promocode->code ?>, скидка: <?= $promocode->discount ?> %
            <?php endif; ?></div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="popup_cart_bottom clearfix mmpvl active">
                <a href="<?= Url::to(['/catalog']) ?>">Продолжить покупки</a>
                <a href="#" id="btn-create-order" class="button">Оформить</a>
                <div title="" class="hidecarbut"></div>
            </div>
        </div>
    </div>
    <input type="submit" class="submithodda" style="display:none;">
<?php ActiveForm::end() ?>


<?php $script = <<< JS
$('#btn-create-order').click(function(e){
        e.preventDefault();
        // $('#order-form').trigger('submit');    
        $('.submithodda').click();    
});
JS;

$this->registerJS($script);

