<?php

use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$module = $this->context->module->id;
$this->title = 'Товары';

?>

<?= $this->render('_menu') ?>

<?= $this->render('_search', ['filters' => $filters, 'categories' => $categories]) ?>

<?= GridView::widget([
    'id' => 'products-table',
    'dataProvider' => $provider,
    'layout' => "{items}\n{pager}",
    'columns' => array_merge([
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
    ], array_merge($settings->gridColumns, [ [
        'class' => 'yii\grid\ActionColumn',
        'header'=>'Удалить',
        'headerOptions' => ['width' => '80'],
        'template' => ' {delete}{link}',
    ], ]))
]); ?>


<?php

$request = Yii::$app->getRequest();
$params = $request->getQueryParams();
$params[0] = 'index'; // Контроллер.
unset($params['per-page']);
$actionform = ActiveForm::begin([
    'id' => 'page-size-form',
    'method' => 'get',
    'action' => Url::to($params)

]); ?>
    <div class="form-group">
        <label for="per-page">Выводить по </label>
        <select name="per-page" id="per-page">
            <?php $sizes = [5,10,20,30]; foreach($sizes as $size): ?>
                <option value="<?= $size ?>" <?= Yii::$app->request->get('per-page') == $size ? 'selected' : '' ?>><?= $size ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php ActiveForm::end(); ?>


    <label for="action-select">С выбранными: </label>
    <select style="display: inline-block" name="action_select" id="action-select">
        <option selected>Выберите действие</option>
        <option value="delete">Удалить</option>
        <option value="formula">Изменить закупочную цену</option>
        <option value="changesort">Изменить сортировку</option>
        <option value="change-enable">Изменить видимость</option>
        <option value="double">Дублировать</option>
    </select>
<?php $actionform = ActiveForm::begin([
    'id' => 'action-form',
    'action' => ['batch'],
    'method' => 'post',
    'options' => ['style' => 'display: inline-block']
]); ?>
<?= Html::hiddenInput('actionform[keys]') ?>
<?= Html::hiddenInput('actionform[action]') ?>

    <div class="action-params"  style="display: inline-block"></div>

<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>


<?= $this->render('_settings', ['settings' => $settings]) ?>


<?php
$script = <<< JS

/*----------------------------------------------------------------------------
    Таблица продуктов.
  -------------------------------------------------------------------------- */

/* Изменение выбранного Действия в селекте действий */ 
$('#action-select').change(function(){
    
    var paramsDiv = $('#action-form .action-params');
    paramsDiv.html('');
    var action = $(this).val();
    switch (action){
        case 'changesort':
            paramsDiv.append('<input name="actionform[changesort]" value="0" >');
            break;
        case 'formula':
            paramsDiv.append('<input name="actionform[formula]" value="" class="incorrect">');
            break;
        case 'change-enable':
            paramsDiv.append('<input name="actionform[change_enable]" type="checkbox" checked >');
            break;
    }
});

/* отправка формы Действий */ 
$('#action-form').submit(function(e){
    var keys = $('#products-table').yiiGridView('getSelectedRows');
    var action = $('#action-select').val();
    
    if( keys.length === 0 || 
        action == '' ||
        $('[name="actionform[formula]"].incorrect').length){
        
        e.stopPropagation();
        e.preventDefault();
        return false;
    }  
    
    $(this).find('[name="actionform[keys]"]').val(keys);
    $(this).find('[name="actionform[action]"]').val(action);
    
    
});


// Записей на странице
$('#per-page').on('change', function(){
    $('#page-size-form').trigger('submit');
});

// Поле Формула в действиях. проверка ввода
$('#action-form').on('input', '[name="actionform[formula]"]', function(){
    var value = $(this).val();

    if(value.match(/^\s*([+-])\s*(\d+)\s*(%?)\s*$/)){
        $(this).removeClass('incorrect'); 
    } else {
        $(this).addClass('incorrect'); 
    }
});

/*----------------------------------------------------------------------------
    Конец. Таблица продуктов.
  -------------------------------------------------------------------------- */

JS;
$this->registerJs($script);
