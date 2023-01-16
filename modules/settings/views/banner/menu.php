<?php

use yii\helpers\Url;

$this->title = "Баннеры";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['banner/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все баннеры
        </a>
    </li>

    <li <?= ($action == 'create' || $action == 'edit' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['banner/create']) ?>">
            Добавить баннер
        </a>
    </li>

</ul>

<br/>
