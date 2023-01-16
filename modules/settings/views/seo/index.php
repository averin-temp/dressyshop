<?php

use yii\helpers\Url;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>


    <table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Название</th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td> <a href="<?= Url::to(['/admin/' . $module . '/seo/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
