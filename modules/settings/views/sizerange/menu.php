<?php

use yii\helpers\Url;

$this->title = "Размерные ряды";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['sizerange/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все размерные ряды
        </a>
    </li>

    <li <?= ($action == 'create' || $action == 'edit' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['sizerange/create']) ?>">
            Добавить ряд
        </a>
    </li>

</ul>

<br/>
