<?php

use yii\helpers\Url;

$this->title = "Статусы заказов";

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
                <td> <a href="<?= Url::to(['edit', 'id' => $item->primaryKey ]) ?>"><?= $item->name ?></a></td>
                <td class="lidrag_del" width="20"><?php if($item->primaryKey != 10){?><a
                            href="<?= Url::to(['delete', 'id' => $item->primaryKey ]) ?>"><span
				class="glyphicon glyphicon-trash"></span></a><?php }?></td>
                <td class="hidden_items">
                    <input class="list_drag_ul_id" type="hidden" value="<?= $item->primaryKey ?>">
                    <input class="list_drag_ul_order" type="hidden" value="<?= $item->order ?>">
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



    <script>
        function funcsortdrag() {
            jsonstring = '';
            $('#list_drag_ul tr').each(function(){
                stindex = $(this).index();
                if($('.pagination').length){
                    pagindex = $('.pagination li.active a').text();
                    pagindex = parseInt(20 * parseInt(pagindex) - 20);
                    stindex = parseInt(stindex) + pagindex;
                }
                $(this).find('.list_drag_ul_order').val(stindex);

                stid = $(this).find('.list_drag_ul_id').val();
                stor = $(this).find('.list_drag_ul_order').val();

                jsonstring=jsonstring+'"'+stindex+'":{"id":"'+stid+'","order":"'+stor+'"},';


            })
            jsonstring = jsonstring.substring(0, jsonstring.length - 1);

            lsetstring = '{'+jsonstring+'}';
            $.ajax({
                type: "POST",
                url: "/admin/settings/status/savedrag",
                data: 'data='+lsetstring
            });
        }
    </script>
<?php endif; ?>