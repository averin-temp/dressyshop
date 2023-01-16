<?php
use yii\helpers\Url;
?>
<form action="<?=Url::to(['catalog/index']) ?>" method="post">
    <button type="submit">Показать все товары</button>
</form>
<form action="<?=Url::to(['catalog/index']) ?>" method="post">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
    <?php if($filters['category']): ?><input type="hidden" name="filters[category]" value="<?=$filters['category']->id ?>"><?php endif; ?>
    <select name="filters[color]" id="filter_color">
        <option value="0">Любой</option>
        <?php foreach($filters['color'] as $color): ?>
        <option data-color-code="<?=$color->code ?>" value="<?=$color->id ?>" <?=(isset($filters['active']['color']) && ($color->id == $filters['active']['color']))?'selected':'' ?> ><?=$color->name ?></option>
        <?php endforeach; ?>
    </select>
    <select name="filters[size]" id="filter_size">
        <option value="0">Любой</option>
        <?php foreach($filters['size'] as $size): ?>
        <option value="<?=$size->id ?>" <?=(isset($filters['active']['size']) && ($size->id == $filters['active']['size']))?'selected':'' ?> ><?=$size->europe ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Найти</button>
</form>
