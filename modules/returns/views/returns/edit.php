<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Возврат</div>
    <div class="panel-body">


	<dl class="dl-horizontal guestbook-view">
		<dt>ФИО:</dt>
		<dd><?= $model->name ?></dd>

		<dt>Еmail:</dt>
		<dd><?= $model->email  ?></dd>
		
		<dt>Номер заказа:</dt>
		<dd><?= $model->order_number?></dd>
		
		
		
		<dt>Дата выкупа:</dt>
		<dd><?= $model->date?></dd>
		<dt>Артикул:</dt>
		<dd><?= $model->articulsize?></dd>
		<dt>Причина возврата:</dt>
		<dd><?= $model->why?></dd>
		<dt>Компенсация:</dt>
		<dd><?= $model->type?></dd>
	</dl>

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['returns/save'],
    ]);
    ?>
    <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
	<?php //die(var_dump($statuses))?>
    <?= $form->field($model, 'comment')->textarea(['rows'=>10])->label('Комментарий')?>
    <?= $form->field($model, 'status')->label('Статус')->dropDownList(\yii\helpers\ArrayHelper::map($statuses, 'id', 'name')) ?>

	
	
	
	
    <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    </div>
</div>