<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Редактировать размер";
?>

<?= $this->render('menu', [ 'range' => $model->parent_range ]) ?>

<div class="panel panel-primary">
    <div class="panel-heading">Размер</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'parent_range')->hiddenInput()->label(false) ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>