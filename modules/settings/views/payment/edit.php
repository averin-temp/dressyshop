<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Новая доставка</div>
    <div class="panel-body">



    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['payment/save'],
    ]); ?>
    <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
    <?= $form->field($model, 'caption')->label('Название') ?>
    <?= $form->field($model, 'desc')->textarea(['rows'=>'10','style'=>'resize:none'])->label('Описание') ?>
    <?= $form->field($model, 'delivery')->label("Способы доставки")->dropDownList(\yii\helpers\ArrayHelper::map($delivery, 'id', 'caption'),['multiple' => 'true', 'style'=>'    height: 200px;', 'value'=>explode(',',$model->delivery)]) ?>

    <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    </div>
</div>