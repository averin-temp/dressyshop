<?php

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to('/admin/users') ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            Пользователи
        </a>
    </li>
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/usergroups']) ?>">Группы пользователей</a></li>
    <?php if($action !== 'edit' && $action !== 'create'): ?><li><a href="<?= Url::to(['/admin/'.$module.'/usergroups/create']) ?>">Создать группу</a></li><?php endif; ?>
</ul>
<br/>
