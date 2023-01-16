<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование группы';
?>
<?= $this->render('menu') ?>
<?php $form = ActiveForm::begin(['action'=> ['save']  ]); ?>
<div class="row">
    <div class="col-lg-4 col-md-6">


        <?= Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')?>
        <?= $form->field($model, 'discount') ?>

    </div>
    <div class="col-lg-8 col-md-6">
    </div>
</div>



<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
<div style="height: 100px;">
    <?php /*мне панель мешает, отодвинул*/ ?>
</div>