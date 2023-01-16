<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Редактировать размерный ряд";

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Размерный ряд</div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>