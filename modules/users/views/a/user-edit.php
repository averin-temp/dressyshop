<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Group;
use app\models\User;
use app\models\Delivery;
use app\models\Regions;
use app\models\Pay;
$this->title = 'Редактирование пользователя';
?>
<?= $this->render('menu') ?>
<?php $form = ActiveForm::begin(['action'=> ['save'] ]); ?>
<div class="row">
    <div class="col-lg-4 col-md-6">


        <?= Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'firstname') ?>
        <?= $form->field($model, 'patronymic') ?>
        <?= $form->field($model, 'lastname') ?>
        <?= $form->field($model, 'email')?>
        <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>
        <?= $form->field($model, 'confirm')->passwordInput() ?>
        <?//= $form->field($model, 'region') ?>
		 <?= $form->field($model, 'region')->dropdownList(
            Regions::find()->select(['name', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Регион не выбран'] ) ?>
        <?= $form->field($model, 'city') ?>
        <?= $form->field($model, 'adress') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'role')->dropdownList(
            User::getRoles(), ['prompt'=>'Выберите роль'] ) ?>
        <?= $form->field($model, 'registered')->textInput(['disabled' => true]) ?>
        <?= $form->field($model, 'last_visit')->textInput(['disabled' => true]) ?>

        <?= $form->field($model, 'preferred_delivery')->dropdownList(
            Delivery::find()->select(['caption', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Способ доставки не выбран'] ) ?>
        <?= $form->field($model, 'preferred_pay_method')->dropdownList(
            Pay::find()->select(['caption', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Способ оплаты не выбран']) ?>
        <?= $form->field($model, 'group_id')->dropdownList(
            Group::find()->select(['name', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Группа не выбрана']);
        ?>
    </div>
    <div class="col-lg-8 col-md-6">
        <img id="preview" style="width: 100%; max-width: 400px; height: auto;" src="<?= $model->avatar ?>">
        <?= $form->field($model, 'photo')->fileInput(['id'=>'file_upload'])->label('Фотография') ?>
    </div>
</div>


<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
<div style="height: 100px;">
    <?php /*мне панель мешает, отодвинул*/ ?>
</div>


<?php

$script = <<< JS
/*---------------------------------------------------------------
    Пользователи
--------------------------------------------------------------- */


// Миниатюра картинки при загрузке файла
$('#file_upload').change(function(){
    var file = this.files[0];
    if(!file || !/^image\/(jpeg|png|gif|jpg|bmp)$/i.test(file.type))
    {
        alert('неверный форат файла, допустимы изображения jpg, png, gif, bmp');
        return;        
    }
    
    var reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }

    reader.readAsDataURL(file);
    
});
/*---------------------------------------------------------------
    Конец. Пользователи
--------------------------------------------------------------- */


JS;
$this->registerJS($script);