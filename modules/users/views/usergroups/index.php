<?php

use yii\helpers\Url;
$this->title = 'Группы';
$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>


<table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Название группы</th>
            <th>Количество пользователей</th>
            <th>Скидака группы</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td><a href="<?= Url::to(['/admin/'.$module.'/usergroups/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/usergroups/edit', 'id' => $item->primaryKey]) ?>"><?= $item->getUsers()->count() ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/usergroups/edit', 'id' => $item->primaryKey]) ?>"><?= $item->discount ?>%</a></td>
				<td class="lidrag_del" width="20"><?php if($item->primaryKey != 10){?><?php if($item->primaryKey != 49){ ?><a
                            href="<?= Url::to(['/admin/'.$module.'/usergroups/delete', 'id' => $item->primaryKey]) ?>"><span
				class="glyphicon glyphicon-trash"></span></a><?php }?><?php }?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
	
	
	

    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
