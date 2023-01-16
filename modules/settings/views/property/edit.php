<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\PropertyType;

?>

<?= $this->render('menu') ?>

<ul class="nav nav-tabs">
    <li class="active"><a href="#">Редактировать свойство</a></li>
    <?php if($model->id && $model->format == PropertyType::IS_UNION): ?><li><a href="<?= Url::to(['/admin/settings/values/index', 'type_id' => $model->id]) ?>"><span class="glyphicon glyphicon-camera"></span> Доступные значения</a></li><?php endif; ?>
</ul>

<br>

<div class="panel panel-primary">
    <div class="panel-heading">Тип свойства</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([ 'method' => 'post', 'action' => ['save'] ]); ?>
        <?= $form->field($model, 'id')->hiddenInput(['name'=>'id'])->label(false) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'format')->dropDownList(PropertyType::types())->label('Формат свойства') ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>