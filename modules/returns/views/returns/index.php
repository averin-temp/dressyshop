<?php

$module = $this->context->module->id;
use yii\helpers\Url;
?>

<?= $this->render('menu') ?>
<?php 
//die(var_dump($data));
?>
<?php if ($data->count > 0 || $data2->count > 0) : ?>


    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th>№</th>
            <th>ФИО</th>
            <th>Дата</th>
            <th>№ заказа</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td width="80"><?=$item->primaryKey?> <?= $item->status == 1 ? '<span style="margin-right:10px;top: -2px;    position: relative;" class="label label-warning">NEW</span>' : "" ?></td>
                <td width="100"><?=$item->date?></td>
                <td> <a href="<?= Url::to(['/admin/' . $module . '/returns/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>
                <td> <?= $item->order_number ?></td>
                <td> <?= $item->status_name->name ?></td>
            </tr>
        <?php endforeach; ?>
		<tr><td class="sepors"></td></tr>
		 <?php foreach ($data2->models as $item) : ?>

            <tr>
                <td width="80"><?=$item->primaryKey?> <?= $item->status == 1 ? '<span style="margin-right:10px;top: -2px;    position: relative;" class="label label-warning">NEW</span>' : "" ?></td>
                <td width="100"><?=$item->date?></td>
                <td> <a href="<?= Url::to(['/admin/' . $module . '/returns/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>
                <td> <?= $item->order_number ?></td>
                <td> <?= $item->status_name->name ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>