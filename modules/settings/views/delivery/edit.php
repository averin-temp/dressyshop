<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Новая доставка</div>
    <div class="panel-body">



    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['delivery/save'],
    ]);
    ?>
    <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
    <?= $form->field($model, 'caption')->label('Название') ?>
    <?= $form->field($model, 'desc')->textarea(['rows'=>'10','style'=>'resize:none'])->label('Описание') ?>
    <?= $form->field($model, 'price')->label('Цена') ?>
    <?= $form->field($model, 'freesumm')->label('Бесплатно от:') ?>
<!--    --><?//= $form->field($model, 'region')->label('Регион:') ?>
    <?= $form->field($model, 'region')->label('Регион<span class="region_select"><span>Выбрать все</span>/<span>Снять все</span></span>')->dropDownList(\yii\helpers\ArrayHelper::map($regions, 'id', 'name'),['multiple' => 'true', 'style'=>'    height: 200px;', 'value'=>explode(',',$model->region)]) ?>
    <?= $form->field($model, 'products_count')->label('Макс. количество товаров:') ?>
    <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    </div>
</div>