<?php 
use yii\helpers\ArrayHelper;
$images = $product->images;
ArrayHelper::multisort($images, ['order'], [SORT_ASC]);
if(count($images)>1 && $images[1]->order == 1){?>
	<?php foreach ($images as $key=>$image): ?>
		<img src="<?=$image->small?>" alt=""  class="product_body_left_mins_item<?=($key == 0)?' active':'' ?>" data-large="<?=$image->noscaled ?>" data-normal="<?=$image->normal ?>" >
	<?php endforeach; ?>
<?php }
else{ 
ArrayHelper::multisort($images, ['primary'], [SORT_ASC]); ?>
<?php foreach ($images as $image): ?>
    <img src="<?=$image->small?>" alt=""  class="product_body_left_mins_item<?=($image->primary == 1)?' active':'' ?>" data-large="<?=$image->noscaled ?>" data-normal="<?=$image->normal ?>" >
<?php endforeach; ?>	
<?php }
?>

