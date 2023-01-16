<?php
use yii\helpers\ArrayHelper;

$sizeRange = $product->model->sizeRange;

$all_sizes = $sizeRange->sizes;
$product_sizes = ArrayHelper::index($product->sizes, 'id');

?>

<?php foreach($all_sizes as $size): ?>
    <li <?= !isset($product_sizes[$size->id]) ? 'class="no-instock" title="На данный момент размера нет в наличии"' : '' ?> data-size-id="<?=$size->id ?>" <?= $product->size_id == $size->id ? '' : '' ?>><span><?= $size->name?></span></li>
<?php endforeach; ?>