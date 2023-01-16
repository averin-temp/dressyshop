<?php

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

?>
<ul class="nav nav-pills">

    <li <?= $action == 'index' ? 'class="active"' : '' ?> >
        <a href="<?= Url::to(['/admin/'.$module.'/categories/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Категории
        </a>
    </li>
    <li <?= $action == 'create' ? 'class="active"' : '' ?> >
        <a href="<?= Url::to(['/admin/'.$module.'/categories/create']) ?>">Создать категорию</a>
    </li>
</ul>
<br/>