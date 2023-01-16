<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Редактировать баннер";

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Баннер</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'caption')->label('Название') ?>
        <?= $form->field( $model , 'url')->label('Ссылка') ?>
        <?= $form->field( $model , 'enable')->checkbox([],false)->label('Включен') ?>
        <?= $form->field( $model , 'image')->fileInput()->label('Изображение'); ?>
        <div class="form-group">
            <img id="preview" style="max-width: 200px; height: auto;" src="<?= $model->image ?>" alt="">
        </div>
        <?= $form->field( $model , 'enable_parallax')->checkbox([],false)->label('Включить параллакс') ?>
        <?= $form->field( $model , 'parallax_image')->fileInput()->label('Изображение параллакса'); ?>

        <div class="form-group">
            <img id="preview-parallax" style="max-width: 200px; height: auto;" src="<?= $model->parallax_image ?>" alt="">
        </div>

        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

$script = <<< JS
$('[name="Banner[image]"]').change(function(){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#preview').get(0).src = reader.result;
    };
    reader.readAsDataURL(this.files[0]);
});

$('[name="Banner[parallax_image]"]').change(function(){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#preview-parallax').get(0).src = reader.result;
    };
    reader.readAsDataURL(this.files[0]);
});
JS;

$this->registerJS($script);
?>
