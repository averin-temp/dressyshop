<?php

use yii\helpers\Url;

$this->title = "Бейджи";
$action = $this->context->action->id;

?>

<?= $this->render('/common/common_menu'); ?>

<?php if($action === 'edit'): ?>
<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['badges/index']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            Все бейджи
        </a>
    </li>
</ul>
<?php endif; ?>
<br/>
