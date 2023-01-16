<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;


$this->title = "Seo настройки";

?>
<?= $this->render('_menu', ['model' => $model]) ?>

<?php $form = ActiveForm::begin([ 'action' => ['save'], 'method' => 'post' ]) ?>
<?= Html::hiddenInput('model', $model->id) ?>
<?= $form->field($model, 'meta_title')->label('SEO title (120)') ?>
<?= $form->field($model, 'meta_keywords')->label('SEO key (255)') ?>
<?= $form->field($model, 'meta_description')->label('SEO descr (255)') ?>

<?= Html::submitButton('Сохранить изменения',['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
