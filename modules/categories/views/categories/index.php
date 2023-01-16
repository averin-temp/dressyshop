<?php

use yii\helpers\Url;
use yii\bootstrap\BootstrapPluginAsset;

BootstrapPluginAsset::register($this);

$this->title = "Категории";

$module = $this->context->module->id;

?>

<?= $this->render('_menu') ?>

    <table width="100%">

        <tbody id="list_drag_ul">

        <?php foreach ($tree as $category) {
            renderCategoryRow($category, 0, $module);
        } ?>
        </tbody>
    </table>
<?php

function renderCategoryRow($category, $level = 0, $module)
{

    $childrens = isset($category['childrens']) ? $category['childrens'] : [];

    ?>


    <tr class="clearfix">
        <td style="padding-left: <?= 30 * $level ?>px"><a href="<?= Url::to(['/admin/' . $module . '/categories/edit', 'id' => $category['id']]) ?>">
            <?php if (!empty($childrens)): ?><i class="caret"></i><?php endif; ?>
            <?= $category['caption'] ?>
        </a></td>
        <td class="lidrag_del" width="20"><a class="lidrag_del"
           href="<?= Url::to(['/admin/' . $module . '/categories/delete', 'id' => $category['id']]) ?>"><span
                    class="glyphicon glyphicon-trash"></span></a></td>
    </tr>


    <?php

    if (!empty($childrens)) {
        $level++;
        foreach ($childrens as $child)
            renderCategoryRow($child, $level, $module);
    }
}

