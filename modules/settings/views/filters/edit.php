<?php

use yii\bootstrap\Html;
use app\models\Filters;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = "Редактировать фильтр";
?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Фильтр</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'type')->dropDownList(Filters::getTypes())->label('Тип') ?>
        <?= $form->field($model, 'parent_filter')->dropDownList($filters,[ 'prompt' => 'Выберите фильтр' ])->label("Показывать с фильтром") ?>
        <?= $form->field($model, 'enable')->checkbox(['label' => false])->label("Активен") ?>
        <?= $form->field($model, 'property_types')->listBox(ArrayHelper::map($propertyTypes,'id', 'name'), ['multiple' => true]) ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>