<?php

use yii\helpers\Url;

?>
<?php foreach ($products as $key => $product): ?>
    <div data-product-id="<?= $product->id ?>" data-product-key="<?= $key ?>"
         class="popup_cart_cart_inner_item clearfix">
        <a href="/<?= $product->model->slug ?>" class="popup_cart_cart_inner_item_photo"
           style="background-image: url(<?= $product->image->small ?>);"></a>
        <div class="popup_cart_cart_inner_item_info">
            <div class="table">
                <div class="table_cell">
                    <div class="popup_cart_cart_inner_item_info_top clearfix">
                        <div class='cart_page_in_type'>
                            <div class="table">
                                <div class="table_cell"> <?= $product->type ?> </div>
                            </div>
                        </div>
                        <div class='cart_page_in_name'>
                            <div class="table">
                                <div class="table_cell"> <?= $product->model->name ?></div>
                            </div>
                        </div>
                        <div class='cart_page_in_ven'>
                            <div class="table">
                                <div class="table_cell"><?= $product->model->vendorcode ?></div>
                            </div>
                        </div>
                        <div class='cart_page_in_size'>
                            <div class="table">
                                <div class="table_cell">
                                    <?php if ($size = $product->size): ?>
                                        <span>Размер: <?= $size->name ?></span>
                                    <?php else: ?>
                                        Без размера
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="popup_cart_cart_inner_item_count">
            <div class="popup_cart_cart_inner_item_count_center">
                <div class="prod_body_top_left_top prod_form_item">
                    <div class="table">
                        <div class="table_cell">
                            <?php if ($product->model->colors[0]->attributes['code'] != '#null') { ?>
                                <div class="color_picker">
                                    <select>
                                        <?php foreach ($product->colors as $color): ?>
                                            <?php $color_class = mb_substr($color->code, 1);
                                            $color_class = 'c_' . $color_class; ?>
                                            <option class="<?= $color_class ?>" value="<?= $color->id ?>"
                                                    data-class="avatar"
                                                    data-style="background-color: <?= $color->code ?>" <?= $color->id == $product->color_id ? 'selected' : '' ?> ><?= $color->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup_cart_cart_inner_item_price">
            <div class="popup_cart_cart_inner_item_info_bot">
                <div class="table">
                    <div class="table_cell">
                        <span><?= $product->model->price ?> </span> руб.
                    </div>
                </div>
            </div>
        </div>
        <div class="popup_cart_cart_inner_item_remove">
            <div class='table'>
                <div class="table_cell">
                    <ul class="cd">
                        <li>Вы уверены?</li>
                        <li><a href="##">ДА</a></li>
                        <li class="cd_close"><span>НЕТ</span></li>
                    </ul>
                    <img src="<?= Url::to('@web/') ?>img/icons/remove.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>