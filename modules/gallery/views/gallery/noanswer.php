<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>Содержание</th>
            <th width="130">Дата</th>
            <th width="60"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $item) : ?>
            <tr>
                <td><?= $item->primaryKey ?></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->content ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->date ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/questions/delete', 'id' => $item->primaryKey]) ?>" class="glyphicon glyphicon-remove"></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
