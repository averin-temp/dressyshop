<?php

use yii\helpers\Url;
use app\classes\CatalogUrl;
use yii\helpers\ArrayHelper;

?>

    <div class="header_bot">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul id="main-menu-list">
                        <li data-ajax-action="latest" >
                            <a href="/latest">
                                <span>Новые поступления</span>
                            </a>
                        </li>
                    <?php
                    $temp = [];
                    $priory = [];
                    foreach ($categories as $category)
                    {
                        if(empty($category['order']))
                            $temp[] = $category;
                        else
                            $priory[] = $category;
                    }
                    ArrayHelper::multisort($temp, ['caption']);
                    ArrayHelper::multisort($priory, ['order','caption']);
                    $categories = array_merge($priory, $temp);

                    foreach ($categories as $category): ?>
						<?php if($category['id'] == 476){continue;}?>
                        <li data-ajax-action="<?= $category['slug'] ?>" class="<?= !empty($category['order']) ? ' priory' : ''; ?>">
                            <a href="/catalog/<?= $category['slug'] ?>">
                                <span>
                                    <?= $category['caption'] ?>
                                    <?php if (!$empty = (empty($category['childrens']) || $category['count'] == '0') ) { ?>
                                        <img src="<?= Url::to('@web/img/icons/arb.png') ?>" alt="">
                                    <?php } ?>
                                </span>
                            </a>
                            <?php if (!$empty) { ?>
                            <div class="sub_mainmenu">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="clearfix">
                                                <?php
                                                $childrens = $category['childrens'];
                                                ArrayHelper::multisort($childrens, ['order','caption'], [SORT_DESC, SORT_ASC]);

                                                ?>
                                                <?php foreach ($childrens as $subcategory) { if($subcategory['count'] != '0'){ ?>
                                                    <li class="<?= !empty($subcategory['order']) ? ' priory' : ''; ?>">
                                                        <?php if($subcategory['icon_link']){?><img src="/web/icons/<?=$subcategory['icon_link']?>"/><?php }?><a href="<?= CatalogUrl::createPath($subcategory['id']) ?>"><?= $subcategory['caption'] ?> (<?=$subcategory['count']?>)</a>
                                                    </li>
                                                <?php }} ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php

$script = <<< JS
$('#main-menu-list li').click(function(e){
    return;
    e.preventDefault();
    
    var url = $(this).attr('data-ajax-action');
    
    if(!url) return;
        
    $.post( url, function( data ) {
        $('#menu-content').html(data.content);
        pp_open('popup_menu');
    });
    
});
JS;

$this->registerJS($script);


?>