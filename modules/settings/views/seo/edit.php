<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">SEO</div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['seo/save'],
        ]); ?>
        <?= $form->field($seo, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($seo, 'name')->label('Заголовок') ?>
        <?= $form->field($seo, 'meta_key') ?>
        <?= $form->field($seo, 'meta_description') ?>
         <?= $form->field($seo, 'title') ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>