<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Бейдж</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field( $model , 'discount')->label('Скидка') ?>
        <?= $form->field( $model , 'image')->fileInput()->label('Изображение') ?>

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
$('[name="Badge[image]"]').change(function(){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#preview').get(0).src = reader.result;
    };
    reader.readAsDataURL(this.files[0]);
});
JS;


$this->registerJS($script);