<?php
use yii\helpers\Url;
?><div class="popup_cart">
    <div class='popuppreloader'></div>
    <div class='popupempty'>
        <div class='table'>
            <div class='table_cell'>
                <span>Корзина пуста</span>
                <a href="/latest" class="button">Перейти в каталог</a>
            </div>
        </div>
    </div>
    <div class="popup_cart_top"><span>Корзина</span></div>
    <div class="popup_cart_cart">
        <div class="popup_cart_cart_inner">

        </div>
    </div>
    <div class="popup_cart_res popup_popup_cart_res">
        <div class="popup_cart_cart_inner_item_info_bot">
            <div>Товаров в корзине: <span class="cart-products-count">0</span></div>
            Сумма: <span class="cart-products-price">0</span> руб.
        </div>
    </div>
    <div class="popup_cart_bottom clearfix">
        <a href="##" onclick="$('.popup_bg').click()">Продолжить покупки</a>
        <a href="<?= Url::to(['/cart/index']) ?>" class="button">Перейти к оформлению</a>
    </div>
    </div>
<?php
$script = <<< JS


/*----------------------------------------------------------------
    Корзина
-------------------------------------------------------------- */

// Запрос на удаление товара
$('.popup_cart_cart_inner').on('click', '.popup_cart_cart_inner_item_remove a', function(e){
    e.preventDefault();
    var container = $(this).closest('.popup_cart_cart_inner_item');
    var old_key = container.attr('data-product-key');

    var data = {key: old_key};
    drassy_callback('Удаление товара из корзины. запрос', data);
    $.post('/cart/ajax_remove', data , function(data){
        drassy_callback('Удаление товара из корзины. ответ', data);
        $('.popup_cart_cart_inner_item[data-product-key='+old_key+']').remove();
        $('.cart-products-count, .popup_cart_cart_inner_item_info_bot span.total_price').html(data.totalCount + ' ');
        $('.cart-products-price, .popup_cart_cart_inner_item_info_bot span.result_price').html(data.totalPrice.toFixed(0));
    }, 'json')
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    }).done(function(){
        delivery_price();
    })
});


$('.popup_cart_cart_inner').on('click', '.cd_close', function(){
    $(this).closest('.cd').fadeOut();
});

// Удаление 
$('.popup_cart_cart_inner').on('click', '.popup_cart_cart_inner_item_remove img', function(){
    $(this).closest('.popup_cart_cart_inner_item_remove').find('.cd').fadeIn();
});

// Открытие окна корзины
$('.header_cart_cart').click(function(){
    $.pp_open('popup_cart')
});

// Запрос при открытии окна корзины
$('.header_cart_cart').click(function () {
    drassy_callback('Открытие корзины, запрос контента', {});
    $.post('/cart/ajax_get', { promo: $('input[name="Order[promocode]"]').val() }, function(data){
        drassy_callback('Ответ на запрос контента корзины', data);
        console.log(data)
        $('.popup_outer .popup_cart_cart_inner').html(data.content);
        $('.cart-products-count, .popup_cart_cart_inner_item_info_bot span.total_price').html(data.totalCount + ' ');
        $('.cart-products-price, .popup_cart_cart_inner_item_info_bot span.result_price').html(data.totalPrice.toFixed(0));
        $.pp_open('popup_cart');
    }, "json")
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    });
});

// Изменение цвета товара в корзине
$('.popup_cart_cart_inner').on('change', '.color_picker select', function(){
    var container = $(this).closest('.popup_cart_cart_inner_item');
    var old_product_key = container.attr('data-product-key');

    var data = {
        key: old_product_key,
        color: $(this).val()
    };
    drassy_callback('Запрос на изменение цвета в корзине', data);
    $.post('/cart/ajax_replace', data, function(data){
        drassy_callback('Ответ на изменение цвета в корзине', data);
        var container = $('[data-product-key='+old_product_key+']');
        container.attr('data-product-id', data.id);
        container.find('a.popup_cart_cart_inner_item_photo').attr('href', data.url);
        container.attr('data-product-key', data.key);
        container.find('.popup_cart_cart_inner_item_photo').css('background-image', ' url(' + data.image + ')');
        container.find('.cart-vendor-code').html(data.vendorCode);

        $('.cart-products-count, .popup_cart_cart_inner_item_info_bot span.total_price').html(data.totalCount + ' ');
        $('.cart-products-price, .popup_cart_cart_inner_item_info_bot span.result_price').html(data.totalPrice.toFixed(0));
    }, 'json')
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    });
});

/*----------------------------------------------------------------
    Конец. Корзина
-------------------------------------------------------------- */


JS;
$this->registerJS($script);