$(window).ready(function(){

    var ImgWrapper = function(image){

        var primary = 0;
        var img = null;
        var imageFile = null;
        var select = null;
        var color = null;
        var id = null;
        var uploadedCallback = null;

        function setFile(file)
        {
            imageFile = file;
            var reader = new FileReader();
            reader.onload = function(e){
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        if(image instanceof Blob)
        {
            img = $('<img>').get(0);
            setFile(image);
        }
        else if(image instanceof HTMLElement)
        {
            img = image;
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
            img.src = src = _src;
            $(img).css('display','block');
            $(img).closest('.img-wrapper').find('.loading').fadeOut(100);
            $(img).closest('.img-wrapper').find('.delete').fadeIn(100);
            $(img).closest('.img-wrapper').find('.delete').show();
            if(uploadedCallback != null) uploadedCallback();
        }


        var _api = {
            img: img,
            setSelect: function(coords) {
                select = coords;
            },
            setPrimary: function(p){
                primary = p;
            },
            getPrimary: function(){
                return primary;
            },
            setColor: function(_color) {
                color = _color;
            },
            upload: function()
            {
                if(imageFile){
                    $(img).css('display', 'none');
                    $(img).closest('.img-wrapper').find('.loading').fadeIn(100);
                    $(img).closest('.img-wrappew').find('.delete').hide();
                    _upload();
                }
            },
            getID: function()
            {
                return id;
            },
            setID: function(_id)
            {
                id = _id;
            }
        };

        img.__imgWrp = _api;

        return _api;


    };


    /**
     * ImageEditor
     */


    var ImageEditor = (function(){

        var $modalWindow = $('#modal_box');
        var $editArea = $('#box');
        var editableNow = null;
        var targetSectionApi = null;
        var isNew = false;


        var jcrop = null;

        $modalWindow.find('.save-button').on('click', save );

        function addFromFile(file){
            editableNow = ImgWrapper(file);
            editableNow.setColor(targetSectionApi.color);
            $editArea.append(editableNow.img);
            $(editableNow.img).Jcrop({
                boxWidth: 500,
                boxHeight: 500,
                setSelect:   [ 0, 0, 400, 400 ],
                aspectRatio: 1
            }, function(){ jcrop = this; show() });
            isNew = true;
            return editableNow;
        }

        function addExisting(imgWrp)
        {
            reset();
            editableNow = imgWrp;

            var checked = Number(editableNow.getPrimary());

            if(checked)
                $modalWindow.find('[name=primary]').prop('checked', true);

            $(editableNow.img).clone().appendTo($editArea);
            show();
        }

        function reset()
        {
            $modalWindow.find('[name=primary]').prop('checked', false);
            $target = null;
            $editArea.html('');
            editableNow = null;
            isNew = false;
            targetSectionApi = null;
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

            var primary = $modalWindow.find('[name=primary]').get(0).checked ? 1 : 0;

            if(isNew) {

                editableNow.setPrimary(primary);
                editableNow.setSelect( jcrop.tellSelect() );
                targetSectionApi.addImgWrp(editableNow);
                editableNow = null;
            }
            else
            {
                editableNow.setPrimary(primary);
            }
            reset();
        }

        return {
            create: function(file, $section){
                reset();
                targetSectionApi = $section;
                addFromFile(file);
            },
            edit: function(imgWrp, $section)
            {
                reset();
                targetSectionApi = $section;
                addExisting(imgWrp);
            }
        }
    })();

    $('#image-input').on('change', function(){
        var file = this.files[0];
        if(file)
            ImageEditor.create( file );
    });


    /**
     * ColorSections
     */

// Управляет областями с разными цветами
    var Sections = (function(){

        var counter = 1;
        var $container = $('.images-section');
        var sections = [];

        function createSection(elem)
        {
            var $section;

            var _color = {
                label: null,
                id: null,
                code: null,
                sizes: []
            };

            var images = [];

            if(elem){
                $section = $(elem);

                _color = {
                    id:  $section.attr('data-color-id'),
                    code: $section.attr('data-color-code'),
                    label: $section.attr('data-color-label'),
                    sizes: []
                }

                $section.find('.img-wrapper').each(function(ind, elem){
                    var $img = $(this).find('img');
                    var imgWrp = new ImgWrapper( $img.get(0) );

                    var primary = $img.closest('.img-wrapper').attr('data-image-primary');
                    var id = $img.closest('.img-wrapper').attr('data-image-id');
                    imgWrp.setID(id);
                    imgWrp.setPrimary(primary);
                    imgWrp.setColor(_color);

                    images.push(imgWrp);
                });

            } else {
                $section = $('<div class="color-block">'+
                    '<div class="form-group">'+
                        '<div class="color-section form-inline">'+
                            '<div class="form-group">'+
                                '<input type="text" class="form-control custom-name" placeholder="Новый">'+
                                '<input type="text" class="form-control color-input" data-defaultvalue="#ff6600" size="7">'+
                                '<button class="btn btn-default add-color" type="button">Добавить цвет</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>');

                $section.find('.color-input').minicolors();

                _updateSelect();
            }

            function _updateSelect()
            {
                $input = $section.find('.custom-name');
                $select = $('.origin-select').clone().attr('class','color-select');
                $oldSelect = $section.find('.color-select');
                $oldval = $oldSelect.val();
                $section.find('.color-select').remove();
                $select.insertAfter( $input );
                $option = $select.find('option[value=' + $oldval + ']').prop('selected', true);
            }

            function collectSizes()
            {
                _color.sizes = [];
                $section.find('[type=checkbox]:checked').each(function(){
                    _color.sizes.push($(this).val());
                });
            }

            function addLayout()
            {
                $section.find('.color-section .form-group').html('');

                $section.find('.color-section .form-group').append(
                    '<label class="color-label">' + _color.label + '</label>'+
                    '<div class="color-preview-box" style="background-color: '+ _color.code +'"></div>'+
                    '<label class="btn btn-default add-image">'+
                    'Добавить картинку'+
                    '<input type="file" style="display: none">'+
                    '</label>'+
                    '<button class="btn btn-default delete-color" type="button">Удалить</button>'
                );
                $section.append('<div class="images-previews clearfix"></div>');
                $sizes = $('#origin-sizes').clone().attr('id','').attr('style','');

                $section.append($sizes);
            }

            /**
             *
             * API SECTION
             *
             * @type {{index: number, color: {label: null, id: null, code: null, sizes: Array}, images: Array, $instance: *, setColor: setColor, addImage: addImage, addImgWrp: addImgWrp, updateSelect: updateSelect, removeWrp: removeWrp, getSizes: getSizes, disapear: disapear}}
             * @private
             */

            var _api = {
                index: counter++,
                color: _color,
                images: images,
                $instance: $section,
                setColor: function(color, id, code)
                {
                    _color.label = $.trim(color);
                    _color.code = code;

                    if(!id){
                        $('.origin-select').find('option').each(function(){
                            if($(this).html() == _color.label) {
                                _color.id = $(this).val();
                            }
                        });
                    } else {
                        _color.id = id;
                    }

                    if(!id)
                    {
                        $.post(ajaxURL.requestColor, { name: _color.label, code: code}, function(data){
                            if(!data.error)
                            {
                                if(!data.id) return;
                                _color.id = data.id;
                                addLayout();
                            }
                        }, 'json');
                    } else {
                        addLayout();
                    }

                    // обновляет все селекты

                    $('.origin-select option').each(function () {
                        for(var i = 0; i < sections.length; i++)
                        {
                            var val = $(this).val();
                            if(val == sections[i].color.id)
                                $(this).remove();
                        }
                    })

                    for(var i = 0; i < sections.length; i++)
                    {
                        sections[i].updateSelect();
                    }


                },
                addImage: function(file)
                {
                    ImageEditor.create(file, _api);
                },
                addImgWrp: function(imgWrp)
                {
                    $(imgWrp.img).attr('style','');

                    var wrapper = $('<div class="img-wrapper"><div class="loading" style="display: none;"></div><div class="delete" style="display: none"></div></div>');
                    wrapper.append(imgWrp.img);
                    $section.find('.images-previews').append(wrapper);
                    images.push(imgWrp);
                    imgWrp.upload();
                },
                updateSelect: function()
                {
                    _updateSelect();
                },
                removeWrp: function(_imgwrp)
                {
                    for(var i = 0; i < images.length; i++)
                    {
                        if(images[i].getID() == _imgwrp.getID())
                        {
                            $(images[i].img).closest('.img-wrapper').remove();
                            images.splice(i,1);
                        }
                    }
                },
                getSizes: function()
                {
                    collectSizes();
                    return _color.sizes;
                },
                disapear: function()
                {
                    $section.fadeOut(200, function () {
                        $section.remove();
                    })
                }
            }

            $section.get(0).__sectionApi = _api;
            $container.append($section);

            return _api;

        }


        /**
         *
         * API SECTIONS
         *
         *
         * @type {{_sections: Array, addNew: addNew, getImagesID: getImagesID, getColors: getColors, deleteColorSection: deleteColorSection, updateSelects: updateSelects, Init: Init}}
         */

        var api = {
            _sections: sections,
            // вызывается извне для добавления формы создания цвета
            addNew: function()
            {
                var section = createSection();
                sections.push(section);
            },
            getImagesID: function()
            {
                var str = [];
                for(var i = 0; i < sections.length; i++)
                    for(var u = 0; u < sections[i].images.length; u++)
                        str.push(sections[i].images[u].getID());

                return str;
            },
            getColors: function()
            {
                var colors = [];
                for(var i = 0; i < sections.length; i++)
                {
                    colors.push({
                        id: sections[i].color.id,
                        sizes: sections[i].getSizes()
                    });
                }
                return colors;
            },
            deleteColorSection: function(section)
            {
                for(var i = 0; i < sections.length; i++) {
                    if(sections[i].index == section.index) {
                        section.disapear();
                        sections.splice(i, 1);
                    }
                }
            },
            updateSelects: function()
            {
                for(var i = 0; i < sections.length; i++)
                {
                    $('.origin-select').find('option[value=' + sections[i].color.id + ']').remove();
                    sections[i].updateSelect();
                }
            },
            Init: function()
            {
                $('.images-section .color-block').each(function(ind, elem){
                    var newSection = createSection(elem);
                    sections.push(newSection);
                });
            }
        };

        return api;

    })();












    $('.images-section').on('click', '.add-color', function(){

        var $colorBlock, label, code, id;

        $colorBlock = $(this).closest('.color-block');







        if(label == '') {

            var $select =  $colorBlock.find('select');
            var selected = $select.find('option:selected');

            if($select.val() != "0"){

            } else {
                alert('введите название или выберите из списка');
                return;
            }
        }

        if(code == "") {
            // TODO: если создается новый цвет, но не выбран код.
        }







        //-------
        var $selectedColor = $colorBlock.find('select option:selected');
        if( $selectedColor.val() != '0' )
        {
            id = selected.val();
            label = selected.html();
            code = selected.attr('data-code');
        }
        else
        {
            label = $colorBlock.find('.custom-name').val();
            code = $colorBlock.find('input.minicolors-input').val();
        }

        if(label == '' || code == ''){
            alert('Укажите название и укажите цвет, или выберите из списка');
            return;
        }

        $colorBlock.get(0).__sectionApi.setColor(label, id, code);

    });

    $('.images-section').on('change', '.add-image input', function(){
        if(this.files[0])
        {
            var sectionApi = $(this).closest('.color-block').get(0).__sectionApi;
            sectionApi.addImage(this.files[0]);
        }

    });

    $('.images-section').on('click', '.img-wrapper', function(){
        var imgApi = $(this).find('img').get(0).__imgWrp;
        var sect = $(this).closest('.color-block').get(0).__sectionApi;
        ImageEditor.edit(imgApi, sect);

    });

    $('.images-section').on('click', '.img-wrapper div.delete', function(e){
        e.stopPropagation();
        var imgWrp = $(this).closest('.img-wrapper').find('img').get(0).__imgWrp;
        var sectApi = $(this).closest('.color-block').get(0).__sectionApi;
        sectApi.removeWrp(imgWrp);
        return false;
    });

    $('.images-section').on('change','select', function(e){
        e.stopPropagation();

        var $select = $(this);
        var $minicolorInput = $select.closest('.color-block').find('input.minicolors-input');

        if( $select.val() != "0"){
            var code = $select.find('option:selected').attr('data-code');
            $minicolorInput.minicolors('value',{ color: code });
        } else {
            $minicolorInput.minicolors('value',{ color: '' });
        }
    });

    $('.images-section').on('click','button.delete-color', function(e){
        e.stopPropagation();

        var sectapi = $(this).closest('.color-block').get(0).__sectionApi;

        Sections.deleteColorSection(sectapi);

        updateSelects();
    });


    function updateSelects()
    {
        $.post(ajaxURL.updateSelects, {}, function(data){
            data = JSON.parse(data);
            if(data.error == false)
            {
                $origin = $('.origin-select').empty();
                $origin.append('<option value="0" data-code="0">Не выбрано</option>');
                if(data.options && data.options.length)
                {
                    for(var i = 0; i < data.options.length; i++)
                    {
                        var id = data.options[i].id;
                        var label = data.options[i].label;
                        var code = data.options[i].code;
                        $origin.append('<option value="' + id + '" data-code="' + code + '">' + label + '</option>');
                    }

                    Sections.updateSelects();
                }
            }
        });
    }























    $('#add-color').on('click', function(){
        Sections.addNew();
    });





    /**
     * Форма
     */

    function validate() {
        var s = validateSettings();
        var p = validatePrice();
        var j = validateColors();

        return s&&p&&j;
    }

    function validateColors()
    {
        var errors = [];
        if(Sections.getColors().length == 0)
        {
            errors.push("Вы не добавили ни одного цвета");
        }

        var valid = true;
        for(var i = 0; i < Sections._sections.length; i++)
        {
            var Sect =  Sections._sections[i];

            var sizes = Sect.getSizes();
            if(sizes.length == 0)
            {
                Sect.$instance.find('.error-text').html('Вы не выбрали ни одного размера').fadeIn(300);
                valid = false;
            }
            else
            {
                Sect.$instance.find('.error-text').html('').fadeOut(300);
            }
        }

        var $ul = $('#colors-errors').find('ul');

        $ul.empty();

        if(errors.length)
        {

            for(var i = 0 ; i < errors.length; i++ )
            {
                $ul.append("<li>"+errors[i]+"</li>");
            }
            $('#colors-errors').fadeIn(300);
            return false;
        } else {
            $('#colors-errors').fadeOut(200);

            return valid;
        }
    }
    function validateSettings() {
        var errors = [];
        var $vendorcode = $('[name="vendorcode"]');

        if($vendorcode.val() == ''){
            errors.push("Введите Артикул товара");
            $vendorcode.closest('.form-group').addClass('has-error');
        } else {
            $vendorcode.closest('.form-group').removeClass('has-error');
        }

        var $brand = $('[name=brand_id]');


        if($brand.val() == 0){
            errors.push("Выберите брэнд");
            $brand.closest('.form-group').addClass('has-error');
        } else {
            $brand.closest('.form-group').removeClass('has-error');
        }

        var $category = $('[name=category_id]');


        if($category.val() == 0){
            errors.push("Выберите категрию");
            $category.closest('.form-group').addClass('has-error');
        } else {
            $category.closest('.form-group').removeClass('has-error');
        }

        var $material = $('[name=material_id]');


        if($material.val() == 0){
            errors.push("Выберите материал");
            $material.closest('.form-group').addClass('has-error');
        } else {
            $material.closest('.form-group').removeClass('has-error');
        }

        var $field = $('#settings_errors');

        if(errors.length)
        {
            var $ul = $field.find('ul').empty();
            for(var i = 0; i < errors.length; i++)
            {
                $ul.append('<li>' + errors[i] + '</li>');
            }
            $field.fadeIn(200);
            return false;
        } else {
            $field.hide();
        }

            return true;
    }

    function validatePrice() {
        var errors = [];
        var $purchasePrice = $('[name="purchase_price"]');

        if($purchasePrice.val() == ''){
            errors.push("Укажите закупочную цену");
            $purchasePrice.closest('.form-group').addClass('has-error');
        } else {
            $purchasePrice.closest('.form-group').removeClass('has-error');
        }

        var $field = $('#price_errors');

        if(errors.length)
        {
            var $ul = $field.find('ul').empty();
            for(var i = 0; i < errors.length; i++)
            {
                $ul.append('<li>' + errors[i] + '</li>');
            }
            $field.fadeIn(200);
            return false;
        } else {
            $field.hide();
        }

        return true;
    }

    $('form').on('submit', function(e){

        if(!validate()){
            e.preventDefault();
            return false;
        }

        var images_ids = Sections.getImagesID();
        var colorsIds = Sections.getColors();
        $('[name=images]').val(JSON.stringify(images_ids));
        $('[name=colors]').val(JSON.stringify(colorsIds));

        // для отладки
        //e.preventDefault();
        //return false;

    });

    $('#checkedall').on('click', function(){
        $(this).closest('.panel-body')
            .find('[type=checkbox]')
            .prop('checked', true);
    });

    Sections.Init();

});