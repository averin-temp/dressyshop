/**
 * Created by hust on 10.05.2017.
 */
// Класс обертка для изображений, для удобства управления

/**
 * описание
 *
 * @param image  Blob или объект img
 * @returns {{self: *, setPrimary: setPrimary, setSelect: setSelect, getSelect: getSelect, getID: getID, setID: setID, getPrimary: getPrimary, setColor: setColor, getColor: getColor, getFile: getFile, setSrc: setSrc}}
 * @constructor
 */
var ImgWrapper = function(image)
{

    /* Jquery объект тега img */
    var $instance = null;
    /* Основная картинка цвета */
    var primary = 0;
    var color = null;
    var imageFile = null;
    var src = null;
    var select = null;
    var id = null;
    var uploadedCallback = null;

    if(image instanceof Blob) {
        $instance = createImg();
        setFile(image);
    }
    else {
        $instance = $(image);
        src = image.src;
    }

    function setFile(file)
    {
        imageFile = file;
        var reader = new FileReader();
        reader.onload = function(e){
            $instance.get(0).src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function _upload()
    {
        var formdata = new FormData();

        formdata.append('color_label', color.label);
        formdata.append('primary', primary);
        if (color.id) formdata.append('color_id', color.id);
        if (imageFile) formdata.append('file', imageFile);
        if (select) formdata.append('selection', JSON.stringify(select) );
        if (id) formdata.append('id', id);

        $.ajax({
            url: ajaxURL.upload,
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data.error === true)
                alert(data.message);
            else if(data.error === false){
                onUploaded(data.id, data.src)
            }else{
                alert(data);
            }
        });
    }



    function onUploaded(_id, _src)
    {
        imageFile = null;
        id = _id;
        $instance.get(0).src = src = _src;
        $instance.css('display','block');
        if(uploadedCallback != null) uploadedCallback();
    }

    function createImg() { return $('<img>') }

    var api = {
        $self: $instance,
        setPrimary: function(val) {
            primary = val;
        },
        setSelect: function(c) {
            select = c;
        },
        getSelect: function(){
            return select;
        },
        getID: function(){
            return id;
        },
        setID: function(_id){
            id = _id;
        },
        getPrimary: function(){
            return primary;
        },
        setColor: function(label,id){
            color = {
                label: label,
                id: id
            };
        },
        getColor: function(){
            return color;
        },
        getFile: function(){
            return imageFile;
        },
        setSrc: function(source){
            src = source;
            $instance.get(0).src = source;
        },
        upload: function()
        {
            if(imageFile == null) return;
            $instance.css('display', 'none');
            _upload();
        },
        onUpload: function(func)
        {
            uploadedCallback = func;
        }
    };

    $instance.get(0).__imageWrapper__link = api;
    return api;
}

// Управляет областями с разными цветами
var Sections = (function(){
    var sections = [];
    var $container = $('.images-section');

    function createSectionElem() { return $('<div class="color-images-wrapper" data-color-id="" ><label for=""></label><div class="color-images"></div></div>');}

    function createWrapper() { return $('<div class="img-wrapper"></div>'); }

    function createField()
    {

    }

    /**
     * Добавляет объект секции в стек
     *
     * @param $el
     * @param color_id
     * @param color_label
     */
    function add($el, color_id, color_label)
    {
        var sect = {
            id: color_id,
            label: color_label,
            $elem: $el,
            imgs: new Array()
        };

        sections.push(sect);
    }


    function find($label)
    {
        for(var i=0; i< sections.length; i++) {
            if(sections[i].label == $label) return sections[i];
        }
        return false;
    }

    function createNew()
    {
        var $instance = $("<div class='section-block'><input type='text' placeholder='Введите название цвета'><button type='button' class='save-color'>Создать</button></div>");

        var self = {
            color: {
                id: null,
                code: null,
                label: null
            } ,
            $elem: $instance,
            imgs: new Array()
        };

        $instance.on('click', '.save-color', function(){

            var label = $instance.find("[type='text']").val();
            if(label == '') return;
            label = $.trim(label);
            var code = 1;
            // получить код
            $.post(ajaxURL.requestColors, {code: code, label: label}, function(){
                $instance.find('button').hide();


            });

        });


        return self;

        //var $instance = createSectionElem();

        var block =
            block.
            $('.images-section').append()

        $instance.find('label').html(lbl);

        $container.append($instance);


    }

    function addToSection(section, ImgWrp)
    {
        var wrapper = createWrapper();

        /**
         * Если картинка отмечена как primary,
         * то у всех других картинок этого цвета
         * primary отключается, маркеры убираются,
         * а в саму картинку ставится маркер
         */
        if(ImgWrp.getPrimary()){

            for(var i = 0; i < section.imgs.length; i++)
            {
                var imgWrp = section.imgs[i];
                var prim = imgWrp.getPrimary();

                if(prim)
                {
                    imgWrp.setPrimary(0);
                    imgWrp.self.closest('.img-wrapper').find('.star').remove();
                }
            }

            wrapper.append( $("<div class='star'>") );
        }

        section.imgs.push(ImgWrp);
        wrapper.append(ImgWrp.$self);
        section.$elem.find('.color-images').append(wrapper);
        ImgWrp.upload();
    }

    return {
        addImgWrp: function(ImgWrp)
        {
            var cLabel = ImgWrp.getColor().label;
            var section = find(cLabel);

            if(section == false) {
                section = createNew(cLabel);
                sections.push(section);
            }

            addToSection(section, ImgWrp);
        },
        init: function()
        {


            $('.color-images-wrapper').each(function(index, sectionElem){

                var data_color_id,
                    data_color_label;

                $sectionElem = $(sectionElem);
                data_color_id = $sectionElem.attr('data-color-id');
                data_color_label = $sectionElem.attr('data-color-label');

                add($sectionElem, data_color_id, data_color_label);

                $sectionElem.find('.img-wrapper').each(function(ind, img_wrapper)
                {
                    var $img_wrapper = $(img_wrapper);
                    var image = $img_wrapper.find('img');
                    var data_primary = $img_wrapper.attr('data-primary');
                    var id = $img_wrapper.attr('data-id');

                    var imgWrap = imageWrapper(image);
                    imgWrap.setColor( data_color_label, data_color_id );
                    if(id) imgWrap.setID(id);
                    imgWrap.setPrimary(data_primary);

                    section = find(data_color_label);
                    section.imgs.push(imgWrap);
                });

            });

        },
        getSections: function()
        {
            return sections;
        }


    }

})();

// Класс редактора изображений, управляет окном редактирования
var ImageEditor = (function(){

    var $modalWindow = $('#modal_box');
    var $editArea = $('#box');
    var editableNow = null;
    var isNew = false;

    var jcrop = null;

    var $target = null;

    $modalWindow.find('.save-button').on('click', save ) ;

    function addFromFile(file){
        reset();
        editableNow = ImgWrapper(file);
        $editArea.append(editableNow.$self);
        editableNow.$self.Jcrop({
            boxWidth: 500,
            boxHeight: 500,
            setSelect:   [ 0, 0, 400, 400 ],
            aspectRatio: 1
        }, function(){ jcrop = this});
        isNew = true;
    }

    function addExisting(imgWrp)
    {
        reset();
        $target = imgWrp;
        editableNow = $target;

        var color = editableNow.getColor();

        var $option = $modalWindow.find('[name=selectColor] option[value=' + color.id + ']');
        if($option.length)
            $option.prop('selected', true);
        else
            $modalWindow.find('[name=newcolor]').val(color.label);

        var checked = Number(editableNow.getPrimary());
        if(checked)
            $modalWindow.find('[name=primary]').prop('checked', true);

        editableNow.self.clone().appendTo($editArea);
    }

    function reset()
    {
        $modalWindow.find('[name=selectColor]').val(0);
        $modalWindow.find('[name=newcolor]').val('');
        $modalWindow.find('[name=primary]').prop('checked', false);
        $target = null;
        $editArea.html('');
        editableNow = null;
    }

    function show(){
        $modalWindow.modal();
    }

    function close()
    {
        $modalWindow.modal('hide');
    }

    function save()
    {

        close();

        var colorLabel = $modalWindow.find('[name=newcolor]').val();
        colorLabel = $.trim(colorLabel);
        var colorID = 0;

        if(colorLabel == '') {
            var $select = $modalWindow.find('[name=selectColor]');
            colorID = $select.val();
            colorLabel = $select.find('option:selected').html();
        }

        var primary = $modalWindow.find('[name=primary]').get(0).checked ? 1 : 0;


        if(isNew) {

            editableNow.setPrimary(primary);
            editableNow.setColor(colorLabel, colorID);
            editableNow.setSelect( jcrop.tellSelect() );
            editableNow.$self.attr('style','');
            Sections.addImgWrp(editableNow);
            editableNow = null;
        }
        else
        {
            editableNow.setPrimary(primary);
            editableNow.setColor(colorLabel, colorID);
        }
    }

    return {
        create: function(file){
            addFromFile(file);
            show();
        },
        edit: function(imgWrp)
        {
            addExisting(imgWrp);
            show();
        }
    }
})();










$('#checkedall').on('click', function(){
    $(this).closest('form')
        .find('.radios')
        .find('[type=checkbox]')
        .prop('checked', true);
});


$('.images-section').on('click', '.img-wrapper', function(){
    var $imgWrp = $(this).find('img').get(0).__imageWrapper__link;
    ImageEditor.edit($imgWrp);
});

$('#image-input').on('change', function(){
    var file = this.files[0];
    if(file)
        ImageEditor.create( file );
});

$('#add-color').on('click', function(){
    Sections.addNewSection();
});

//Sections.init();