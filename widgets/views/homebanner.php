<?php


?>

<div class="mainpage_banner_block <?= $banner->class ?>" style="background-image: url(<?= $banner->image ?>)">
	<?php if($banner->url){ ?><a href="<?=$banner->url?>"></a><?php }?>
    <?php if($banner->enable_parallax): ?><div class="prlx prlx1" data-speed="5" style="background-image: url(<?= $banner->parallax_image ?>); "></div><?php endif; ?>
</div>
<?php

$script = <<< JS

JS;
