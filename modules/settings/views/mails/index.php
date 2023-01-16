<?php

use yii\helpers\Url;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>


    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th></th>
            <th>Название</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td class="lidrag_sort" width="20"><span class="glyphicon glyphicon-sort"></span></td>
                <td> <a href="<?= Url::to(['/admin/' . $module . '/mails/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>

                <td class="lidrag_del" width="20"><a
                            href="<?= Url::to(['/admin/' . $module . '/mails/delete', 'id' => $item->primaryKey]) ?>"><span
                                class="glyphicon glyphicon-trash"></span></a></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
