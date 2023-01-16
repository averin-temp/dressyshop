<?php

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

Modal::begin([
        'id' => 'settings-window',
    'header' => '<h2>Настройки столбцов</h2>',
    'toggleButton' => ['label' => 'Настройка таблицы'],
]);

$form = ActiveForm::begin([
    'id' => 'filter-settings', 'method' => 'post'
]);




?>
    <div class="row">


        <div class="col-xs-4">
            <h4>Все столбцы</h4>
            <div class="well">

                <ul id="inactive-columns">
                    <?php foreach($settings->inactiveColumns as $column => $options): ?>
                        <li data-column="<?= $column ?>"><?= $options['label'] ?></li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <button id="toleft" class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                </button>
            </div>
            <div class="form-group">
                <button id="toright" class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </button>
            </div>
        </div>

        <div class="col-xs-4">
            <h4>Активные столбцы</h4>
            <div class="well">

                <ul id="active-columns">
                        <?php foreach($settings->activeColumns as $column => $options): ?>
                            <li data-column="<?= $column ?>"><?= $options['label'] ?></li>
                        <?php endforeach; ?>
                </ul>

            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <button id="toup" class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </button>
            </div>
            <div class="form-group">
                <button id="todown" class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-arrow-down"></span>
                </button>
            </div>
        </div>

    </div>

    <button class="btn btn-default" type="submit">Сохранить</button>
    <button class="btn btn-default" data-dismiss="modal" type="reset">Отменить</button>
<?php





ActiveForm::end();

Modal::end();

$script = <<< JS

/*----------------------------------------------------------------------------
    Настройки.
  -------------------------------------------------------------------------- */
$('#inactive-columns').on('click', '[data-column]', function(){
    $('#toleft,  #toup, #todown').prop('disabled', true);
    $('#toright').prop('disabled', false);
});

$('#active-columns').on('click', '[data-column]', function(){
    $('#toleft, #toup, #todown').prop('disabled', false);
    $('#toright').prop('disabled', true);
});

$('[data-column]').click(function(){
    $('#settings-window [data-column]').removeClass('active');
    $(this).addClass('active');
});

$('#toleft').click(function(){
    $('#active-columns [data-column].active').appendTo($('#inactive-columns'));
    if($('#active-columns [data-column]').length == 0)
    {
        $('#toleft, #toup, #todown').prop('disabled', true);
        $('#toright').prop('disabled', false);
    }
    else
    {
        $('#settings-window [data-column]').removeClass('active');
        $('#active-columns li').first().addClass('active');
        
    }
});

$('#toright').click(function(){
    $('#inactive-columns [data-column].active').appendTo($('#active-columns'));
    if($('#inactive-columns [data-column]').length == 0)
    {
        $('#toleft, #toup, #todown').prop('disabled', false);
        $('#toright').prop('disabled', true);
    }
    else
    {
        $('#settings-window [data-column]').removeClass('active');
        $('#inactive-columns li').first().addClass('active');       
    }
});

$('#filter-settings').submit(function(){
    var form = $(this);
    $('#active-columns [data-column]').each(function(){
        var column = $(this).attr('data-column');
        form.append($('<input type="hidden" name="columns[]">').val(column));
    });
});

$('#toup').click(function(){
    var activField = $('#active-columns [data-column].active');
    activField.insertBefore(activField.prev());
    if(activField.is(':first')){
        $('#toup').prop('disabled', true);
    }
});

$('#todown').click(function(){
    var activField = $('#active-columns [data-column].active');
    activField.insertAfter(activField.next());
    if(activField.is(':last')){
        $('#todown').prop('disabled', true);
    }
});


if($('#active-columns [data-column]').length){
    $('#active-columns [data-column]').first().addClass('active');
    $('#toright').prop('disabled', true);
} else {
    $('#inactive-columns [data-column]').first().addClass('active');
    $('#toleft, #toup, #todown').prop('disabled', true);
}

/*----------------------------------------------------------------------------
    конец Настроек
  -------------------------------------------------------------------------- */
JS;
$this->registerJs($script);