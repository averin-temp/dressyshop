<?php

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

?>
<ul class="nav nav-pills">

    <li>
        <a href="<?= Url::to(['/admin/'.$module]) ?>">
            <?php if($action !== 'index') : ?><i class="glyphicon glyphicon-chevron-left font-12"></i><?php endif ?>
            Все товары
        </a>
    </li>
    <li <?= $action == 'create' ? 'class="active"' : '' ?> >
        <a href="<?= Url::to(['/admin/'.$module.'/models/create']) ?>">Добавить товар</a>
    </li>
</ul>
<br>