<?php

use yii\helpers\Url;
use app\classes\CatalogUrl;
use yii\helpers\ArrayHelper;

?>
<div class="catalog_left_menu">
    <div class="catalog_left_menu_header"><?= ($label === null) ? "Каталог товаров" : $label ?></div>
    <div class="catalog_left_menu_list">
        <ul>
            <!--<?php if($label === null): ?><li><a href="<?= Url::to(['catalog/index']) ?>">Вся одежда</a></li><?php endif; ?>-->
            <?php

            $temp = [];
            $priory = [];
            foreach ($list as $category)
            {
                if(empty($category['order']))
                    $temp[] = $category;
                else
                    $priory[] = $category;
            }
            ArrayHelper::multisort($temp, ['caption']);
            ArrayHelper::multisort($priory, ['order','caption']);
            $list = array_merge($priory, $temp);


            ?>
            <?php foreach($list as $item): ?>
			<?php //if($item['c_count'] == 0){continue;}?>	
            <li class="<?= $active && $active == $item['id'] ? 'active':''; ?><?= !empty($item['order']) ? ' priory' : ''; ?>">
               <a href="<?= CatalogUrl::createPath($item['id']) ?>"><?= $item['caption'] ?> (<?= $item['c_count'] ?>)</a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>