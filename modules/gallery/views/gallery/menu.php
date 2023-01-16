<?php

use yii\helpers\Url;

$this->title = "Фотоальбомы";

$action = $this->context->action->id;
$module = $this->context->module->id;
$controller = $this->context->id;
?>


<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['index']) ?>">
            <?php if($action !== 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все фотоальбомы
        </a>
    </li>
</ul>
<br/>