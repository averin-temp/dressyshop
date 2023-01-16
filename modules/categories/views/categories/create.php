<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = "Создать категорию";

function catRows($cats, $lvl = 0, &$data = [] )
{
    if($lvl < 2)
    foreach($cats as $cat)
    {
        $data[$cat['id']] = str_pad("",$lvl,'-').$cat['caption'];
        $chs = isset($cat['childrens']) ? $cat['childrens'] : [];
        if(!empty($chs)) catRows($chs, $lvl + 1, $data);
    }
}

$data = [];
catRows($categories, 0, $data);

if(!empty($model->id)) $selected = $model->parent_id;

?>

<?= $this->render('_menu'); ?>

<?php $form = ActiveForm::begin(['enableAjaxValidation' => false, 'action'=> Url::to(['save']) ]); ?>



<?= $form->field($model, 'caption')->label('Название категории') ?>
<?= $form->field($model, 'caption_one')->label('Единственное число') ?>
<?= $form->field($model, 'order')->input('number')->label('Приоритет') ?>

<?= Html::hiddenInput('id', $model->id) ?>

<?= $form->field($model, 'parent_id')->dropDownList(
    $data,
    [
        'prompt' => "Без родительской категории",
        "options" => [ $selected => ['selected' => true]]
    ]
)->label('Родительская категория');

?>
<?= $form->field($model, 'slug') ?>

<div class="form-group">
    <textarea class="editor form-control" name="Category[description]" ><?= $model->description ?></textarea>
</div>


<?= $form->field($model, 'use_parent_description')->checkbox()->label('Использовать описание родительской категории') ?>
<?= $form->field($model, 'meta_title')->label('SEO title (120)') ?>
<?= $form->field($model, 'icon_link')->label('Ссылка на иконку') ?>
<?= $form->field($model, 'meta_keywords')->label('SEO key (255)') ?>
<?= $form->field($model, 'meta_description')->label('SEO descr (255)') ?>
<?= Html::submitButton("Сохранить", [ 'class' => 'btn btn-default' ] ) ?>
<?php ActiveForm::end(); ?>


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
$('#category-caption').on('input',function(){
    var caption = $(this).val();
    var slug = translit(caption);
    $('#category-slug').val(slug);
});
JS;

$this->registerJS($script);