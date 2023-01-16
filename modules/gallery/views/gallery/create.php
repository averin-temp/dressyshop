<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Material;

?>
<?= $this->render('menu') ?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name') ?>

<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
