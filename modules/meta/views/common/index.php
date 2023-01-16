<?php

use yii\helpers\Url;
use yii\bootstrap\BootstrapPluginAsset;

BootstrapPluginAsset::register($this);

$this->title = "META";

$module = $this->context->module->id;

function renderCategoryRow($category, $level = 0, $module){

    $level++;

    $childrens = $category->childrens;

    ?>

    <tr>
        <td width="50" ><?= $category->id ?></td>
        <td style="padding-left: <?= 20*$level ?>px">
            <a href="<?= Url::to(['/admin/'.$module.'/categories/edit', 'id' => $category->id]) ?>">
            <?php if( !empty($childrens) ): ?><i class="caret"></i><?php endif; ?>
            <?= $category->caption ?>
            </a>
        </td>
        <td width="120" class="text-right">
            <div class="dropdown actions">
                <i id="dropdownMenu<?= $category->id ?>" data-toggle="dropdown" title="Действия" class="glyphicon glyphicon-menu-hamburger"></i>
                <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu<?= $category->id ?>">
                    <li><a href="<?= Url::to(['/admin/'.$module.'/categories/edit', 'id' => $category->id]) ?>"><i class="glyphicon glyphicon-pencil font-12"></i> Редактировать</a></li>
                    <li><a href="<?= Url::to(['/admin/'.$module.'/categories/create', 'id' => $category->id]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> Добавить подкатегорию</a></li>
                    <li><a href="<?= Url::to(['/admin/products/models/create_cat', 'cat_id' => $category->id]) ?>"><i class="glyphicon glyphicon-plus font-12"></i> Добавить товар в категорию</a></li>
                    <li><a href="<?= Url::to(['/admin/'.$module.'/categories/delete', 'id' => $category->id]) ?>" style="color: #a60000"><i class="glyphicon  glyphicon-remove font-12"></i> Удалить</a></li>
                </ul>
            </div>
        </td>
    </tr>

    <?php

    if(!empty($childrens))
    {
        foreach($childrens as $child)
            renderCategoryRow($child, $level, $module);
    }
}

?>

<?= $this->render('_menu') ?>

<table class="table table-hover">
    <tbody>
    <?php foreach($categories as $category) {
        $parent = $category->parent;
        if (empty($parent)) {
            renderCategoryRow($category, 0, $module);
        }
    } ?>
    </tbody>
</table>
