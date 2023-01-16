<?php

use yii\helpers\Url;

$this->title = "Цвета";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['color/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все цвета
        </a>
    </li>

    <li <?= ($action == 'create' || $action == 'edit' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['color/create']) ?>">
            Добавить цвет
        </a>
    </li>

</ul>

<br/>
