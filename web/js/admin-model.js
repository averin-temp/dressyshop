$(window).ready(function(){



    var Editor = function()
    {
        this.$targetSection = null;
        this.file = null;
        this.jcrop = null;
        this.$targetimage = null;
        this.$modal = $('#modal_box');
        this.editbox = this.$modal.find('.edit-box');
        this.button = this.$modal.find('button');

        this.open = function() {
            if(!this.$targetimage)
                this.button.html('Добавить');
            else
                this.button.html('Сохранить');
            this.$modal.modal('show');
        };
        this.close = function() {
            this.$modal.modal('hide');
        };
        this.reset = function(){
            this.$targetSection = null;
            this.file = null;
            this.$targetimage = null;
            this.editbox.html('');
            this.jcrop = null;
        };

        this.uploadedImage = function(imageData, file, section)
        {
            this.$targetSection = section;
            this.$modal.find('[name=primary]').prop('checked', false );
            this.file = file;
            var $img = $('<img>');
            $img.get(0).src = imageData;
            this.setImage($img, true);
        };

        this.editImage = function($imgTag)
        {
            var primary = $imgTag.attr('data-primary') == "0" ? false : true;
            this.$targetimage = $imgTag;
            this.$modal.find('[name=primary]').prop('checked', primary );
            this.setImage($imgTag.find('img').clone(), false);
            this.open();
        };

        this.setImage = function($img, jcrop)
        {
            var that = this;
            this.editbox.html('');
            this.editbox.append($img);
            if(jcrop == true){
                $img.Jcrop({
                    boxWidth: 400,
                    boxHeight: 400,
                    setSelect:   [ 0, 0, 400, 400 ],
                    aspectRatio: 1
                }, function(){
                    that.jcrop = this;
                    imageEditor.open()
                });
            }
        };

        this.getSelect = function()
        {
            var select = this.jcrop.tellSelect();

            if(select.w == 0 || select.h == 0)
                return null;

            return JSON.stringify(select);
        };

        this.sendImage = function()
        {
            var model = $('[name=model]').val();
            var color = this.$targetSection.attr('data-color');
            var select = this.getSelect();
            var primary = this.$modal.find('[name=primary]').is(':checked') ? 1 : 0 ;

            if(select === null) return "no select";

            var formData = new FormData();
            formData.append('image', this.file );
            formData.append('model', model );
            formData.append('color', color );
            formData.append('selection', select );
            formData.append('primary', primary );


            $.ajax({
                url: ajaxURL.uploadImage,
                type: 'post',
                data: formData,
                contentType: false,
                processData: false
            }).done(function (data) {
                data = JSON.parse(data);
                if(data instanceof Object) {
                    if (data.error === false)
                        imageEditor.sendImageCallback(data.src, data.id, data.primary);
                    else
                        alert(data.message);
                } else alert(data);
            });

        };

        this.sendImageCallback = function(url, image_id, primary){
            sections.addImage(url, image_id, primary,  this.$targetSection);
        };

        this.save = function(){

            if(this.isNewImage())
            {
                return this.sendImage();
            }
            else
            {
                this.updateImage();
                this.close();
            }
        };

        this.isNewImage = function()
        {
            if(this.$targetimage == null) return true;
            return false;
        };

        this.updateImage = function(){
            var primary = this.$modal.find('[name=primary]').is(':checked') ? 1 : 0 ;
            sections.setPrimaryImage(this.$targetimage, primary);
        }

        this.$modal.on('hidden.bs.modal', function (e) {
            imageEditor.reset();
        })

    };

    var Sections = function()
    {
        this.parentTag = $('#photos-section');
        this.templates = {};

        this.initTemplates = function()
        {
            var $layout = $('#template');

            var $imgLayout = $layout.find('.photos > div');
            this.templates.image = $imgLayout.clone();
            $imgLayout.remove();

            this.templates.section = $layout.clone();
            $layout.remove();
        };

        this.initTemplates();

        this.create = function(color_id, color_code, color_label)
        {
            var sectionTemplate = this.templates.section.clone();
            sectionTemplate.find('.color-label').html('Цвет: ' + color_label);
            sectionTemplate.attr('data-color', color_id ).prop('id','');
            sectionTemplate.css('display', 'none');
            sectionTemplate.find('.color-preview').css('background-color', color_code);
            sectionTemplate.appendTo(this.parentTag);
            sectionTemplate.fadeIn(1000);

        };

        this.newImage = function() {
            return this.templates.image.clone();
        };

        this.addImage = function(url, id, primary, section){
            var $img = this.newImage();
            $img.attr('data-id', id)
                .find('img')
                .attr('src', url);
            section.find('div.photos').append($img);
            sections.setPrimaryImage($img, primary);
            imageEditor.close();
        };

        this.setPrimaryImage = function(image, primary)
        {
            if(primary == 1){
                image.closest('.color-section')
                    .find('.photos > div')
                    .attr('data-primary', "0");
            }

            image.attr('data-primary', primary);
        };

    };




    var imageEditor = new Editor();
    var sections = new Sections();




    $('select[name=colors]').change(function(){
        $('#add-color').prop('disabled', $(this).val() == "");
    });

    $('#add-color').click(function(){

        var $select = $('select[name=colors]');
        var value = $select.val();

        if(value != "")
        {
            var selected = $select.find('option:selected');

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

            sections.create(
                value,
                selected.attr('data-code'),
                selected.html()
            );
        }

        $('select[name=colors]').val("");;
        $(this).prop('disabled', true);
    });


    $('#photos-section').on('change', 'input[type=file]', function(){

        var i, file, image,
            files = this.files;

        var sectionTag = $(this).closest('.color-section');
        $(this).closest('label').append($('<input type="file">'));
        $(this).remove();


        for( i = 0; i < files.length; i++)
        {
            file = files[i];
            if(checkFile(file)) {
                image = createImageFromFile(file);

            }
        }





        var reader = new FileReader();
        reader.onload = function(){
            imageEditor.uploadedImage(reader.result, file, sectionTag);
        };

        reader.readAsDataURL(file);
    });

    $('#photos-section').on('click', '.photos > div', function(e){
        e.stopPropagation();
        imageEditor.editImage($(this));
    } );


    $('#colors-form').on('submit',function(e){

        var colors = [];
        var errors = [];

        $('.color-section').each(function(){

            var color_id = $(this).attr('data-color');

            var images = [];
            var primary = 0;
            $(this).find('.photos > div').each(function(){
                images.push( $(this).attr('data-id') );
                if($(this).attr('data-primary') == '1')
                    primary = $(this).attr('data-id');
            });

            var sizes = [];
            $(this).find('.sizes input').each(function(){
                if( $(this).is(':checked') ) sizes.push( $(this).val() );
            });

            if(sizes.length === 0 && !noSizes) {
                $(this).find('.sizes').addClass('has-error');
                errors.push("Вы не указали размеры");
            }else{
                $(this).find('.sizes').removeClass('has-error');
            }

            colors.push({ id: color_id, images: images, sizes: sizes, primary: primary });
        });

        if(errors.length) {
            alert(errors);
            return false;
        }

        $('[name=colors]').val( JSON.stringify(colors) );

    });




    $('.image-options .save-button').click(function(){
        if(imageEditor.save() == 'no select')
            alert('выделите область на фотографии');
    });

    $('#photos-section').on('click','a.confirm-delete',function(e){
        e.preventDefault();
        e.stopPropagation();
        var secton = $(this).closest('.color-section');
        secton.fadeOut(1000, function(){
            secton.remove();
        });
    });

    $('#photos-section').on('click', '.checkbox span', function(){
        $(this).closest('.sizes').removeClass('has-error');
    });


    function checkFile(file)
    {
        if(!/image\/(jpeg|jpg|png)$/i.test(file.mimeType))
        {
            console.log('неверный тип файла: ' + file.name);
            return false;
        }
    }

    function createImageFromFile(file)
    {
        var image = new Image();
        image.file = file;
        image.upload();
        return image;
    }








     function Image()
     {
         this.id = null;
         this.filename = null;
         this.file = null;
         this.source = null;
         this.body = null;
         this.section = null;

         this.upload = function(){

             var formData = new FormData();
             formData.append('image', this.file );
             formData.append('model', model );
             // TODO: найти где откуда model
             formData.append('color', color );
             formData.append('selection', select );
             formData.append('primary', primary );


             $.ajax({
                 url: ajaxURL.uploadImage,
                 type: 'post',
                 data: formData,
                 contentType: false,
                 processData: false
             }).done(function (data) {
                 data = JSON.parse(data);
                 if(data instanceof Object) {
                     if (data.error === false)
                         imageEditor.sendImageCallback(data.src, data.id, data.primary);
                     else
                         alert(data.message);
                 } else alert(data);
             });
         };

         this.clip = function(){

         };
     }



/*
var color = {
    id: 1,
    images: [ 1, 2, 3],
    primary: 3,
    sizes: [ 2 ,4, 6 ]
};

var colors = [ color1, color2,.. ];
*/





});

