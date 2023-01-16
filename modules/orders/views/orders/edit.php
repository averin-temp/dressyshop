<?php
use app\assets\AdminAsset;
use yii\bootstrap\Html;

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = "Заказ";


?>

<?= $this->render('_menu') ?>

<style>
.panel-body{
	padding:15px 0
}
</style>
<div>

    <!-- Nav tabs -->
    <!--<ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#order-details" aria-controls="order-details" role="tab" data-toggle="tab">Заказ</a></li>
        <li role="presentation"><a href="#change-history" aria-controls="change-history" role="tab" data-toggle="tab">История изменений</a></li>
    </ul>-->

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="order-details">


			<div class="panel panel-default">
                <div class="panel-heading">Информация</div>
                <div class="panel-body">
				<div class="col-md-6">
					<dl class="dl-horizontal guestbook-view viewordertop">
						<dt>Номер заказа:</dt>
						<dd><?=$order->order_number?></dd>

						<dt>Дата создания:</dt>
						<dd><?=$order->created?></dd>
						
						<dt>Последнее изменение:</dt>
						<dd><?=$order->created?></dd>
						
						<dt>Способ доставки:</dt>
						<dd><?=$order->delivery->caption?></dd>
						
						<dt>Цена доставки:</dt>
						<dd><?= $order->delivery_price ?></dd>
						
						<dt>Способ оплаты:</dt>
						<dd><?=$order->pay->caption?></dd>
						
						
					</dl>
                </div>
				<div class="col-md-6">
				<?php $statusForm = ActiveForm::begin([
                        'id' => 'status-form'
                    ]) ?>

					<div class="col-md-12">
                    <?= $statusForm->field($statusModel, 'status_add')->textInput(['value' => $order->status_add])->label("Номер отправления") ?>
					</div>
					<div class="col-md-12">
                    <?= $statusForm->field($statusModel, 'status')->dropDownList($statuses)->label("Статус заказа") ?>
					</div>
					<div class="col-md-12">
                    <?= Html::submitButton('Сохранить статус') ?>
					</div>
                    <?php ActiveForm::end() ?>
                </div>
                </div>
            </div>
			

			<div class="panel panel-default">
                <div class="panel-heading">Покупатель</div>
                <div class="panel-body">
				<div class="col-md-12">
                    <div class="form-horizontal">
						<div class="form-group">
                            <label for="user-lastname" class="col-sm-2 control-label">Фамилия</label>
                            <div class="col-sm-10">
                                <input type="text" name="lastname" class="form-control" id="user-lastname" value="<?= $order->lastname ?>" >
                            </div>
                        </div>
						
						
                        <div class="form-group">
                            <label for="user-name" class="col-sm-2 control-label">Имя</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="user-name" value="<?= $order->firstname ?>" >
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label for="user-patronymic" class="col-sm-2 control-label">Отчество</label>
                            <div class="col-sm-10">
                                <input type="text" name="patronymic" class="form-control" id="user-patronymic" value="<?= $order->patronymic ?>" >
                            </div>
                        </div>

					<div class="form-group">
                            <label for="user-email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" name="user_name" class="form-control" id="user-email" value="<?= $order->email ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user-phone" class="col-sm-2 control-label">Телефон</label>
                            <div class="col-sm-10">
                                <input type="text" name="phone" class="form-control" id="user-phone" value="<?= $order->phone ?>" >
                            </div>
                        </div>
						<div class="form-group">
                            <label for="user-zip_code" class="col-sm-2 control-label">Индекс</label>
                            <div class="col-sm-10">
                                <input type="text" name="user_name" class="form-control" id="user-zip_code" value="<?= $order->zip_code ?>" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user-region" class="col-sm-2 control-label">Регион</label>
                            <div class="col-sm-10">
                                <input type="text" name="user_name" class="form-control" id="user-region" value="<?= $order->regiona->name ?>" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user-city" class="col-sm-2 control-label">Город</label>
                            <div class="col-sm-10">
                                <input type="text" name="user_name" class="form-control" id="user-city" value="<?= $order->city ?>" >
                            </div>
                        </div>
						<div class="form-group">
                            <label for="user-adress" class="col-sm-2 control-label">Адрес</label>
                            <div class="col-sm-10">
                                <input type="text" name="user_name" class="form-control" id="user-adress" value="<?= $order->adress ?>" >
                            </div>
                        </div>


                        <div class="form-group">

                            <label for="user_comment" class="col-sm-2 control-label">Комментарий покупателя</label>
                            <div class="col-sm-10">
                                <textarea  name="user_comment" class="form-control" id="" cols="30" rows="3"><?=  $order->user_comment ?></textarea>
                            </div>

                        </div>



                        

                    </div>
                    </div>
                </div>
            </div>
			
			

            <div class="panel panel-default">
                <div class="panel-heading">Состав заказа</div>

                    <table class="table table-hover">
                        <tr>
                            <td>Название</td>
                            <td>Артикул</td>
                            <td>Размер</td>
                            <td>Цвет</td>
                            <td>Цена</td>
                            <td>Удалить</td>
                        </tr>

                    <?php foreach($products as $product): ?>
                        <tr>
                            <td><a href="/<?= $product->model->slug  ?>" target="_blank"><?= $product->model->name ?></a></td>
                            <td><a href="/<?= $product->model->slug  ?>" target="_blank"><?= $product->model->vendorcode ?></a></td>
                            <td><a href="/<?= $product->model->slug  ?>" target="_blank"><?= $product->size->name ?></a></td>
                            <td><a href="/<?= $product->model->slug  ?>" target="_blank"><?= $product->color->name ?></a></td>
                            <td><a href="/<?= $product->model->slug  ?>" target="_blank"><?= $product->model->final_price ?></a></td>
                            <td><a href="<?= Url::to(['delprod' ,'id' => $product->id]) ?>">Удалить</a></td>
                        </tr>

                    <?php endforeach; ?>
                    </table>


                        <!--<div class="col-xs-6">

                            <div class="form-group">
                                <label for="">Промо код</label>
                                <input type="text" placeholder="Промо код" value="<?= $order->promo ? $order->promo->code : '' ?>" disabled>
                                <?= $order->promo ? '<span>'.$order->promo->discount.'% скидка</span>' : '' ?>
                            </div>


                        </div>-->
						<div class="panel-body">
						
                        <div class="col-md-12">

                                <table width=100%  >
                                    <tr>
                                        <td>Общая стоимость товаров </td><td><?= $order->cost ?></td>
                                    </tr>
                                    <tr>
                                        <td>Стоимость доставки </td><td><?= $order->delivery_price ?></td>
                                    </tr>
                                    <tr>
                                        <td>Итоговая стоимость заказа.</td><td><?= ($order->fullcost) ?></td>
                                    </tr>
                                </table>


                            </div>


                        </div>


            </div>




            <div class="panel panel-default">
                <div class="panel-heading">Комментарий</div>
                <div class="panel-body">
					<?php $comment_form = ActiveForm::begin() ?>
						<?= $comment_form->field($comment,'comment', [
							'template' => "{label}<div class='col-sm-10'>{input}{error}</div>"
						])->label('Комментарий менеджера', ['class' => 'col-sm-2 control-label'])->textarea(['class' => 'form-control', 'rows'=>'3','style'=>'resize:none']) ?>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<?= Html::submitButton("Сохранить комментарий", [ 'class' => 'btn btn-success' ]) ?>
							</div>
						</div>
					<?php ActiveForm::end() ?>
                </div>
            </div>


			
			

						
						
						

        </div>
        <div role="tabpanel" class="tab-pane" id="change-history">...</div>

    </div>


    <?php $deblockForm = ActiveForm::begin([
        'action' => ['deblock', 'id' => $order->id]
    ]) ?>
        <?= Html::submitButton('Выйти и разблокировать раздел', ['class' => 'btn btn-warning']) ?>
    <?php ActiveForm::end()?>


</div>




