<?php

use yii\helpers\Url;

$controller = $this->context->id;
?>

<ul class="nav nav-pills">
    <li <?= ($controller == 'common' )? "class='active'" :''; ?>><a href="<?= Url::to(['common/index']) ?>">Общие настройки</a></li>
    <li <?= ($controller == 'payment' )? "class='active'" :''; ?>><a href="<?= Url::to(['payment/index']) ?>">Способы оплаты</a></li>
    <li <?= ($controller == 'delivery' )? "class='active'" :''; ?>><a href="<?= Url::to(['delivery/index']) ?>">Способы доставки</a></li>
    <li <?= ($controller == 'mails' )? "class='active'" :''; ?>><a href="<?= Url::to(['mails/index']) ?>">Почтовые шаблоны</a></li>
    <!--<li class="stopdisabled" <?= ($controller == 'sms' )? "class='active'" :''; ?>><a href="<?= Url::to(['sms/index']) ?>">СМС шаблоны</a></li>
    <li class="stopdisabled" <?= ($controller == 'promocode' )? "class='active'" :''; ?>><a href="<?= Url::to(['promocode/index']) ?>">Купоны</a></li>-->
    <li <?= ($controller == 'badges' )? "class='active'" :''; ?>><a href="<?= Url::to(['badges/index']) ?>">Бейджи</a></li>
    <li <?= ($controller == 'banner' )? "class='active'" :''; ?>><a href="<?= Url::to(['banner/index']) ?>">Баннеры</a></li>
    <li <?= ($controller == 'status' )? "class='active'" :''; ?>><a href="<?= Url::to(['status/index']) ?>">Статусы заказов</a></li>
    <li <?= ($controller == 'brand' )? "class='active'" :''; ?>><a href="<?= Url::to(['brand/index']) ?>">Бренды</a></li>
    <li <?= ($controller == 'sizerange' )? "class='active'" :''; ?>><a href="<?= Url::to(['sizerange/index']) ?>">Размерные ряды</a></li>
    <li <?= ($controller == 'property' )? "class='active'" :''; ?>><a href="<?= Url::to(['property/index']) ?>">Свойства продуктов</a></li>
    <li <?= ($controller == 'filters' )? "class='active'" :''; ?>><a href="<?= Url::to(['filters/index']) ?>">Фильтры</a></li>
    <li <?= ($controller == 'color' )? "class='active'" :''; ?>><a href="<?= Url::to(['color/index']) ?>">Цвета</a></li>
    <li <?= ($controller == 'seo' )? "class='active'" :''; ?>><a href="<?= Url::to(['seo/index']) ?>">СЕО настройки</a></li>
    <li <?= ($controller == 'regions' )? "class='active'" :''; ?>><a href="<?= Url::to(['regions/index']) ?>">Список регионов</a></li>
</ul>

<br>