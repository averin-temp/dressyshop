<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Guestbook;

?>
<?= $this->render('menu') ?>










<dl class="dl-horizontal guestbook-view">
    <dt>Имя</dt>
    <dd><?= $model->name ?></dd>

    <dt>Дата создания</dt>
    <dd><?= $model->created  ?></dd>
	
    <dt>Вопрос:</dt>
    <dd><?= $model->content?></dd>
</dl>


<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'answer')->textarea(['rows'=>10])->label('Ответ') ?>
    
<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>








