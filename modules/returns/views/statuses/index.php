<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if ($data->count > 0) : ?>


    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Название</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>

                <td> <a href="<?= Url::to(['/admin/' . $module . '/statuses/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>

                <td class="lidrag_del" width="20">
                    <?php if($item->primaryKey != 1){?>
            <a
                            href="<?= Url::to(['/admin/' . $module . '/statuses/delete', 'id' => $item->primaryKey]) ?>"><span
                                class="glyphicon glyphicon-trash"></span></a>
            <?php }?> </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
