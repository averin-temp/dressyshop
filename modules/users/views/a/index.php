<?php

use yii\helpers\Url;
$this->title = 'Пользователи';
$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count > 0) : ?>
<table width="100%">
        <thead class="list_drag">
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Группа</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td><?= $item->primaryKey ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>"><?= empty($item->email) ? 'не указан' : $item->email ?></a></td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>"><?php $group = $item->group; if(empty($group)): echo 'Отсутствует'; else: echo $group->name; endif; ?></a></td>
				<td class="lidrag_del" width="20"><?php if($item->primaryKey != 10){?><?php if($item->primaryKey != 49){ ?><a
                            href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>"><span
				class="glyphicon glyphicon-trash"></span></a><?php }?><?php }?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
	
	

    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php endif; ?>
