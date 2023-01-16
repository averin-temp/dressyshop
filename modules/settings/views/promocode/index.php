<?php

use yii\helpers\Url;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>Название</th>
            <th>Скидка</th>
            <th>Удалить</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $item) : ?>
            <tr>
                <td><?= $item->primaryKey ?></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/promocode/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/promocode/edit', 'id' => $item->primaryKey]) ?>"><?= $item->discount ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/promocode/delete', 'id' => $item->primaryKey]) ?>">X</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

