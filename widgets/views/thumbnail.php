<?php 
use yii\helpers\ArrayHelper;

?>
    <a href="##" class="deffer_close" title="Удалить" data-itemid="<?=$product->id?>" >x</a>
<a href="/<?= $product->model->slug ?>">

    <div class="products_container_outer">
        <div class="products_container_inner">
            <?php if ($badge = $product->badge): ?>
                <div class="badge_ <?= $badge->class ?>"
                     style="background-image: url('<?= \yii\helpers\Url::to('@web/images/badges/') . $badge->image ?>');<?= $badge->css ?>">
                    <div><?= $badge->text ?></div>
                </div>
            <?php endif; ?>
            <div class="product_list_cont">
               <?php
                $images = $product->images;
				//var_dump($images);
				if(null) {
					ArrayHelper::multisort($images, ['order'], [SORT_ASC]);

					if (count($images) > 1 && $images[1]->order == 1) {
						$image1 = $images[0]->medium;
						$image2 = $images[1]->medium;
					} else if (count($images) > 1 && $images[1]->order != 1) {
						//echo count($images);
						foreach ($images as $image) {
							if ($image->primary) {
								$image1 = $image->medium;
							} else {
								$image2 = $image->medium;
							}
						}
					} else {
						$image1 = $product->images[0]->medium;
						$image2 = $image1;
					}


					$data_sq1 = $images[0]->sq_first  == 1 ? 'true' : '' ;
					$data_sq2 = $images[1]->sq_second == 1 ? 'true' : '' ;
					$class    = $images[0]->sq_first  == 1 ? 'square' : '' ;

				}
				else
                {
	                $data_sq1 = 'false';
	                $data_sq2 = 'false';
	                $class = '';
	                $image1 = '';
	                $image2 = '';
                }
				//echo $images[1]->sq_second;
				

				
				
				
				
				
                ?>
                <div data-sq1="<?= $data_sq1 ?>" data-sq2="<?= $data_sq2 ?>" class="<?= $class ?> preload_image_list product_list_cont_img" style="background-image: url('<?= $image1 ?>');"
                     data-first_img="<?= $image1 ?>" data-second_img="<?=$image2?>">
				</div>
               <div class="underthumbcont">
                   <div class="table">
                       <div class="table_cell">
                           <div class="product_list_cont_name">
                               <?= $product->type ?> <?= $product->model->vendorcode ?>
                           </div>

                           <div class="product_list_cont_oldprice">
                               <?php if ($product->model->discount): ?>
                                   <span><?= $product->model->final_price ?> руб.</span> (-<?= $product->model->discount ?>%)
                               <?php endif ?>
                           </div>
                           <div class="product_list_cont_price"><?= $product->model->price ?> руб.</div>
                       </div>
                   </div>

               </div>

                <div class="product_list_cont_size">
					<?php
					$product_sizes = ArrayHelper::index($product->sizes, 'id');
					?>

							


			




                    <?= count($product->model->sizeRange->sizes) ? 'Размер:' : '' ?>
                    <div class="product_list_cont_size_inner">
                        <ul>
                            <?php if ($product->model->sizes) { ?>
                                <?php $avalibleSizes = \yii\helpers\ArrayHelper::index($product->model->sizes, 'id');
                                $first = true;
                                $select = '';
                                foreach ($product->model->sizeRange->sizes as $size): ?>
									<li data-size-id="<?= $size->id ?>"
										<?php if(!isset($product_sizes[$size->id])) {echo 'title="На данный момент размера нет в наличии"';}
										else { ?>class="active <?php if($first){echo 'selected'; $first=false;}?>"<?php } ?>>
										<span><?= $size->name?></span>
									</li>
                                <?php endforeach; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php if ($product->model->colors[0]['name'] != 'Как на фото') { ?>
                        <div class="product_list_cont_color">
                            Цвета:
                            <div class="product_list_cont_color_inner">
                                <?php
                                foreach ($product->model->colors as $color) {
                                    $border_color = '';
                                    $bgmulti = '';
                                    if ($color['name'] == 'Мультиколор') {
                                        $bgmulti = 'background: url(/web/img/multi.jpg) no-repeat center center;background-size: contain;';
                                    }
                                    if($color['name'] == 'Черно-белый'){
                                        $bgmulti = 'background: url(/web/img/bw.png) no-repeat center center;background-size: contain;';
                                    }
                                    if($color['name'] == 'Черно-серый'){
                                        $bgmulti = 'background: url(/web/img/bg.png) no-repeat center center;background-size: contain;';
                                    }
                                    if ($color['code'] == '#ffffff') {
                                        $border_color = 'border: 1px solid #ccc;';
                                    }
                                    echo '<div class="thumb_color" title="' . $color['name'] . '" style="background-color:' . $color['code'] . ';' . $border_color . '' . $bgmulti . '"></div>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="product_list_cont_button">
                    <a data-product="<?= $product->id ?>" href="##" class="incart_list button">в корзину</a>
                </div>
            </div>
        </div>
    </div>
</a>

<?php

$script = <<< JS


$('.product_list_cont_size_inner ul li').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    return false;
});

$('.product_list_cont_size_inner ul li.active').click(function () {
    $(this).parent('ul').children('li.active').removeClass('selected')
    $(this).addClass('selected');
})



JS;
$this->registerJS($script, yii\web\View::POS_READY, 'thumbnail');