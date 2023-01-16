<?php

use yii\helpers\Url;

$this->title = "Значения свойства";
$action = $this->context->action->id;
$controller = $this->context->id;

?>

<?= $this->render('/common/common_menu'); ?>

<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['property/index']) ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            Все типы
        </a>
    </li>
    <li><a href="<?= Url::to(['property/create']) ?>">Создать тип свойства</a></li>
</ul>

<br/>


<ul class="nav nav-tabs">
    <li><a href="<?= Url::to(['property/edit', 'id' => $model->id]) ?>">Редактировать свойство</a></li>
    <li class="active"><a href="##"><span class="glyphicon glyphicon-camera"></span> Доступные значения</a></li>
</ul>