<?php

use yii\helpers\Url;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>
<h3>Автоматические</h3>
<?php if($auto->count > 0) : ?>



    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Название</th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($auto->models as $item) : ?>

            <tr>
                <td>   <a href="<?= Url::to(['/admin/' . $module . '/autobadge/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


<?php endif; ?>

<h3>Пользовательские</h3>
<?php if($custom->count > 0) : ?>





    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th></th>
            <th>Название</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($custom->models as $item) : ?>

            <tr>
                <td class="lidrag_sort" width="20"><span class="glyphicon glyphicon-sort"></span></td>
                <td> <a href="<?= Url::to(['/admin/' . $module . '/badges/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>

                <td class="lidrag_del" width="20"><a
                            href="<?= Url::to(['/admin/' . $module . '/badges/delete', 'id' => $item->primaryKey]) ?>"><span
                                class="glyphicon glyphicon-trash"></span></a></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



<br>


    <?= yii\widgets\LinkPager::widget([
        'pagination' => $custom->pagination
    ]) ?>
<?php endif; ?>


    <?= \yii\bootstrap\Html::a("Создать бейдж", Url::to(['create']), [ 'class' => 'btn btn-success'  ]) ?>
