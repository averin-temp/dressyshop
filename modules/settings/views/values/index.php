<?php

use yii\helpers\Url;
use yii\bootstrap\Html;

$this->title = "Типы свойств";

?>

<?= $this->render('menu', ['model' => $type]) ?>
    <div class="form-inline">
        <div class="form-group">
		<br>
            <?= Html::textInput('', '', ['id' => 'property-value-name', 'placeholder' => 'Введите значение', 'class' => 'form-control', 'style'=>'vertical-align:top']) ?>
            <?= Html::button('Добавить значение', ['class' => 'btn btn-primary', 'id' => 'add-value']) ?>
		<br>
		<br>
        </div>
    </div>
<?php if ($data->count > 0) : ?>
	<table width="100%">
        <thead class="list_drag">
        <tr>
            <th>Название</th>
            <th></th>
        </tr>
        </thead>
        <tbody  id="list_drag_ul">

        <?php foreach ($data->models as $item) : ?>

            <tr>
                <td> <?= $item->name ?></a></td>
                <td class="lidrag_del" width="20"><a
                       href="<?= Url::to(['/admin/' . $module . '/delivery/delete', 'id' => $item->primaryKey]) ?>"><span
                                class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>



<?php
$type = $type->id;
$urlsave = Url::to(['ajaxsave']);
$urldelete = Url::to(['delete']);
$script = <<< JS

 function funcsortdrag() {
            jsonstring = '';
            $('#list_drag_ul li').each(function(){
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
                url: "/admin/settings/values/savedrag",
                data: 'data='+lsetstring
            });
        }
        
$('.list_drag_ul').on('click', '.remove-item', function(e){
    e.preventDefault();
    e.stopPropagation();
    
    var that =  $(this);
    var tr = that.closest('li')
    
    var id = tr.find('[data-id]').attr('data-id');
    
    $.post( "$urldelete" , { id: id }, function(res){
        if(res instanceof Object)
        {
            if(res.error === true)
            {
                alert(res.message);
            } else {
                tr.remove();
            }    
        } 
        else alert(res);
    })
})


$('#add-value').click(function(){
    var value = $('#property-value-name').val();
    value.trim();
    
    if(value == '') return;
    
    
    if($('[data-value="'+value+'"]').length){
        alert('такое значение уже есть');
        return;
    }
    
    $.post("$urlsave", { name: value, type: $type  },  function(result){
        if(result instanceof Object)
        {
            if(result.error === true)
            {
                alert(result.message);
            }
            else{
                var id = result.id;
                var value = result.name;
                $('#value-table tbody').append('<tr><td data-id="'+id+'" data-value="'+value+'">'+value+'</td><td><div  class="remove-item glyphicon glyphicon-trash" href="/index.php/admin/settings/values/delete/'+id+'" ></div></td></tr>');
            }
        }
        else
        {
            alert(result);        
        }
    });
});
JS;
$this->registerJS($script);