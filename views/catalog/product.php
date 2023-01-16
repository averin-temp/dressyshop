<?php
use app\widgets\Breads;
use app\assets\ProductFormAsset;
use yii\helpers\Url;
use app\models\Category;
use app\widgets\LastViewedBottomWidget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\modules\settings\models\Settings;
use app\models\PropertyType;
use app\models\PropertyValue;

ProductFormAsset::register($this);

#avtorkoda 22-08-2017
//$this->title = $product->model->attributes['meta_title'];

?>

<?php
if ($product->image->normal == '') {
    $prod_img = '/web/img/no_big.jpg';
    $minimode = true;
} else {
    $prod_img = $product->image->small;
    $minimode = false;
}



?>


    <main>
        <div class="page product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?= Breads::widget([
                            'path' => Category::getBreadPath($product->model->categories[0]),
                            'home' => Url::to(['catalog/index']),
                            'last' => $product->model->name.' '.$product->model->vendorcode
                        ]) ?>
                    </div>
                </div>
                <div class="row product_body" id="product_container" data-color-id="<?= $product->color_id ?>"
                     data-model-id="<?= $product->model_id ?>" data-product-id="<?= $product->id ?>">
<?php if(!$minimode){ ?>
                    <div class="col-md-1 clearfix product_body_left">
                        <div class="product_body_left_mins">
                            <?= $this->render('images_list', ['product' => $product]) ?>
                        </div>

                    </div>
    <?php } ?>
                    <div class="preload_image_list col-md-<?php if(!$minimode){ ?>5<?php } else{ ?>6<?php } ?>  mib_img_prod" <?php if($minimode){ ?>style="padding-left: 15px;"<?php }?>>
                        <img id="product_preview" src="<?= $prod_img ?>"
                             data-large="<?= $product->image->noscaled ?>" alt=""></div>
                    <div class="col-md-6 prod_body">
                        <div class="prod_body_top_right">
                            <a href="##">
                                <span></span>
                                <span>Доставка</span>
                            </a>
                            <a href="##">
                                <span></span>
                                <span>оплата</span>
                            </a>


                            <a href="##" id="postpound" class="favor">
                                <span></span>
                                <span>отложить</span>
                            </a>

                            <a href="/catalog/Accessories">
                                <span></span>
                                <span>дополнить образ</span>
                            </a>


                        </div>
                        <h1 id="label-product-category"><?= $product->model->name ?></h1>
                        <div class="prod_body_top ">
                            <div class="prod_body_top_left">
                                <div class="prod_body_top_left_top">
                                    Артикул: <?= $product->model->vendorcode ?><br>

                                    Производитель: <a href="<?= Url::toRoute(['catalog/brand', 'slug' => $product->model->brand->slug ]) ?>"><?= $product->model->brand->name ?></a>
                                </div>
								<?php //var_dump($product->model->final_price);?>
                                <div class="prod_body_top_left_bottom">
                                    <div class="prod_body_top_left_bottom_oldprice" id="discount">
                                        <?php if ($product->model->discount): ?>
                                            <span><?= $product->model->final_price ?>
                                                руб.</span> (-<?= $product->model->discount ?>%)
                                        <?php endif ?>
                                    </div>
                                    <div class="prod_body_top_left_bottom_price"><span
                                            id="price"><?= $product->model->price ?></span> руб.
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="prod_body_middle">
                            <?php if ($product->model->colors[0]->attributes['code'] == '#null' && $product->model->colors[0]->attributes['id'] != 24) { ?>
                                
                            <?php } else { ?>
							<div class="prod_form_item">
                                    <span class="label_product">выберите цвет:</span>
                                    <div class="color_picker">
                                        <select>
                                            <?php foreach ($product->model->colors as $color): ?>
                                                <?php $color_class = mb_substr($color->code, 1);
                                                $color_class = 'c_' . $color_class; ?>
                                                <option class="<?= $color_class ?>"
                                                        value="<?= $color->id ?>" <?= $color->id == $product->color_id ? 'selected' : '' ?> ><?= $color->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
							<?php } ?>
                            <?php if (($sizerange = $product->model->sizeRange) && count($sizerange->sizes)): ?>
                                <div class="prod_form_item">
                                    <span class="label_product">выберите размер:</span>
                                    <ul class="prod_sizes">
                                        <?= $this->render('sizes', ['product' => $product]) ?>
                                    </ul>
                                    <a href="<?= Settings::get('table_image') ?>" data-fancybox
                                       data-caption="Таблица размеров" class="size_table">
                                        Таблица размеров
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="prod_body_bottom">
                            <div class="prod_body_bottom_top">
                                <a data-product="<?= $product->id ?>" href="##" class="button">В корзину</a><span class="selectsizepan">Выберите размер!</span>
                                <a href="##">купить в один клик</a>
                            </div>
                        </div>


                    </div>
                    <!--                    <div class="col-md-2 prod_body">-->
                    <!--                        -->
                    <!--                    </div>-->
                </div>
                <div class="row product_tabs" id="chars">
                    <div class="col-md-11 col-md-offset-1">
                        <div class="product_tabs_header tabs_header" id="tabs_product">
                            <div class="product_tabs_header_inner">
                                <ul>
                                    <li <?= $tab == "description" ? 'class="active"' : '' ?>><span><a href="#chars">описание</a></span></li>
                                    <li <?= $tab == "review" ? 'class="active"' : '' ?>><span><a href="#chars">отзывы</a></span></li>
                                    <li <?= $tab == "question" ? 'class="active"' : '' ?>><span><a href="#chars">вопросы</a></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product_tabs_body tabs_body" id="tabs_product">
                            <div class="clearfix tab  <?= $tab == "description" ? 'active' : '' ?>">
                                <?php if($product->model->description !='') {?>
                                <div class="tabdesc tabdesc1">
                                    <?= ($description = nl2br($product->model->description)) ? $description : 'нет описания' ?>
                                </div>
                                <?php } ?>
<?php  if (empty($characteristics)){?>
                                <div  <?php if($product->model->description != '') {?> class="tabdesc"  <?php } ?>>

                                    <?php $characteristics = $product->model->characteristics;

                                    if (empty($characteristics)) echo 'Характеристики товара отсуствтуют';
                                    else {
                                        $options = [];
                                        foreach ($characteristics as $option) {
                                            $options[$option->type_id][] = $option;
                                        }

                                        $types_ids = array_keys($options);
                                        $types = PropertyType::find()->where(['id' => $types_ids])->indexBy('id')->all();

                                        $ids = [];
                                        foreach ($options as $type_id => $values) {
                                            $type = $types[$type_id];
                                            if ($type->format == PropertyType::IS_UNION) {
                                                foreach ($values as $option) {
                                                    $ids[$option->value] = '';
                                                }
                                            }
                                        }
                                        $values_ids = array_keys($ids);
                                        $property_values = PropertyValue::find()->where(['id' => $values_ids])->indexBy('id')->all();




                                        foreach ($types as $type_id => $type) {

                                            echo '<p class="clearfix chars_row">';
                                            echo '<span>' . $type->name . '</span>: ';

                                            $temp = $options[$type_id];

                                            if ($type->format == PropertyType::IS_TEXT) {

                                                $option = $temp[0];
                                                $value = $option->value_text;
                                                echo '<i style="float: right">' . $value . '</i>';
                                            }

                                            if ($type->format == PropertyType::IS_NUMBER) {
                                                $option = $temp[0];
                                                $value = $option->value;
                                                echo '<i style="float: right">' . $value . '</i>';
                                            }

                                            if ($type->format == PropertyType::IS_UNION) {

                                                $values = [];
                                                foreach ($temp as $option) {
                                                    $propertyValue = $property_values[$option->value];
                                                    $values[] = $propertyValue->name;
                                                }

                                                echo '<i style="float: right">' . implode(', ', $values) . '</i>';

                                            }

                                            echo '</p>';
                                        }


                                    }

                                    ?>
                                </div>
<?php }?>


                            </div>
                            <div class="tab <?= $tab == "review" ? 'active' : '' ?>">
                                <div class="product_reviews">
                                    <div class="product_reviews_header">
                                        <a href="##" class="add_rev">Оставить отзыв</a>
                                        <div class="add_rev_form prodrevform">

                                            <?php $form = ActiveForm::begin(['action' => ['/catalog/review', 'id' => $product->id] ]) ?>
                                            <?= $form->field($review, 'name', ['inputOptions' => ['placeholder' => 'Ваше имя', 'class' => '']])->label(false); ?>
                                            <?= $form->field($review, 'model_id')->hiddenInput()->label(false); ?>
                                            <?php /* $form->field($review, 'email', ['inputOptions' => [ 'placeholder' => 'Ваш e-mail' , 'class' => '' ]])->label(false);*/ ?>
                                            <?= $form->field($review, 'content')->textarea(['placeholder' => 'Текст отзыва', 'cols' => 30, 'rows' => 10, 'class' => ''])->label(false); ?>
                                            <div class="noactivestars form-stars stars">
                                                <ul><?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <li><span><input type="checkbox" name="Reviews[evaluation]"
                                                                         value="<?= $i ?>" <?= $i == $review->evaluation ? "checked" : '' ?>
                                                                         style="display: none"></span></li>
                                                    <?php endfor; ?></ul>
                                                <?= Html::submitButton("Отправить", ['class' => 'button']) ?>
                                                <?php ActiveForm::end() ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($reviewMessage)): ?>
                                        <div class="review-message"><?= $reviewMessage ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($reviewMessageError)): ?>
                                        <div class="review-message"><?= $reviewMessageError ?></div>
                                    <?php endif; ?>
                                    <div class="product_reviews_body">

                                        <?= count($reviews) ? "" : "Нет отзывов" ?>
                                        <?php foreach ($reviews as $review):

                                            ?>
                                            <div class="product_reviews_body_item clearfix">
                                                <div
                                                    class="product_reviews_body_item_name"><?= $review->name ?>   <?= date_create($review->created)->Format('d-m-Y'); ?></div>
                                                <div class="product_reviews_body_item_stars">
                                                    <div class="noactivestars stars<?= $review->evaluation ?>">
                                                        <ul>
                                                            <li><span></span></li>
                                                            <li><span></span></li>
                                                            <li><span></span></li>
                                                            <li><span></span></li>
                                                            <li><span></span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div
                                                    class="product_reviews_body_item_text"><?= $review->content ?></div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="tab quests <?= $tab == "question" ? 'active' : '' ?>">
                                <div class="product_reviews_header">
                                    <a href="##" class="add_rev">Задать вопрос</a>
                                    <div class="add_rev_form">
                                        <?php $form = ActiveForm::begin(['action' => ['/catalog/question', 'id' => $product->id] ]) ?>
                                        <?= $form->field($question, 'name', ['inputOptions' => ['placeholder' => 'Ваше имя', 'class' => '']])->label(false); ?>
                                        <?= $form->field($question, 'model_id')->hiddenInput()->label(false); ?>
                                        <?php /* $form->field($question, 'email', ['inputOptions' => [ 'placeholder' => 'Ваш e-mail' , 'class' => '' ]])->label(false); */ ?>
                                        <?= $form->field($question, 'content')->textarea(['placeholder' => 'Текст вопроса', 'cols' => 30, 'rows' => 10, 'class' => ''])->label(false); ?>
                                        <?= Html::submitButton("Отправить", ['class' => 'button']) ?>
                                        <?php ActiveForm::end() ?>
                                    </div>
                                </div>
                                <?php if (!empty($questionMessage)): ?>
                                    <div class="review-message"><?= $questionMessage ?></div>
                                <?php endif; ?>
                                <?php if (!empty($questionMessageError)): ?>
                                    <div class="review-message"><?= $questionMessageError ?></div>
                                <?php endif; ?>
                                <div class="product_quests_body">
                                    <?= count($questions) ? "" : "Нет вопросов"; ?>
                                    <?php foreach ($questions as $question): ?>
                                        <div class="product_reviews_body_item clearfix">
                                            <div
                                                class="product_reviews_body_item_name"><?= $question->name ?>  <?= date_create($question->created)->Format('d-m-Y'); ?></div>
                                            <span style="text-decoration: underline;">Вопрос Покупателя:</span><br><div class="product_reviews_body_item_quest"><?= $question->content ?></div>
                                            <br><span style="text-decoration: underline;">Ответ магазина:</span><br> <div class="product_reviews_body_item_text"><?= $question->answer ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= LastViewedBottomWidget::widget() ?>
            </div>
        </div>
        </div>
    </main>
<?php
$postpound_url = Url::to(['account/postpound']);  // отложить товар
$script = <<< JS

/*---------------------------------------------------------------
    Карточка товара 
--------------------------------------------------------------- */

$('.form-stars li').on('click',function(){
    var checkbox = $(this).find('input');
    checkbox.prop('checked', true);
    $(this).closest('.form-stars').attr('class', 'noactivestars form-stars stars' + checkbox.val() );
});

// Таблица размеров
$("[data-fancybox]").fancybox();






$('.prod_sizes').on('click', 'li', function () {
    if (!$(this).hasClass('no-instock')) {
        $('.prod_sizes li').removeClass('active');
        $(this).addClass('active');
    }
});

// Изменение цвета в карточке товара
$('#product_container .color_picker select').on("change", function(){
    var container = $('#product_container');
    var data = {
        product_id: container.attr('data-product-id'),
        color_id: $(this).val()
    };
    drassy_callback("Изменение цвета в карточке товара", data);
    $.post('/ajax/catalog/ajax_change', data , function(data){
        drassy_callback("Ответ на зменение цвета в карточке товара", data);
        container.attr('data-product-id', data.product);
        container.find('[data-product]').attr('data-product',  data.product);
        $('.product_body_left_mins').html(data.imagesContent);
        $('.prod_sizes').html(data.sizes);
        initImages();
        //vendorCode TODO:
    }, 'json').fail(function(xhr, txt, error){
        console.log(error);
    });
});




$('.prod_body_top_right > a:nth-child(1)').click(function () {
    $.pp_open('popup_delivery')
});

$('.prod_body_top_right > a:nth-child(2)').click(function () {
    $.pp_open('popup_pay')
});




function initImages()
{
    $(".product_body_left_mins_item").mouseenter(function(){
        $('.product_body_left_mins_item').removeClass('active');
        var that = this;
        $(that).addClass('active');

        $("#product_preview").fadeOut(0, function(){
            $(this)
            .attr("src",$(that).attr("data-normal"))
            .attr("data-large",$(that).attr("data-large"))
            .fadeIn(0);
        });
    });

    var activeElement = $('.product_body_left_mins_item.active');
    $("#product_preview").attr("src",activeElement.attr("data-normal"))
    .attr("data-large",activeElement.attr("data-large"))
    .fadeIn(0);

}
initImages();

/*---------------------------------------------------------------
    Конец. Карточка товара
--------------------------------------------------------------- */

JS;
?>
<?php if (\Yii::$app->user->isGuest) {
    $script2 = <<< JS
    $('#postpound').click(function(){
            $.pp_open('popup_enter')
        })
JS;
} else {
    $script2 = <<< JS
   $('#postpound').click(function(){
    var id = $('.product_body[data-product-id]').attr('data-product-id');
    var data = { id: id };
    drassy_callback('отложить товар', data);
    $.get( "$postpound_url", data,
      function(){
         drassy_callback('товар отложен', {});
      });
}); 
JS;
}
?>

<?php if(!$minimode){
$script3 = <<< JS
// ZoomSL плагин
$(".mib_img_prod img").imagezoomsl({zoomrange: [3, 3]});
JS;
}?>


<?php
$this->registerJS($script);
$this->registerJS($script2);
$this->registerJS($script3);
