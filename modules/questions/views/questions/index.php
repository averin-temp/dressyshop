<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$module = $this->context->module->id;
?>

<?= $this->render('menu') ?>

<?php if($data->count) : ?>

	<table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Дата</th>
            <th>Имя</th>
            <th>Артикул</th>
            <!--<th>Фото</th>-->
            <th>Вопрос</th>
            <th>Ответ</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
			    <td width="180"><?= $item->answer == '' ? '<span style="margin-right:10px;top: -2px;    position: relative;" class="label label-warning">NEW</span>' : "" ?> <a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->date; ?></a></td>
			    <td width="200"><a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->name; ?></a></td>
				<td width="180"><a target="_blank" href="<?= Url::to(['../../catalog/'. $product = \app\models\Product::find()->where(['model_id' => $item->model->id])->One()->id]) ?>"><?= $item->model->vendorcode ?></a></td>
				<!--<td><a href="<?= $item->model->adminLink ?>">
                        <?php $images = $item->model->images; if(!empty($images)) : ?><img src="<?= $images[0]->small ?>" style="max-width:100px; max-height: 100px;" alt=""> <?php endif; ?>
                    </a></td>-->
                <td><a class="heighenormal" href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->content ?></a></td>
				<td><a class="heighenormal" href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->answer != '' ? $item->answer : "Не отвечен" ?></a></td>			
			
				<td class="lidrag_del" width="20"><a
                            href="<?= Url::to(['/admin/'.$module.'/questions/delete', 'id' => $item->primaryKey]) ?>"><span
				class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
			
        <?php endforeach; ?>
		    <?= LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
	<tr><td class="sepors"></td></tr>
		</tbody>
	</table>
	
<?php endif; ?>

<?php if($data2->count) : ?>
<table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Дата</th>
            <th>Имя</th>
            <th>Артикул</th>
            <!--<th>Фото</th>-->
            <th>Вопрос</th>
            <th>Ответ</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">
		
		<?php foreach ($data2->models as $item) : ?>

            <tr>
			    <td width="180"><?= $item->answer == '' ? '<span style="margin-right:10px;top: -2px;    position: relative;" class="label label-warning">NEW</span>' : "" ?> <a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->date; ?></a></td>
			    <td width="200"><a href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->name; ?></a></td>
				<td width="180"><a target="_blank" href="<?= Url::to(['../../catalog/'. $product = \app\models\Product::find()->where(['model_id' => $item->model->id])->One()->id]) ?>"><?= $item->model->vendorcode ?></a></td>
				<!--<td><a href="<?= $item->model->adminLink ?>">
                        <?php $images = $item->model->images; if(!empty($images)) : ?><img src="<?= $images[0]->small ?>" style="max-width:100px; max-height: 100px;" alt=""> <?php endif; ?>
                    </a></td>-->
                <td><a class="heighenormal" href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->content ?></a></td>
				<td><a class="heighenormal" href="<?= Url::to(['/admin/'.$module.'/questions/view', 'id' => $item->primaryKey]) ?>"><?= $item->answer != '' ? $item->answer : "Не отвечен" ?></a></td>			
			
				<td class="lidrag_del" width="20"><a
                            href="<?= Url::to(['/admin/'.$module.'/questions/delete', 'id' => $item->primaryKey]) ?>"><span
				class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
	

	

    <?= LinkPager::widget([
        'pagination' => $data2->pagination
    ]) ?>
<?php endif; ?>
