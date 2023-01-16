<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\modules\products\models\ProductsFilters;

?>
<div class="row">
    <div class="col-lg-12">

    <div class="panel panel-warning">
        <div class="panel-heading">Фильтры</div>
        <div class="panel-body">




            <?php $t = $filters->formParams;
            $form = ActiveForm::begin([
                'action' => Url::to($t),
                'id' => 'search-form',
                'method' => 'get',
            ]) ?>



            <?php

            $allFilters = array_keys($filters->filters);
            $activeFilters = array_keys($filters->activeFilters);

            ?>

            <div class="well well-lg" data-filter="price" <?= !in_array('price', $activeFilters) ? 'style="display: none"' : '' ?>>
                <?= Html::input('hidden','filter[price]') ?>
                <?= $form->field($filters, 'priceFrom')->label('Цена не меньше чем') ?>
                <?= $form->field($filters, 'priceTo')->label('Цена не больше чем') ?>
            </div>

            <div class="well well-lg" data-filter="created" <?= !in_array('created', $activeFilters) ? 'style="display: none"' : '' ?>>
                <?= Html::input('hidden','filter[created]') ?>

                <div class="form-group field-productsfilters-fromdate">
                    <label class="control-label" for="productsfilters-fromdate">Добавлено не раньше чем</label>
                    <input type="date" id="productsfilters-fromdate" class="form-control" name="ProductsFilters[fromDate]" value="<?= $filters->fromDate ?>">

                    <p class="help-block help-block-error"></p>
                </div>


                <div class="form-group field-productsfilters-todate">
                    <label class="control-label" for="productsfilters-todate">Добавлено не больше чем</label>
                    <input type="date" id="productsfilters-todate" class="form-control" name="ProductsFilters[toDate]" value="<?= $filters->toDate ?>">

                    <p class="help-block help-block-error"></p>
                </div>

            </div>

            <div class="well well-lg" data-filter="vendorcode" <?= !in_array('vendorcode', $activeFilters) ? '' : '' ?>>
                <?= Html::input('hidden','filter[vendorcode]') ?>

                <div class="form-group field-productsfilters-fromdate">
                    <label class="control-label" for="productsfilters-fromdate">Артикул</label>
                    <input type="text" id="productsfilters-fromdate" class="form-control" name="ProductsFilters[vendorcode]" value="<?= $filters->vendorcode ?>">

                    <p class="help-block help-block-error"></p>
                </div>

            </div>

            <div class="well well-lg" data-filter="category" <?= !in_array('category', $activeFilters) ? 'style="display: none"' : '' ?>>
                <?= Html::input('hidden','filter[category]') ?>
                <?= $form->field($filters, 'category')->dropDownList( $categories )->label('Категория товара') ?>
            </div>


            <?= Html::input('submit','','Поиск',['class' => 'btn btn-primary', 'id' => 'submit-button']); ?>
            <?//= Html::resetButton('Сброс', ['class' => 'btn btn-primary']); ?>



            <div class="dropdown" style="display: inline-block">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Настройка фильтров
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach($allFilters as $name): if($name == 'vendorcode'){continue;}?>
                        <li>
                            <a href="#" data-filter="<?= $name ?>" <?= in_array($name, $activeFilters) ? 'class="active-filter"' : '' ?>">
                                <span class="glyphicon glyphicon-ok filter-indicator"></span>
                                <?= ProductsFilters::label($name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>



            <?php ActiveForm::end() ?>

        </div>
    </div>
    </div><!-- /.col-lg-6 -->

</div><!-- /.row -->

<?php

$script = <<< JS

/*----------------------------------------------------------------------------
    Фильтры.
  -------------------------------------------------------------------------- */

$('a[data-filter]').click(function(e){
    e.preventDefault();
    var target = $(this).toggleClass('active-filter').attr('data-filter');
    $('div[data-filter='+target+']').fadeToggle(200);
});
$('#submit-button').click(function(){
    var filters = $(this).closest('#search-form').find('div[data-filter]');
    filters.each(function(){
        
        var that = $(this);
        
        if(that.css('display') == 'none'){
            that.remove();
        } 
            
    });
    $('#search-form').trigger('submit');
});

/*----------------------------------------------------------------------------
    Конец. Фильтры.
  -------------------------------------------------------------------------- */

JS;
$this->registerJs($script);
