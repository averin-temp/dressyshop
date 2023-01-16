<?php
use app\modules\settings\models\Settings;
use yii\helpers\Url;
?>
<script>
    history.pushState(null, null, '/')
</script>
<main>
    <div class="page thankspage">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    Благодарим за Ваш заказ, <?=$_GET['order_name']?>. <br>
                    Номер заказа: <strong><?=$_GET['order_id']?></strong>.<br>

                    Более полная информация о заказе доступна в <a href="<?= Url::to(['account/index']) ?>">Личном кабинете</a>.<br>

					Уточнить информацию о сроках и статусе исполнения заказа либо внести изменения в заказ Вы можете у наших менеджеров по телефону: <?= Settings::get('phone1') ?> или в <a href="/page/gostevaya_kniga">Гостевой книге</a>.

                </div>
            </div>
        </div>
    </div>
</main>