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
    <li class="active"><a href="#">Редактировать модель</a></li>
    <?php if($model->id): ?>
        <li><a href="<?= Url::to(['/admin/products/characteristics/edit', 'model' => $model->id]) ?>"><span class="glyphicon glyphicon-list-alt"></span> Характеристики</a></li>
        <li><a href="<?= Url::to(['/admin/products/seo/edit', 'model' => $model->id]) ?>"><span class="glyphicon glyphicon-knight"></span> SEO</a></li>
        <li><a href="<?= Url::to(['/admin/products/photos/edit', 'model' => $model->id]) ?>"><span class="glyphicon glyphicon-camera"></span> Фотографии</a></li>
    <?php endif; ?>
</ul>

<br>
