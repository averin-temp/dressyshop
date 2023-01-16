<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Group;

?>
<?= $this->render('menu') ?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->input('password') ?>
<?= $form->field($model, 'confirm')->input('password') ?>

<?= $form->field($model, 'group_id')->dropdownList(
    Group::find()->select(['name', 'id'])->indexBy('id')->column(),
    ['prompt'=>'Выберите группу'] )
?>

<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
