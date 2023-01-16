<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\classes\socials\Vk;
use app\classes\socials\Facebook;
use app\classes\socials\Odnoklassniki;
use app\classes\socials\Google;
$regions_base=$regions;
?>
<div class="row">

    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['action' => Url::to('/account/save')]) ?>

        <?= \yii\helpers\Html::hiddenInput('id', $model->id) ?>
        <?= $form->field($model, 'lastname') ?>
        <?= $form->field($model, 'phone')->textInput(['class' => 'phone_field']) ?>
        <?= $form->field($model, 'firstname') ?>
        <?= $form->field($model, 'email')->textInput(['disabled' => true]) ?>
        <?= $form->field($model, 'patronymic') ?>
        <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>
        <?= $form->field($model, 'zip_code')->textInput(['type' => 'number']) ?>
        <?= $form->field($model, 'confirm')->passwordInput(['value' => '']) ?>

        <div class="form-group field-user-region">
            <label class="control-label" for="user-preferred_region">Регион:</label>
            <select id='user-region' class="form-control" name='User[region]'>
                <option <?= !$model->region ? 'selected' : '' ?> >Не выбрано</option>
                <?php foreach ($regions_base as $item) { ?>
                    <option <?= $item['id'] == $model->region ? 'selected' : '' ?> value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <? //= $form->field($model, 'region') ?>
<!--        --><?php //die(var_dump($model->preferred_delivery))?>
        <?= $form->field($model, 'preferred_delivery')->dropDownList($delivery, array('prompt'=>'Не выбрано'))->label('Предпочитаемый способ доставки') ?>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <?= $form->field($model, 'city') ?>
        <?= $form->field($model, 'preferred_pay_method')->dropDownList($payment, array('prompt'=>'Не выбрано'))->label('Предпочитаемый способ оплаты') ?>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <?= $form->field($model, 'adress') ?>
        <div class="form-group field-user-color">
            <label class="control-label" for="user-preferred_delivery">Ваш любимый цвет</label>
            <div class="color_picker">
                <select id="user-color" class="form-control" name="User[color]">
                    <option class="no_color">Не выбран</option>
                    <?php foreach ($colors as $color): ?>
                        <?php $color_class = mb_substr($color->code, 1);
                        $color_class = 'c_' . $color_class; ?>
                        <option class="<?= $color_class ?>"
                                value="<?= $color->id ?>" <?= $color->id == $model->color ? 'selected' : '' ?> ><?= $color->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary button buttonsubmitacc']) ?>

        <?php ActiveForm::end() ?>


    </div>
    <!--<div class="col-md-6">

        <?php if (empty($model->vk_id)): ?>
            <p><a href="<?= (new Vk())->getAddLink() ?>" class="vk_button">Соединить аккаунт с VK</a></p>
        <?php endif; ?>

        <?php if (empty($model->odnoklassniki_id)): ?>
            <p><a href="<?= (new Odnoklassniki())->getAddLink() ?>" class="fb_button">Соединить аккаунт с Одноклассниками</a></p>
        <?php endif; ?>

        <?php if (empty($model->google_id)): ?>
            <p><a href="<?= (new Google())->getAddLink() ?>" class="goo_button">Соединить аккаунт с Google+</a></p>
        <?php endif; ?>

        <?php if (empty($model->facebook_id)): ?>
            <p><a href="<?= (new Facebook())->getAddLink() ?>" class="sk_button">Соединить аккаунт с Facebook</a></p>
        <?php endif; ?>
    </div>-->
</div>

