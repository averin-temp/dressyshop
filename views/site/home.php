<?php

use app\widgets\LatestWidget;
use app\modules\settings\models\Settings;
use app\widgets\HomeBanner;
use app\classes\CatalogUrl;
use yii\helpers\Url;
use app\models\Seo;
if(!Seo::SetSeo(2)) $this->title = "Главная страница";

?>
<main>
    <div class="page mainpage">
        <?= HomeBanner::widget() ?>
        <div class="mainpage_tiles">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <div class="mainpage_tiles_big tile"
                                 style="background-image: url(img/tilebig.jpg);">
                                <a href="<?= CatalogUrl::createPath(55) ?>">
                                            <span class="table">
                                                <span class="table_cell">
                                                    <span>Каталог</span>
                                                    <span>Женской одежды</span>
                                                    <p class="tiles_seen">смотреть</p>
                                                </span>
                                            </span>
                                    <span class="tiles_seen"><span>смотреть</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="clearfix">
                            <div class="mainpage_tiles_middle tile"
                                 style="background-image: url(img/tilemid.jpg);">
                                <a href="<?= CatalogUrl::createPath(67) ?>">
                                            <span class="table">
                                                <span class="table_cell">
                                                    <span>Скидки</span>
                                                    <span>до 20%</span>
                                                    <p class="tiles_seen">смотреть</p>
                                                </span>
                                            </span>
                                    <span class="tiles_seen"><span>смотреть</span></span>
                                </a>
                            </div>
                            <div class="mainpage_tiles_min mfirst tile"
                                 style="background-image: url(img/tilemin1.jpg);">
                                <a href="<?= CatalogUrl::createPath(50) ?>">
                                            <span class="table">
                                                <span class="table_cell">
                                                    <span>Аксессуары</span>
                                                    <p class="tiles_seen">смотреть</p>
                                                </span>
                                            </span>
                                    <span class="tiles_seen"><span>смотреть</span></span>
                                </a>
                            </div>
                            <div class="mainpage_tiles_min mlast tile"
                                 style="background-image: url(img/tilemin2.jpg);">
                                <a href="/page/dostavka_i_oplata">
                                            <span class="table">
                                                <span class="table_cell">
                                                    <span>Доставка</span>
                                                    <span>от 500 р</span>
                                                    <span>Бесплатно</span>
                                                    <p class="tiles_seen">смотреть</p>
                                                </span>
                                            </span>
                                    <span class="tiles_seen"><span>смотреть</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mainpage_infos">
            <div class="container">
                <div class="row">
                    <div class="col-xs-3">
						<a href="/page/dostavka_i_oplata">
                        <div><img src="img/icons/info/1.jpg" alt=""></div>
                        <div>Примерка перед<br>покупкой</div>
						</a>
                    </div>
                    <div class="col-xs-3">
					<a href="/page/dostavka_i_oplata">
                        <div><img src="img/icons/info/2.jpg" alt=""></div>
                        <div>Работаем без<br>предоплаты</div>
						</a>
                    </div>
                    <div class="col-xs-3">
					<a href="/page/tablica_razmerov">
                        <div><img src="img/icons/info/3.jpg" alt=""></div>
                        <div>Размерный ряд<br>от 40 до 74</div>
						</a>
                    </div>
                    <div class="col-xs-3">
					<a href="/latest">
                        <div><img src="img/icons/info/4.jpg" alt=""></div>
                        <div>Новинки<br>ежедневно</div>
						</a>
                    </div>
                </div>
            </div>
        </div>
        <?= LatestWidget::widget() ?>
        <div class="mainpage_desc">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?= Settings::get('homepage_text') ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>