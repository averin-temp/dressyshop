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

$this->title = 'Результаты поиска';
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
                    <?php
					if($_GET['search']){
						$search = $_GET['search'];
					}
					echo Breads::widget([
                        'last' => 'Поиск: '.$search,
                        'home' => Url::to(['catalog/index'])
                    ]) ?>
                    <div class="catalog_header clearfix">
                        <h1>Результаты поиска</h1>
                    </div>
                    <div class="catalog_filters">
					
                    </div>

                    <div class="products_container clearfix">
						<?php 
						if(count($products) == 0){
							echo 'Товаров по Вашему запросу не найдено.';
						}
						?>
                        <?php foreach($products as $product): ?>
                        <div class="min-md">
                        <?= Thumbnail::widget(['product' => $product]); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
</main>
