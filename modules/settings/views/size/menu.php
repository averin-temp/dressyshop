<?php

use yii\helpers\Url;

$this->title = "Размерный ряд";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['sizerange/index']) ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            Все размерные ряды
        </a>
    </li>

    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['size/index', 'range' => $range]) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все размеры
        </a>
    </li>

    <li <?= ($action == 'create' || $action == 'edit' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['size/create', 'range' => $range ]) ?>">
            Добавить размер
        </a>
    </li>

</ul>

<br/>