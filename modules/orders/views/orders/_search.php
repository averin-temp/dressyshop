<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\orders\models\OrdersFilters;

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

					
				
					
					<div class="admin_filters">
					
						<div class="well well-lg" data-filter="order_number" <?= !in_array('order_number', $activeFilters) ? '' : '' ?>>
							<?= Html::input('hidden','filter[order_number]') ?>
							<?= $form->field($filters, 'order_number')->label('Номер заказа') ?>
						</div>
						
						
						<div class="well well-lg" data-filter="created" <?= !in_array('created', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[created]') ?>

							<div class="form-group field-productsfilters-fromdate">
								<label class="control-label" for="productsfilters-fromdate">Создан не раньше чем</label>
								<input type="date" id="productsfilters-fromdate" class="form-control" name="OrdersFilters[fromDate]" value="<?= $filters->fromDate ?>">

								<p class="help-block help-block-error"></p>
							</div>

							<div class="form-group field-productsfilters-todate">
								<label class="control-label" for="productsfilters-todate">Создан не позднее чем</label>
								<input type="date" id="productsfilters-todate" class="form-control" name="OrdersFilters[toDate]" value="<?= $filters->toDate ?>">

								<p class="help-block help-block-error"></p>
							</div>

						</div>
						
						<div class="well well-lg" data-filter="lastname" <?= !in_array('lastname', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[lastname]') ?>
							<?= $form->field($filters, 'lastname')->label('Фамилия клиента') ?>
						</div>
						
						<div class="well well-lg" data-filter="firstname" <?= !in_array('firstname', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[firstname]') ?>
							<?= $form->field($filters, 'firstname')->label('Имя клиента') ?>
						</div>
						<div class="well well-lg" data-filter="patronymic" <?= !in_array('patronymic', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[patronymic]') ?>
							<?= $form->field($filters, 'patronymic')->label('Отчество клиента') ?>
						</div>
						
						
						 
						<div class="well well-lg" data-filter="status_id" <?= !in_array('status_id', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[status_id]') ?>
							<label class="control-label" for="ordersfilters-status_id">Статус</label>
							<?= Html::dropDownList('OrdersFilters[status_id]',$filters->status_id,$statuses)?>
							<p class="help-block help-block-error"></p>

						</div>
						
						
						<div class="well well-lg" data-filter="fullcost" <?= !in_array('fullcost', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[fullcost]') ?>
							<?= $form->field($filters, 'fullcostFrom')->label('Сумма не меньше чем') ?>
							<?= $form->field($filters, 'fullcostTo')->label('Сумма не больше чем') ?>
							<?= $form->field($filters, 'fullcostDot')->label('Сумма равна') ?>
						</div>

						
						<div class="well well-lg" data-filter="delivery_id" <?= !in_array('delivery_id', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[delivery_id]') ?>
							<label class="control-label" for="ordersfilters-delivery_id">Способ доставки</label>
							<?= Html::dropDownList('OrdersFilters[delivery_id]',$filters->delivery_id,$deliverys)?>
							<p class="help-block help-block-error"></p>
						</div>
						
						
						<div class="well well-lg" data-filter="pay_method" <?= !in_array('pay_method', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[pay_method]') ?>
							<label class="control-label" for="ordersfilters-pay_method">Способ доставки</label>
							<?= Html::dropDownList('OrdersFilters[pay_method]',$filters->pay_method,$pays)?>
							<p class="help-block help-block-error"></p>
						</div>

						
						
						<div class="well well-lg" data-filter="regions" <?= !in_array('regions', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[regions]') ?>
							<label class="control-label" for="ordersfilters-regions"Регион</label>
							<?= Html::dropDownList('OrdersFilters[regions]',$filters->regions,$regions)?>
							<p class="help-block help-block-error"></p>
						</div>
						
						
						<div class="well well-lg" data-filter="adress" <?= !in_array('adress', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[adress]') ?>
							<?= $form->field($filters, 'adress')->label('Адрес') ?>
						</div>
						<div class="well well-lg" data-filter="city" <?= !in_array('city', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[city]') ?>
							<?= $form->field($filters, 'city')->label('Город') ?>
						</div>
						<div class="well well-lg" data-filter="phone" <?= !in_array('phone', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[phone]') ?>
							<?= $form->field($filters, 'phone')->label('Телефон') ?>
						</div>
						<div class="well well-lg" data-filter="admin_comment" <?= !in_array('admin_comment', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[admin_comment]') ?>
							<?= $form->field($filters, 'admin_comment')->label('Комментарий администратора') ?>
						</div>
						<div class="well well-lg" data-filter="user_comment" <?= !in_array('user_comment', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[user_comment]') ?>
							<?= $form->field($filters, 'user_comment')->label('Комментарий покупателя') ?>
						</div>
						<!--<div class="well well-lg" data-filter="articul" <?= !in_array('articul', $activeFilters) ? 'style="display: none"' : '' ?>>
							<?= Html::input('hidden','filter[articul]') ?>
							<?= $form->field($filters, 'articul')->label('Артикул') ?>
						</div>-->
						
					</div>

                   <?= Html::input('submit','','Поиск',['class' => 'btn btn-primary', 'id' => 'submit-button']); ?>
                    <?//= Html::resetButton('Сброс', ['class' => 'btn btn-primary']); ?>

                    <div class="dropdown" style="display: inline-block">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Фильтры
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach($allFilters as $name): ?>
								<?php if($name != 'order_number'){?>
                                <li>
                                    <a href="#" data-filter="<?= $name ?>" <?= in_array($name, $activeFilters) ? 'class="active-filter"' : '' ?>">
                                    <span class="glyphicon glyphicon-ok filter-indicator"></span>
                                    <?= OrdersFilters::label($name) ?>
                                    </a>
                                </li>
								<?php }?>
                            <?php endforeach; ?>
                        </ul>
                    </div>


                    <?php ActiveForm::end() ?>

                </div>
            </div>
        </div><!-- /.col-lg-6 -->

    </div><!-- /.row -->
<style>
#search-form{
	margin-bottom:0!important
}
</style>
<?php

$script = <<< JS

/*----------------------------------------------------------------------------
    Фильтры.
  -------------------------------------------------------------------------- */

/* Выбор фильтра в выпадающем меню  Настройка фильтров */
$('a[data-filter]').click(function(e){
    e.preventDefault();
    var target = $(this).toggleClass('active-filter').attr('data-filter');
    $('div[data-filter='+target+']').fadeToggle(200);
});

/* Отправка формы Фильтров */
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
