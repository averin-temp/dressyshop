<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
?>

<?= $this->render('menu') ?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['common/save'],
]); ?>


<div class="panel panel-primary">
    <div class="panel-heading">Номера телефонов</div>
    <div class="panel-body">
            <?= $form->field($model, 'phone1')->label('Телефон1') ?>
            <?= $form->field($model, 'phone2')->label('Телефон2') ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Настройки формул</div>
    <div class="panel-body">
        <?= $form->field($model, 'formula', [ 'enableClientValidation' => true ] )->label('Формула') ?>
        <?= $form->field($model, 'formula_bel', [ 'enableClientValidation' => true ] )->label('Формула (белорусы)') ?>
    </div>
</div>

    <div class="panel panel-primary">
        <div class="panel-heading">Текст на главной</div>
        <div class="panel-body">
            <?= $form->field($model, 'homepage_text')->textarea([ 'class' => 'editor' ])->label(false) ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Описание доставки</div>
        <div class="panel-body">
            <?= $form->field($model, 'delivery_description')->textarea([ 'class' => 'editor' ])->label(false) ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Описание оплаты</div>
        <div class="panel-body">
            <?= $form->field($model, 'payment_description')->textarea([ 'class' => 'editor' ])->label(false) ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Социальные сети</div>
        <div class="panel-body">
            <?/*= $form->field($model, 'facebook_url')->label('Ссылка на страницу в Facebook') */?>
            <?/*= $form->field($model, 'google_url')->label('Ссылка на страницу в Google+') */?>
            <?= $form->field($model, 'skype_url')->label('Ссылка на страницу в Instagram') ?>
            <?= $form->field($model, 'vk_url')->label('Ссылка на страницу в VK') ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Баннер на главной</div>
        <div class="panel-body">
            <?= $form->field($model, 'home_banner')->dropDownList($banners, [ 'prompt' => 'Выберите баннер' ] )->label('Баннер главной страницы') ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Таблица размеров</div>
        <div class="panel-body">
            <?= $form->field($model, 'table_image')->fileInput(['id' => 'table-image-field'])->label('Изображение таблицы размеров') ?>
            <br>
            <img id="table-sizes-image" style="max-width: 300px; max-height: 300px; height: auto"  src="<?= $model->table_image ?>" alt="">
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Email админа</div>
        <div class="panel-body">
            <?= $form->field($model, 'admin_email')->label('Email админа') ?>
        </div>
    </div>


    <div class="panel panel-primary">
        <div class="panel-heading">Блокировки</div>
        <div class="panel-body">
            <?= Html::button('Снять все блокировки', ['id' => 'reset-blocks']) ?>
        </div>
    </div>


<?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php
$resetLink = Url::to('/admin/orders/orders/deblockall');
$script = <<< JS

//  Снять все блокировки
$('#reset-blocks').click(function(){
    $.post('$resetLink').done(function(data){
        data.ok ? drassy_callback('Блокировки:  все сняты',{})  :
          drassy_callback('Блокировки: ошибка разблокирования',data);         
    });
});



$('#table-image-field').on('change', function(){
    var file = this.files[0];
    var reader = new FileReader();
    reader.onload = function(){
        var data = reader.result;
        $('#table-sizes-image').get(0).src = data;
    }
    reader.readAsDataURL(file);
});
JS;
$this->registerJS($script);

