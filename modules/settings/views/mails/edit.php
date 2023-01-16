<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<?php 
$sh_codes_desc = '';
if($model->zone){
	$sh_codes_desc.="<br>";
	$sh_codes_desc.="<div style='border: 1px solid #337ab7;
    padding: 10px;
    width: 100%;
    margin-top: 5px;
    margin-bottom: 6px;
    background: #efefef;'>";	
	$sh_codes_desc.="<span style='font-weight:normal!important'>";
	switch ($model->zone){
		
		case "registration":
			$sh_codes_desc.="{email} - для замены на email пользователя";
			break;		
		case "remember":
			$sh_codes_desc.="{email} - для замены на email пользователя<br>";
			$sh_codes_desc.="{password} - для замены на пароль пользователя";
			break;	
		case "answer":
			$sh_codes_desc.="{answer} - для замены на ответ менеджера<br>";
			break;	
			
		case "new_order":
			$sh_codes_desc.="{order_number} - для замены на номер заказа<br>";
			break;				
			
			
		case "status_11":
		case "status_12":
		case "status_13":
		case "status_16":
		case "status_18":
		case "status_19":
		case "status_20":
		case "status_21":
			$sh_codes_desc.="{order_number} - для замены на номер заказа<br>";
			break;				
		case "status_15":
			$sh_codes_desc.="{order_number} - для замены на номер заказа<br>";
			$sh_codes_desc.="{order_track} - для замены на трекномер<br>";
			break;				
		
			
			
			
			
			
			
			
		default:
			$sh_codes_desc.="<span style='color:red'>Шорткодов для данного шаблона не найдено</span>";
			break;
	}
		
	$sh_codes_desc.="</span>";
	$sh_codes_desc.="</div>";	
	
}


?>



<div class="panel panel-primary">
    <div class="panel-heading">Шаблон письма</div>
    <div class="panel-body">



        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['mails/save'],
        ]); ?>
		 <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
		<?= $form->field($model, 'content')->textarea(['rows' => '6','class' => 'editor'])->label('Текст шаблона <span style="font-weight:normal!important">(доступны следующие вставки)</span>:'.$sh_codes_desc) ?>
		<?= $form->field($model, 'subject')->label('Тема письма') ?>
		<?= $form->field($model, 'from')->label('Отправитель письма') ?>
		<?= $form->field($model, 'zone')->label('Зона') ?>
        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>