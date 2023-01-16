<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
?>

<!--
<div class="footer_subscribe">
    <form action="">
        <label for="">Подписаться на новости</label>
        <input type="email" required>
        <input type="submit" value="подписаться">
    </form>
</div>
<div class="footer_subscribe">
    <form id="subscribe-form" action="/site/subscribe" method="post">
        <input type="hidden" name="_csrf" value="UVhZaGdVLkwkLmBaDmdPeBwNPwU/BmkHFxEpBAZ4eQgOdSgEDmNNJA==">
        <label class="control-label" for="subscribe-email">Подписаться на новости</label>
        <input type="text" id="subscribe-email" name="Subscribe[email]" aria-required="true" aria-invalid="true">

        <input type="submit" name="" value="подписаться">    </form></div>



-->


<div class="footer_subscribe">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['site/subscribe']),
        'method' => 'post',
        'id' => 'subscribe-form',
		'enableAjaxValidation' => false,
        'fieldConfig' => [ 'template' => "{label}\n{input}",
                           'inputOptions' => [ 'class' => false,  'type' => 'email', 'placeholder' => 'Ваш email' ],
                           'options' => [ 'tag' => false ],
                           'labelOptions' => [ 'class' => false ]
        ],
        'options' => [ 'class' => false ]
    ])?>
    <?= $form->field($model, 'email')->label('Подписаться на новости') ?>
    <?= Html::input('submit','','подписаться') ?>
    <?php ActiveForm::end() ?>
</div>

<?php
$script = <<< JS
$('#subscribe-form').on('beforeSubmit',function(e){
    e.stopPropagation();
    e.preventDefault();
	$('.footer_subscribe').html('<img src="/img/anim.svg"/>');
    var serialized = $(this).serialize();
    var action = $(this).attr('action');
    $.post(action, serialized, function(response){
		//console.log(response);
		//return false;
        if(response.ok) {
			//alert('ok');
			$('.footer_subscribe').fadeOut(200);			
			setTimeout(function(){
				$('.footer_subscribe').html('Спасибо за подписку!');
				$('.footer_subscribe').fadeIn(200);
			},250)	
			return false;		
        } 
		else{
			//alert('nook');
            $('.footer_subscribe').fadeOut(200);			
			setTimeout(function(){
				$('.footer_subscribe').html('Вы уже подписаны на рассылку. Отписаться можно перейдя по ссылке. ');
				$('.footer_subscribe').fadeIn(200);
			},250)	
        }
    }, 'json');
	return false;
});
JS;

$this->registerJS($script, \yii\web\View::POS_READY, 'subscribe');
