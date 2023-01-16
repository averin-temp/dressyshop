<?php

use app\widgets\CategoriesList;
use app\widgets\Thumbnail;
use app\widgets\Paginator;
use app\widgets\CatalogFilters;
use app\widgets\Breads;
use app\models\Category;
use app\widgets\Brands;
use yii\helpers\Url;
use app\widgets\LastViewedWidget;
use app\widgets\BannerWidget;
use app\widgets\CatalogSort;
use app\models\Seo;

$this->title = $name." - DressyShop";

?>
<main>
    <div class="page catalog">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="catalog_left">
                        <?= CategoriesList::widget() ?>
                        <?= BannerWidget::widget() ?>
                        <div class="catalog_left_last">
                            <?= LastViewedWidget::widget() ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?=Breads::widget([
                        'path' => [ 'Новые поступления' => Url::to(['latest']) ],
                        'home' => Url::to(['catalog/index'])
                    ]) ?>
					<div class="catalog_header_h1 clearfix">
						<h1><?=$name?> <span>Товаров: <?= $resultsCount ?></span></h1>
						<?=Paginator::widget([
							'pagination' => $pagination,
							"prevPageLabel" => '...',
							"nextPageLabel" => '...',
							'resultsCount' => false,
							'options' => []
						]) ?>
					</div>
<?php 
//die(var_dump($avalibleProperties));
?>
                    <div class="catalog_filters">
                        <?=CatalogFilters::widget(['filters' => [] ]) ?>
                    </div>
					<div class="catalog_header_sort"><?= CatalogSort::widget() ?></div>
                    <div class="products_container clearfix">
                        <?php foreach($items as $item): ?>
                            <div class="min-md">
                                <?= Thumbnail::widget(['product' => $item]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?=Paginator::widget([
                        'pagination' => $pagination,
                        "prevPageLabel" => '...',
                        "nextPageLabel" => '...',
                        "lastPageLabel" => true,
                        "firstPageLabel" => true,
                        'resultsCount' => $resultsCount,
                        'options' => []
                    ]) ?>

                    <?= Brands::widget() ?>

                </div>
            </div>
        </div>
    </div>
    </div>
</main>
