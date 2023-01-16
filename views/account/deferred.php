<?php
use app\widgets\Thumbnail;

?>
<a href="##" class="defferallremove <?php if($products){echo "active";}?>">Удалить все товары</a>
<div class="deffernone <?php if(!$products){echo "active";}?>">
    Отложенные товары отсутствуют.<br>Перейдите в <a href='/latest'>каталог</a>.
</div>
<div class="products_container clearfix">
	<?php foreach($products as $item): ?>
	<div class="min-md">
	<?= Thumbnail::widget(['product' => $item]); ?>
	</div>
	<?php endforeach; ?>
</div>