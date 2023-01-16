<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Промокод</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название')  ?>
        <?= $form->field( $model , 'discount')->label('Скидка')  ?>
        <?= $form->field($model, 'code')->label('Код') ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

$script = <<< JS
// генератор кода
JS;

$this->registerJS($script);