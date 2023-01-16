<?php

use yii\helpers\Url;
use app\assets\OwlAsset;

OwlAsset::register($this);
?>
<div class="catalog_brands">
    <div class="brands_carusel owl-theme owl-carousel">

        <?php foreach($brands as $brand): ?>
            <a href="<?= Url::toRoute(['catalog/brand', 'slug' => $brand->slug ]) ?>">
<!--            <a href="/brands/--><?//=$brand->id?><!--">-->
                <span class="table">
                    <span class="table_cell">
                        <img src="<?= $brand->image ?>" alt="">
                    </span>
                </span>
            </a>
        <?php endforeach; ?>

    </div>
</div>

<?php
$script = <<< JS
$('.brands_carusel').owlCarousel({
    'items': 5,
    'margin': 10,
    'autoplay': true,
    'autoplayTimeout': 2500,
    'autoplayHoverPause': true
});
JS;

$this->registerJS($script);
