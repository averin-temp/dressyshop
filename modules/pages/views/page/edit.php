<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<?= $this->render('_menu') ?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['save'])
]) ?>
<?= Html::hiddenInput('id', $page->id) ?>

<?= $form->field($page, 'caption', [
    'inputOptions' => [ 'id' => 'caption-field' ]
])->label('Название') ?>
<?= $form->field($page, 'slug', [
    'inputOptions' => [ 'id' => 'slug-field' ]
])->label('Слаг'); ?>

<?= $form->field($page, 'menu_id')->dropDownList($menus,[ 'prompt' => 'Выберите меню' ])->label("В каком меню вывести ссылку на страницу "); ?>
<?= $form->field($page, 'menu_order')->label('Порядок в меню') ?>
<div class="form-group">
<textarea class="editor form-control" name="Page[content]" ><?= $page->content ?></textarea>
</div>

<?= $form->field($page, 'meta_title')->label('SEO title (120)') ?>
<?= $form->field($page, 'meta_keywords')->label('SEO key (255)') ?>
<?= $form->field($page, 'meta_description')->label('SEO descr (255)') ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>

<?php
$script = <<< JS
function translit(A, space) {
    space = space ? space : '_';
    var result = '';
    var transl = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
        'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
        'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space,
        'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
    }
    if (A != '')
        A = A.toLowerCase();
 
    for (var i = 0; i < A.length; i++){
        if (/[а-яё]/.test(A.charAt(i))){ // заменяем символы на русском
            result += transl[A.charAt(i)];
        } else if (/[a-z0-9]/.test(A.charAt(i))){ // символы на анг. оставляем как есть
            result += A.charAt(i);
        } else {
            if (result.slice(-1) !== space) result += space; // прочие символы заменяем на space
        }
    }
    
    return result;
}
$('#caption-field').on('input',function(){
    var caption = $(this).val();
    var slug = translit(caption);
    $('#slug-field').val(slug);
});
JS;

$this->registerJS($script);