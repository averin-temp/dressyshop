<?php

use yii\helpers\Url;

?>

<div class="catalog_left_banner">
    <?php foreach($banners as $banner): ?>
        <a href="<?= $banner->url ?>"><img src="<?= $banner->image ?>" alt="image"></a>
    <?php endforeach; ?>
</div>
