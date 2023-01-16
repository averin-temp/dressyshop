<?php

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use app\assets\PhotosAsset;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

PhotosAsset::register($this);

$this->title = "Фотографии";

/**
 * Собирает все картинки и размеры в один массив:
 * [ 'ID_цвета' =>
 *      [
 *          'color' => {Color},
 *          'images' => [{Image}, {Image}, ...],
 *          'sizes' => [ size_ID1, size_ID12, ... ]
 *      ]
 * ]
 */
$indexedColors = [];
foreach($model->colors as $color)
    $indexedColors[$color->id]['color'] = $color;
if(count($indexedColors)) {
    foreach ($model->images as $image)
        $indexedColors[$image->color_id]['images'][] = $image;
    foreach ($model->products as $product) {
        if($product->size_id)
            $indexedColors[$product->color_id]['sizes'][] = $product->size_id;
    }

}
if($sizeRange = $model->sizeRange){
    $sizes = $model->sizeRange->sizes;
} else $sizes = [];

?>

<?= $this->render('_menu', ['model' => $model]) ?>

<div class="form-inline" style="margin: 10px 0;">
    <div class="form-group">

        <select name="colors" class="form-control">
            <option value="">Выберите цвет</option>
            <?php foreach($colors as $colorItem): ?>
            <option value="<?= $colorItem->id ?>" data-code="<?= $colorItem->code ?>" ><?= $colorItem->name ?></option>
            <?php endforeach; ?>
        </select>
        <?= Html::button('Добавить цвет', [ 'class' => 'btn btn-warning', 'id' => 'add-color' , 'disabled' => true ]) ?>
    </div>
</div>
<div class="alert alert-success can-drug" style="display:  none">Ваш браузер поддерживает перенос изображений мышью</div>
<section id="photos-section">

    <?php foreach($indexedColors as $item): ?>

        <div class="color-section row" data-color="<?= $item['color']->id ?>">
            <div class="col-md-2 colorriw">


                <div class="row">
                    <div class="col-sm-12">


                        <div class="form-group frggg">
                            <span class="color-label">Цвет: <?= $item['color']->name ?></span>
                            <label class="file-input btn btn-success">
                                <input type="file" multiple>
                                <span>Загрузить фото</span>
                            </label>
                        </div>

<div class="color-preview" style="background-color: <?= $item['color']->code ?>"></div>
                    </div>

                </div>




            </div>
            <div class="col-md-9">
                <div class="photos clearfix phohoh">
                    <?php if(isset($item['images'])): ?> <?php
					ArrayHelper::multisort($item['images'], ['order'], [SORT_ASC]);
					foreach($item['images'] as $image): ?>
                    <div data-sq_second="<?= $image->sq_second ?>" data-sq_first="<?= $image->sq_first ?>" data-order="<?= $image->order ?>" data-id="<?= $image->id ?>"  data-primary="<?= $image->primary ?>" data-original="<?= $image->source ?>">
                        <img src="<?= $image->small ?>" alt="small preview">
                        <span class="glyphicon glyphicon-remove delete-button"></span>
                    </div>
                    <?php endforeach; endif;?>
                </div>

                <p>Размеры:</p>
                <div class="sizes form-inline">
                    <?php 
					$arr = [18,19,20];
					$arr2 = (array)$item['sizes'];
					foreach($sizes as $size): ?>
                        <div class="checkbox">
						<label>
							<input type="checkbox" value="<?= $size->id ?>" <?= in_array($size->id, $arr2) ? 'checked' : '' ?>><span><?= $size->name ?></span>
						</label>
						</div>
                    <?php endforeach; ?>
					<span class="sizesall"><span class="sizesall_yes">Выбрать все</span> / <span class="sizesall_no">Снять все</span></span>
                </div>

            </div>
            <div class="col-md-1">
                <div class="btn-group btn-group-sm delcoldr" role="group">
                    <a href="#" class="btn btn-default delete_section_btn" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</section>

<?php ActiveForm::begin([ 'action' => ['save'], 'method' => 'post', 'id' => 'colors-form' , 'enableAjaxValidation' => false]) ?>

<?= Html::hiddenInput('model', $model->id) ?>
<?= Html::hiddenInput('colors', '') ?>

<?= Html::submitButton('Сохранить', [ 'class' => 'btn btn-primary' ]) ?>

<?php ActiveForm::end() ?>

<?= $this->render('editor') ?>

<div class="color-section row" id="template">
    <div class="col-md-2 colorriw">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group frggg">
                    <span class="color-label"></span>
                    <label class="file-input btn btn-success">
                        <input type="file" multiple>
                        <span>Загрузить фото</span>
                    </label>
                </div>
				
				<div class="color-preview" ></div>
            </div>
        </div>

    </div>
    <div class="col-md-9">

        <div class="photos clearfix">
        </div>

        <p>Размеры:</p>

            <div class="sizes form-inline">
                <?php foreach($sizes as $size): ?>
                    <div class="checkbox"><label><input type="checkbox" value="<?= $size->id ?>"><span><?= $size->name ?></span></label></div>
                <?php endforeach; ?>
				<span class="sizesall"><span class="sizesall_yes">Выбрать все</span> / <span class="sizesall_no">Снять все</span></span>
            </div>


    </div>
    <div class="col-md-1">
        <div class="btn-group btn-group-sm delcoldr" role="group">
            <a href="#" class="btn btn-default delete_section_btn" title="Удалить"><span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
</div>


<?php
$image_upload_url = Url::to(['upload']);
$image_set_primary_url = Url::to(['set_primary']);
$image_clip_url = Url::to(['clip']);

$script = <<< JS
$( ".phohoh" ).sortable({
	axis: "x"
});
$( ".phohoh" ).disableSelection();
function Editor()
{
    this.elem = $('#modal_box');
    this.image = null;
    this.img = null;
    this.jcrop = null;
    this.box = $('.edit-box');
    
    this.openImage = function(image)
    {
        var that = this;
        this.reset();
        this.image = image;
        $(this.box).html('');
        this.img = $('<img>').get(0);
        this.img.src = image.getOriginal();
        
        $(this.box).append( this.img );
        
        this.elem.find('[name=primary]').prop('checked', this.image.getPrimary() == '1' );
            
        
        $(this.img).Jcrop({
            boxWidth: 400,
            boxHeight: 400,
        }, function(){
            that.jcrop = this;
        });
        
        this.elem.modal('show');
    };
        
    this.getSelect = function()
    {
        var select = this.jcrop.tellSelect();

        if(select.w == 0 || select.h == 0)
            return null;

        return JSON.stringify(select);
    };        
    
    this.save = function()
    {
        this.image.selection = this.getSelect();
        
        if(this.image.selection)
            this.image.clip();
        
        if($(this.elem).find('input[name=primary]').is(':checked')) {
            
            $(this.image.elem)
                .closest('.photos')
                .find('[data-primary="1"]')
                .attr('data-primary', 0);
            
            this.image.setPrimary(1);
            
        }
        
        this.close();

       
    };
    
    this.close = function(){
        this.elem.modal('hide');
        this.reset();
    };
    
    
    this.reset = function()
    {
        this.image = null;
        this.jcrop = null;
        this.img = null;
        $('#box').html();
    };
}

var editor = new Editor();

function ImageItem(that)
{
    this.file = null;
    this.original = null;
    this.selection = null;
    
    if(that){
        this.elem = that;   
    } else {
    this.elem = $('<div data-id=""  data-primary=""  data-original=""><img src="" alt="small preview"><i class="loading"></i><span class="glyphicon glyphicon-remove delete-button"></span></div>').get(0);
    }
                    
    this.img = $(this.elem).find('img').get(0);
    
    this.upload = function()
    {
        var that = this;
        var color = $(this.elem).closest('.color-section').attr('data-color');
        var model = $('#colors-form input[name=model]').val();
        
        
        var formData = new FormData();
        formData.append('image', this.file );
        formData.append('model_id', model );
        formData.append('color_id', color );
        console.log(this.elem.dataset['primary'])
        formData.append('primary', this.elem.dataset['primary']);
        this.loading(true);

        $.ajax({
            url: '$image_upload_url',
            type: 'post',
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false
        }).done(function (result) {
            if(result.ok)
            {
                that.img.src = result.src;
                that.file = null;
                $(that.elem).attr('data-original', result.source );
                $(that.elem).attr('data-id', result.id );
                
                that.loading(false);
            }
            else
            {
                console.log(result);
            }
        });
    };
    
    this.getOriginal = function()
    {
        return $(this.elem).attr('data-original');   
    };

    this.loading = function(flag)
    {
        if(!flag)
        {
            $(this.img).css('display', 'block'); 
        }
        else
        {
            $(this.img).css('display', 'none');
        }    
    }
    
    
    this.elem.__api = this;
    
    this.remove = function()
    {
        $(this.elem).remove();   
    };
    
    this.getPrimary = function()
    {
        return $(this.elem).attr('data-primary');  
    };
    
    this.setPrimary = function(a)
    {
        $(this.elem).attr('data-primary', a);  
    };
    
    this.getID = function()
    {
        return $(this.elem).attr('data-id');  
    };
    
    this.clip = function()
    {
        var that = this;
        var formData = new FormData();
        formData.append('id', this.getID() );
        formData.append('selection', this.selection);
        
        this.loading(true);

        $.ajax({
            url: '$image_clip_url',
            type: 'post',
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false
        }).done(function (result) {
            if(result.ok)
            {
                that.img.src = result.src + '?' + Math.random();
                that.loading(false);
            }
            else
            {
                console.log(result);
            }
        });
    }
}

// Загрузка картинок
$('#photos-section').on('change', 'input[type=file]', function(){
    var image, files = this.files;
    var section = $(this).closest('.color-section');
    
    for(var i = 0; i < files.length ; i++ ) {
        addImageToSection(files[i], section);
    }
});


function addImageToSection(file, section) {
    // TODO: проверить тип файла
        image = new ImageItem();
        image.file = file;
        image.setPrimary( section.find('[data-primary="1"]').length ? 0 : 1 );
        section.find('.photos').append(image.elem);
        image.upload();
}

// клик по картинке
//$('#photos-section').on('click', '.color-section div[data-id]', function(){
//    editor.openImage(this.__api);
//});

// Отправка формы
$('#colors-form').on('click','.btn.btn-primary',function(e){
    e.stopPropagation();
    e.preventDefault();
	
	
	$('.color-section').each(function(){
		$(this).find('.photos').children('div').each(function(){
			$(this).attr('data-order',$(this).index())
		})
	})
	
	
    form = $('#colors-form');
    var colors = [];
    $('#photos-section .color-section').each(function(){
        var color = { id: $(this).attr('data-color') , primary: $(this).find('[data-primary="1"]').attr('data-id') };
        color.images = [];
        color.sizes = [];
        $(this).find('div[data-id]').each(function(){
            color.images.push( $(this).attr('data-id') );
        });
        $(this).find('.sizes input:checked').each(function(){
            color.sizes.push( $(this).val() );
        });
        colors.push(color);
    });
    colors = JSON.stringify(colors);
    form.find('input[name=colors]').val(colors);
	
	
    console.log( form.find('input[name=colors]').val());

	
	
	
    form.submit();
});



// Инициализирует картинки после загрузки страницы
$('#photos-section [data-id]').each(function(){
    new ImageItem(this);
});



// Добавляет панель цвета
$('#add-color').click(function(){

    var select = $('select[name=colors]');
    var value = select.val();
    
    if(value != "") {
        var selected = select.find('option:selected');

        var exists = false;
        $('.color-section').each(function(){
            if($(this).attr('data-color') == value) {
                exists = true;
            }
        });

        if(exists) {
            alert("Такой цвет уже добавлен");
            return;
        }

        createSection(
            value,
            selected.attr('data-code'),
            selected.html()
        );
    }

    $('select[name=colors]').val("");;
    $(this).prop('disabled', true);
});

// Создает секцию
function createSection(color_id, color_code, color_label)
{
    var layout = $('#template').clone();
    layout.find('.color-label').html('Цвет: ' + color_label);
    layout.attr('data-color', color_id ).prop('id','');
    layout.css('display', 'none');
    layout.find('.color-preview').css('background-color', color_code);
    layout.appendTo('#photos-section');
    layout.fadeIn(1000);
}

// Изменение выбора цвета для добавления
$('select[name=colors]').change(function(){
    $('#add-color').prop('disabled', $(this).val() == "");
});

// Удаление картинки
$('#photos-section').on('click', '.delete-button',function(e){
    e.stopPropagation();
    var imagediv = $(this).closest('div[data-id]');
    imagediv.fadeOut(300,function(){ imagediv.remove(); });
});





// DrugAndDrop:
function drugAndDropAvalible(){
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}

if(drugAndDropAvalible()){

    $('.can-drug').css('display', "block");

    
    $('#photos-section').on('drag dragstart dragend dragover dragenter dragleave drop', '.photos', function(e) {
        e.preventDefault();
        e.stopPropagation();
    }).on('dragover dragenter', '.photos', function() {
        $(this).addClass('dragover-style');
    }).on('dragleave dragend drop', '.photos', function() {
        $(this).removeClass('dragover-style');
    }).on('drop', '.photos', function(e) {
        
        var droppedFiles = e.originalEvent.dataTransfer.files;
        var section = $(this).closest('.color-section');
        
        for(var i = 0; i < droppedFiles.length; i++)
        {
            var file = droppedFiles[i];
            if(!/image\/(jpeg|jpg|png)/.test(file.type)){
                alert(file.name + ' - неверный тип файла. Выберите изображение в формате PNG или JPEG');
            } else {
                addImageToSection(file, section);
            }
        }
    });
}

$('.clip-button').click(function(){
    editor.clip();
});

$('.save-button').click(function(){
    editor.save();
});

$('body').on('click','.delete_section_btn',function(){
    var sect = $(this).closest('.color-section');
    sect.fadeOut(100, function(){ 
        sect.remove() ;
    });
});



JS;
$this->registerJS($script);