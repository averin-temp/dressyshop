<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Questions;

?>
<?= $this->render('menu') ?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->label('Имя пользователя') ?>
<?= $form->field($model, 'created')->label('Дата создания') ?>
<?= $form->field($model, 'content')->textarea()->label('Содержание') ?>
<?= $form->field($model, 'avalible')->checkbox(['label' => null])->label('Показывать на сайте') ?>
<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
