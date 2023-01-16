<?php

use yii\helpers\Url;

$controller = $this->context->id;
?>

<ul class="nav nav-pills">
    <li <?= ($controller == 'returns' )? "class='active'" :''; ?>><a href="<?= Url::to(['returns/index']) ?>">Все заявки на возврат</a></li>
    <li <?= ($controller == 'statuses' )? "class='active'" :''; ?>><a href="<?= Url::to(['statuses/index']) ?>">Статусы возврата</a></li>
</ul>

<br>