<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Бэйдж</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['save']),
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'viewcount')->label('Количество просмотров') ?>
        <?= $form->field($model, 'image')->fileInput() ?>

        <div class="form-group">
            <img id="preview" style="max-width: 200px; height: auto;" src="<?= Url::to('@web/images/badges/'.$model->image) ?>" alt="">
        </div>


        <?= $form->field($model, 'text')->label('Текст')->textInput() ?>
        <?= $form->field($model, 'class')->label('Класс')->textInput() ?>
        <?= $form->field($model, 'css')->label('Стили')->textarea() ?>

        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

$script = <<< JS
$('[name="AutoBadge[image]"]').change(function(){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#preview').get(0).src = reader.result;
    };
    reader.readAsDataURL(this.files[0]);
});
JS;


$this->registerJS($script);
?>