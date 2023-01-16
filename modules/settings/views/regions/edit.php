<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Новый регион</div>
    <div class="panel-body">



    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['regions/save'],
    ]); ?>
    <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
    <?= $form->field($model, 'name')->label('Название региона') ?>
    <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    </div>
</div>