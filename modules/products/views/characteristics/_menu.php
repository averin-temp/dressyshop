<?php
use yii\helpers\Url;
?>
<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['/admin/products/models/index']) ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            Все товары
        </a>
    </li>
</ul>

<br/>

<ul class="nav nav-tabs">
    <li><a href="<?= Url::to(['/admin/products/models/edit', 'id' => $model->id]) ?>"> Редактировать модель</a></li>
    <li class="active"><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Характеристики</a></li>
    <li><a href="<?= Url::to(['/admin/products/seo/edit', 'model' => $model->id]) ?>"><span class="glyphicon glyphicon-knight"></span> SEO</a></li>
    <li><a href="<?= Url::to(['/admin/products/photos/edit', 'model' => $model->id]) ?>"><span class="glyphicon glyphicon-camera"></span> Фотографии</a></li>
</ul>

<br>