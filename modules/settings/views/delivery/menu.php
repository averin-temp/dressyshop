<?php

use yii\helpers\Url;

$this->title = "Способы доставки";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['index']) ?>">
            <?php if($action === 'create' || $action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Список доставок</a></li>
    <li <?= ($action == 'create' )? "class='active'" :''; ?>><a href="<?= Url::to(['create']) ?>">Добавить доставку</a></li>
</ul>

<br/>
