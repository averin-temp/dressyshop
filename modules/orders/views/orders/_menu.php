<?php

use yii\helpers\Url;

$action = $this->context->action->id;

?>


<ul class="nav nav-pills">
    <li <?= $action == 'index' ? 'class="active"' : "" ?>>
        <a href="<?= Url::to(['index', 'sort'=>'-id']) ?>">
            <?php if($action != 'index') : ?>
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Список заказов
        </a>
    </li>
    <?php if($action != 'index') : ?>
    <!--<li><a href="">Печать заказа</a></li>-->
    <?php endif; ?>
</ul>
<br>