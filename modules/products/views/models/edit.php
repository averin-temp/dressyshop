<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\Settings;
use yii\bootstrap\Html;
use app\classes\PropertyHelper;
use app\assets\ChainedAsset;

ChainedAsset::register($this);

$this->title = "Редактировать модель";

?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?php $form = ActiveForm::begin([ 'action' => ['save'], 'method' => 'post' , 'id' => 'model_form']) ?>
<div class="row">

    <div class="col-md-6">

        <?= $form->field($model, 'id', [ 'template' => '{input}' , 'options' => [ 'tag' => false ] ])->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'name')->label('Название') ?>
        <?= $form->field($model, 'slug', ['enableAjaxValidation' => true])->label('slug') ?>
        <?= $form->field($model, 'vendorcode', ['enableAjaxValidation' => true])->label('Артикул') ?>
        <?= $form->field($model, 'model_type')->label('Тип товара') ?>
        <?= $form->field($model, 'brand_id')->dropDownList(ArrayHelper::map($brands, 'id', 'name'), [ 'prompt' => 'Выберите брэнд' ])->label('Брэнд') ?>

        <?php #avtorkoda 15-08-2017
          // отобразить список категорий по кол-ву вложенностей
          // использовать плагин jquery chained
          // запрос в базу
          $rows = (new \yii\db\Query())
                ->select(['id', 'caption', 'parent_id'])
                ->from('category')
                #->where(['last_name' => 'Smith'])
                #->limit(10)
                ->all();

           /*
               $model->categories_id;
               Array
(
    [0] => 90
    [1] => 91
)
               $model->attributes['id']
           */
           $category=array();
           foreach($rows as $z => $data) {
            $category[(int)$data['parent_id']][$data['id']] = $data['id'];
            $category['caption'][$data['id']] = $data['caption'];
            $category['id'][$data['id']] = $data['parent_id'];
           }

           #echo '<input name="categories_id" value="" type="hidden">';
           $category1=$category2=$category3='<option value="0">--</option>';

           foreach($category[0] as $z => $id) {
            $category1 .= '<option value="'.md5('a'.$id).'" data-id="'.$id.'">'.$category['caption'][$id].'</option>';
                if (isset($category[$id]) && sizeof($category[$id])) {
                foreach($category[$id] as $z2 => $id2) {
                    $category2 .= '<option value="'.md5('b'.$id2).'" data-chained="'.md5('a'.$id).'">'.$category['caption'][$id2].'</option>';
                    if (isset($category[$id2]) && sizeof($category[$id2])) {
                    foreach($category[$id2] as $z3 => $id3) {
                        $category3 .= '<option value="'.$id3.'" data-id="'.$id3.'" data-chained="'.md5('b'.$id2).'">'.$category['caption'][$id3].'</option>';
                    }
                    }
                }
                }
           }

           echo '<label class="control-label" for="category1">Категория /уровень 1</label><br>';
           echo '<select size=1 id="category1" class="form-control">';
           echo $category1;
           echo '</select><br>';

           echo '<label class="control-label" for="category2">Категория /уровень 2</label><br>';
           echo '<select size=1 id="category2" class="form-control">';
           echo $category2;
           echo '</select><br>';

           echo '<div class="form-group field-categories_id">
                <label class="control-label" for="categories_id">Категория /уровень 3</label><br>';
           echo '<select size=5 name="categories_id[]" id="categories_id" class="form-control" aria-required="true" multiple=multiple>';
           echo $category3;
           echo '</select><p class="help-block help-block-error"></p></div>';


           if (sizeof($model->categories_id)) {

               $levev3= $model->categories_id; #3
               $levev2= md5('b'.$category['id'][$model->categories_id[0]]); #2
               $levev1= md5('a'.$category['id'][$category['id'][$model->categories_id[0]]]); #1

           } else {
                $levev1=$levev2=$levev3=0;

           }
        ?>
        <script>
        $( document ).ready(function() {
            $("#category1").val('<?= $levev1; ?>');
            $("#category2").val('<?= $levev2; ?>');

            $("select#category2").chainedTo("select#category1");
            $("select#categories_id").chainedTo("select#category2");
            <?php
              if ($levev3!=0 && is_array($levev3)) {
                foreach($levev3 as $z => $id) {
                  echo ';$("#categories_id option[value='.$id.']").prop("selected", true);
                  ';
                }
              }

            ?>

        });
        </script>
   <!--
        <?php echo $form->field($model, 'categories_id')->dropDownList(ArrayHelper::map($categories, 'id', 'caption'), [ 'multiple' => 'multiple',])->label('Категория') ?>
-->

        <?= $form->field($model, 'sizerange')->dropDownList(ArrayHelper::map($sizeranges, 'id', 'name'), [ 'prompt' => 'Выберите размерный ряд' ])->label('Размерный ряд') ?>
        
      
    </div>
    <div class="col-md-6">
		<?= $form->field($model, 'purchase_price')->label('Закупочная цена') ?>
		<?= $form->field($model, 'bel_price')->label('Белорусская закупочная цена') ?>
        <?= $form->field($model, 'fixed_price')->label('Фиксированная цена') ?>

        <div class="form-group" style="display: none">
            <?= Html::textInput('formula', Settings::get('formula'), [ 'id' => 'formula', 'disabled' => true, 'class' => 'form-control' ] ) ?><br>
            <?= Html::textInput('formula_bel', Settings::get('formula_bel'), [ 'id' => 'formula_bel', 'disabled' => true, 'class' => 'form-control' ] ) ?>
        </div>
        
        <?= $form->field($model, 'final_price', [ 'inputOptions' => ['disabled' => true] ])->label('Итоговая цена (без учета скидок)') ?>

        <div class="form-group">
            <label class="control-label" for="preview_price">Цена со скидкой:</label>
            <?= Html::textInput('preview_price', '', [ 'id' => 'preview_price', 'disabled' => true, 'class' => 'form-control' ] ) ?>
        </div>

        <?= $form->field($model, 'active')->checkbox()->label('Показывать на сайте') ?>
        <?= $form->field($model, 'description')->textarea(['rows' => '11','style'=>'resize:none;     height: 231px;'])->label('Описание') ?>
		<?= $form->field($model, 'badge_id')->dropDownList(ArrayHelper::map($badges, 'id', 'name'),
            [
                'prompt' => 'Без бэйджа' ,
                'options' => ArrayHelper::map(
                    array_map(function($item) {
                        return [ 'id' => $item->id,
                            'option' => [
                                'data-discount' => $item->discount]
                        ];
                    }, $badges),
                    'id',
                    'option'
                )
            ])->label('Бэйдж') ?>
			<?= $form->field($model, 'sort')->label('Сортировка') ?>

    </div>
</div>


<?= Html::submitButton('Сохранить изменения',['class' => 'btn btn-success']) ?>


<?php ActiveForm::end() ?>

<?php

$script = <<< JS
/*---------------------------------------------------------------
Редактор модели
--------------------------------------------------------------- */
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
$('#name').on('input',function(){
    var caption = $(this).val();
    var slug = translit(caption);
    $('#slug').val(slug);
});


$('#w0').on('submit', function(){
    if(!$(this).find('.has-error').length)
        $(this).find('[disabled]').prop('disabled', false);
});

$('#purchase_price, #fixed_price, #bel_price').on('input', calculatePrice);
$('[name=badge_id]').change(calculatePrice);

function calculatePrice()
{
    var purchasePrice, fixedPrice, bel_price, result;
    var finalPrice = $('#final_price');
    
    var valid = function(val){
        if(val.length && val.val() !== ''){
            var value = Number(val.val());
            if(value !== NaN && value >= 0)
                return value;
        }
        return false;
    };

	
	
	if($(this).attr('id') == 'bel_price' && $('#bel_price').val() == ''){
		purchasePrice = valid($('#purchase_price'));
	}
    else if($(this).attr('id') == 'bel_price' || $('#bel_price').val() != ''){
		purchasePrice = valid($('#bel_price'));
	}
	else{
		purchasePrice = valid($('#purchase_price'));
	}
	
	
    if(purchasePrice) {   
        result = purchasePrice;
    } else {
        result = '0.00';
    }
    
    if(fixedPrice = valid($('#fixed_price'))){
        result = fixedPrice;
    }
	var str;
	
	if($(this).attr('id') == 'bel_price' && $('#bel_price').val() == ''){
		str = $('#formula').val();  
	}
	else if($(this).attr('id') == 'bel_price' || $('#bel_price').val() != ''){
		str = $('#formula_bel').val();  
	}
	else{
		str = $('#formula').val();  
	}
    
    var check = str.match(/^\s*[*]\s*(\d+)\s*(([+-])\s*(\d+))?$/);               
 
    if(check !== null){
        
        var multiplier = Number(check[1]);
        result *= multiplier;
        
        if(check.length > 4)
        {
            var operand = check[3];
            var number = Number(check[4]);
            
            switch (operand){
                case '+':
                    result += number;
                    break;
                case '-':
                    result -= number;
                    break;
            }
        }
        
        if(result < 0) result = 0;
    } 
    
	resint = result;
	if($(this).attr('id') == 'fixed_price' && $('#fixed_price').val() == ''){
		result = resint;
	}
	else if($(this).attr('id') == 'fixed_price' || $('#fixed_price').val() != ''){
		result = parseInt($('#fixed_price').val())
	}
	else{		
		result = resint;
	}
    finalPrice.val(result.toFixed(2));
    
    var badgeDiscount = Number($('select[name=badge_id] option:selected').attr('data-discount'));
    if(badgeDiscount !== NaN && badgeDiscount >= 0) 
        result -= result * badgeDiscount * 0.01; 
    
    var DiscountPrice = $('#preview_price');
    DiscountPrice.val(result.toFixed(2));
  
}

calculatePrice();


/*---------------------------------------------------------------
Редактор модели. Конец.
--------------------------------------------------------------- */
JS;

$this->registerJS($script);