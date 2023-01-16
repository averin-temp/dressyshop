<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Редактировать бренд";

?>

<?= $this->render('menu') ?>

<div class="panel panel-primary">
    <div class="panel-heading">Бренд</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['save'],
        ]); ?>
        <?= \yii\bootstrap\Html::hiddenInput('id',$model->id) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'slug', ['enableAjaxValidation' => true])->label('Слаг') ?>
        <?= $form->field( $model , 'image')->fileInput()->label('Изображение'); ?>
        <div class="form-group">
            <img id="preview" style="max-width: 200px; height: auto;" src="<?= $model->image ?>" alt="">
        </div>

        <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

$script = <<< JS
$('[name="Brand[image]"]').change(function(){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#preview').get(0).src = reader.result;
    };
    reader.readAsDataURL(this.files[0]);
});

// транслит
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
$('#brand-name').on('input',function(){
    var caption = $(this).val();
    var slug = translit(caption);
    $('#brand-slug').val(slug);
});


JS;

$this->registerJS($script);
?>
