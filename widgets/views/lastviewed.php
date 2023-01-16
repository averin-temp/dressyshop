<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>

<?php if($products){?>
<div class="catalog_left_last_header">
    Последние просмотренные
</div>
<?php }?>
<div class="catalog_left_last_list">
    <?php foreach ($products as $product): ?>

        <div class="catalog_left_last_list_item clearfix">
            <?php
            if ($product->image->small == '') {
                $prod_img = Url::to('@web/img/no_small.jpg');
            } else {
                $prod_img = $product->image->small;
            }
            ?>
            <div class="catalog_left_last_list_item_left" style="background-image: url('<?= $prod_img ?>') !important">
                <a href="/<?= $product->model->slug ?>"></a>
            </div>
            <div class="catalog_left_last_list_item_right">
                <div class="table">
                    <div class="table_cell">
                        <div class="catalog_left_last_list_item_right_name"><a href="<?= $product->link ?>">
                                <?= $product->type ?> <?= $product->model->vendorcode ?>
                            </a>
                        </div>
                        <!--<div class="catalog_left_last_list_item_right_sizes">
                    <?php /* $sizes = ArrayHelper::getColumn($product->sizes,'name');
                          $sizes = implode(', ', $sizes);
                          echo $sizes; */ ?>
                </div>-->
                        <div class="catalog_left_last_list_item_right_price">
                            <span><?= $product->model->price ?></span> руб.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php endforeach; ?>
</div>
