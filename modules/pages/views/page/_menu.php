<?php

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

?>
<ul class="nav nav-pills">

    <li <?= $action == 'index' ? 'class="active"' : '' ?> >
        <a href="<?= Url::to(['/admin/'.$module.'/page/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все страницы
        </a>
    </li>
    <li <?= $action == 'create' ? 'class="active"' : '' ?> >
        <a href="<?= Url::to(['/admin/'.$module.'/page/create']) ?>">Создать страницу</a>
    </li>
</ul>
<br/>