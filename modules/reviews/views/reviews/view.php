<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Questions;
use yii\jui\DatePicker;


?>
<?= $this->render('menu') ?>


<dl class="dl-horizontal guestbook-view">
    <dt>Имя</dt>
    <dd><?= $model->name ?></dd>

    <dt>Дата создания</dt>
    <dd><?= $model->created  ?></dd>
	
    <dt>Вопрос:</dt>
    <dd><?= $model->content?></dd>
	
	<dt>Артикул:</dt>
    <dd><a target="_blank" href="<?= Url::to(['../../catalog/'. $product = \app\models\Product::find()->where(['model_id' => $model->model->id])->One()->id]) ?>"><?= $model->model->vendorcode ?></a></dd>

</dl>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'avalible')->checkbox(['label' => null])->label('Показывать на сайте') ?>
<?= Html::submitButton( 'Сохранить' , ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
