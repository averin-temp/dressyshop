<?php

use yii\helpers\Url;

$this->title = "Купоны";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li <?= ($action == 'index' )? "class='active'" :''; ?>>
        <a href="<?= Url::to(['index']) ?>">
            <?php if($action === 'create' || $action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все купоны</a></li>
    <li <?= ($action == 'create' )? "class='active'" :''; ?>><a href="<?= Url::to(['create']) ?>">Добавить купон</a></li>
</ul>


<br/>
