<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

use app\assets\AdminAsset;
AdminAsset::register($this);

$this->title = "Список заказов";

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?= $this->render('_search', [
	'filters' => $filters,
	'statuses' => $statuses,
	'deliverys' => $deliverys,
	'pays' => $pays,
	'regions' => $regions,
	
	]) ?>
			
<table width="100%">
        <thead class="list_drag">
        <tr>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-id'){echo '?sort=id';} else{echo '?sort=-id';}?>">№</a><!--<span class="glyphicon glyphicon-chevron-down"></span>--></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-created'){echo '?sort=created';} else{echo '?sort=-created';}?>">Дата</a></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-lastname'){echo '?sort=lastname';} else{echo '?sort=-lastname';}?>">ФИО</a></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-status.caption'){echo '?sort=status.caption';} else{echo '?sort=-status.caption';}?>">Статус</a></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-fullcost'){echo '?sort=fullcost';} else{echo '?sort=-fullcost';}?>">Сумма</a></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-delivery.caption'){echo '?sort=delivery.caption';} else{echo '?sort=-delivery.caption';}?>">Доставка</a></th>
            <th><a style="color:white" href="<?php if($_GET['sort'] == '-pay_methods.caption'){echo '?sort=pay_methods.caption';} else{echo '?sort=-pay_methods.caption';}?>">Оплата</a></th>
            <th>Состав заказа</th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul" style="font-size:13px;">

        <?php foreach ($provider->models as $item) : ?>

            <tr <?php if($item->status->id == 10){?> style="font-weight:bold" <?}?>>
                <td><a href="/admin/orders/orders/edit/<?=$item->id?>"><?=$item->order_number?></a></td>
                <td width="70" style="text-align:center"><?=date_create($item->created)->Format('d-m-y');?><br><?=date_create($item->created)->Format('H:i');?></td>
                <td><?=$item->lastname?> <?=$item->firstname?> <?=$item->patronymic?></td>
                <td width="120"><?=$item->status->name?></td>
                <td><?=$item->fullcost?></td>
                <td><?=$item->delivery->caption?></td>
                <td><?=$item->pay->caption?></td>
                <td width="280">
					<ul style="    margin-bottom: 0;    margin-left: 10px;list-style: decimal;    padding: 0;">
					<?php foreach($item->products as $oneprod){ ?>
						<li>
							<a target="_blank" href="/catalog/<?= $oneprod->id ?>"> 
							<?= \app\models\Model::find()->where(['id' => $oneprod->model_id])->One()->vendorcode?> 
								(<?= \app\models\Color::find()->where(['id' => $oneprod->color_id])->One()->name?>)<?php if($oneprod->size_id) {?> Размер <?= \app\models\Size::find()->where(['id' => $oneprod->size_id])->One()->name?><?php }?>
								
							</a>
						</li>
					<?php }?>	
					</ul>				
				</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?// = GridView::widget([
    // 'id' => 'orders-table',
    // 'layout' => "{items}\n{pager}",
    // 'dataProvider' => $provider,
    // 'columns' => array_merge([
        // [
            // 'class' => 'yii\grid\CheckboxColumn',
        // ],
    // ], $settings->gridColumns)
// ]); ?>

<br>
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

<?php $actionform = ActiveForm::begin([
    'id' => 'action-form',
    'options' => ['style' => 'display: inline-block']
]); ?>
<?= Html::hiddenInput('actionform[keys]') ?>
<?= Html::hiddenInput('actionform[action]') ?>
<div class="action-params"  style="display: inline-block"></div>
<?//= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>




<?//= $this->render('_settings', ['settings' => $settings]) ?>

<?php

$script = <<< JS


/*----------------------------------------------------------------------------
    Таблица заказов.
  -------------------------------------------------------------------------- */

/* Обработка выбора действия */
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
    }
});

/* Отправка формы Действий */
$('#action-form').submit(function(e){
    var keys = $('#orders-table').yiiGridView('getSelectedRows');
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
/* Сколько строк выводить */
$('#per-page').on('change', function(){
    $('#page-size-form').trigger('submit');
});

/* Проверка ввода Формулы */
$('#action-form').on('input', '[name="actionform[formula]"]', function(){
    var value = $(this).val();
    
    if(value.match(/^\s*([+-])\s*(\d+)\s*(%?)\s*$/))
    {
        $(this).removeClass('incorrect');     
    }
    else
    {
        $(this).addClass('incorrect'); 
    }
});

/*----------------------------------------------------------------------------
    Конец. Таблица заказов.
  -------------------------------------------------------------------------- */

JS;
$this->registerJs($script);

