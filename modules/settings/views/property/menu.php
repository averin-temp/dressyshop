<?php

use yii\helpers\Url;

$this->title = "Статусы заказов";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= $action === 'index' ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все типы
        </a>
    </li>
    <li><a href="<?= Url::to(['create']) ?>">Создать тип свойства</a></li>
</ul>

<br/>