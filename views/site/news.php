<?php

use app\widgets\Thumbnail;

?><div class="mainpage_news">

    <h2>Новинки</h2>
    <div class="products_container">
        <div class="container">
            <div class="row">

                <?php foreach ($products as $product): ?>
                <div class="col-sm-6 col-md-3">
                    <?= Thumbnail::widget(["product" => $product]) ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <a href="##" class="standart_button news_button">Все новинки</a>

</div>
