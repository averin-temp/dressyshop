<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\grid\GridView;

$this->title = 'Страницы';
$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table width="100%">
        <thead class="list_drag">
            <tr>
                <th>Название</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="list_drag_ul">
        <?php foreach ($data->models as $item) : ?>
            <tr>
                <td>
                    <a href="<?= Url::to(['/admin/' . $module . '/page/update', 'id' => $item->primaryKey]) ?>"><?= $item->caption ?></a>
                </td>
                <td class="lidrag_del" width="20">
                    <a
                            href="<?= Url::to(['/admin/' . $module . '/page/delete', 'id' => $item->primaryKey]) ?>"><span
                                class="glyphicon glyphicon-trash"></span></a>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


<?php endif; ?>

