<?php
use yii\helpers\Url;
?>
<div class="breads">
    <ul>
        <li><a href="<?=$home?>"></a></li>
        <?php foreach ($path as $label => $url): ?>
            <li><?php if($url!=''): ?><a href="<?=$url ?>"><?=$label ?></a><?php else: ?><span><?=$label ?></span><?php endif; ?></li>
        <?php endforeach; ?>
        <?= !empty($last) ? "<li>$last</li>" : '' ?>
    </ul>
</div>
