<?php

use yii\helpers\Url;

$this->title = "Промокоды";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>


<ul class="nav nav-pills">
    <li <?= $action === 'index' ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['promocode/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все коды
        </a>
    </li>
    <?php if($action === 'index'): ?>
    <li >
        <a href="<?= Url::to(['promocode/create']) ?>">
            Создать промокод
        </a>
    </li>
    <?php endif; ?>
</ul>

<br/>
