
function urlFromBackgroundCss(styleString){
    return styleString.replace(/^url\("?(.*?)"?\)$/, "$1");
}

function updateColors(colors, selected)
{
    var html = '';
    var selectedAttr = '';
    for(var i=0; i< colors.length; i++)
    {
        if(colors[i].id == selected){
            selectedAttr = 'selected';
        } else
            selectedAttr = ''

        html += "<option value='"+ colors[i].code +"' " + selectedAttr + ">" + colors[i].name + "</option>"
    }

    $('#product_colors').html(html);
}

function updateSizes(sizes){

    $('#product_sizes').html(' ');

    var html = '';

    for(size in sizes)
    {
        html += "<li ";
        if(size.avalible) html += " class='active'";
        html += ">" + size.europe + "</li>";
    }

    //$('#product_sizes').html(html);
}

function updateImages(list){

    var html = '';
    for(var i=0; i < list.length; i++)
    {
        html += "<div class=\"product_body_left_mins_item";
        if(list[i].primary) {
            html += " active";
            var link = list[i].link.replace('small_', 'normal_');
            $('#product_preview').get(0).src = link;
        }
        html += "\" style=\"background-image: url('" + list[i].link + "');\"></div>";
    }
    $('#product_icons').html(html);


}

function updateProductForm(data){

    $('#label-product-category').html(data['category_name'] +", "+ data['brand']);
    $('#vendor').html("Артикул: " + data['articul'] + "<br>Производитель: <a href='##'>"+data['brand']+"</a>");
    $('#price').html(data['price']);
    $('#product_container').attr('data-color-id', data['color']);
    $('#product_container').attr('data-model-id', data['model']);
    $('#product_container').attr('data-product-id', data['product']);

    updateImages(data['imagesList']);
    updateColors(data['colors'], data['color']);
    updateSizes(data['sizes']);

    var discount = data['discount'];
    if(discount){
        $('#discount').html("<span>" + discount.price + "</span>(" + discount.percent + "%)");
    } else {
        $('#discount').html();
    }
    initProductForm();

}


function initProductForm()
{
    $('#product_sizes div').on('change',function(){

    });
    $('#product_colors').on('change',function(){

        var url = "/frontend/web/index.php?r=catalog%2Fajax_change";
        var formData = new FormData();
        var color = $(this).val();

        formData.append("color", $('#product_container').attr('data-color-id'));
        formData.append("model", $('#product_container').attr('data-model-id'));

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false
        }).done(function(data){
            data = JSON.parse(data);
            updateProductForm(data);
        });
    });

    $('#product_icons div').mouseenter(function(){
        $('#product_icons div').removeClass('active');
        $(this).addClass('active');
    });
}

$(window).on('load', function(){

    initProductForm();

});