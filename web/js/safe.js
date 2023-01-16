/**
 * Created by hedindoom on 26.06.2017.
 */

// explode function;
function explode(delimiter, string) {

    var emptyArray = {0: ''};

    if (arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined') {
        return null;
    }

    if (delimiter === ''
        || delimiter === false
        || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }

    return string.toString().split(delimiter.toString());
}


$('#main-menu-list>li').click(function(){
	$.ajax('/system/clearfilter');
})
function checkdelivery() {

    $('.cart_form_radios_pay').hide(0);
    $('.cart_form_radios_pay>div').hide(0);
    $('input:radio').prop('checked', false).trigger('refresh');
$('.selecdeliverytype').show(0)

    $('.cart_form_radios.cart_form_radios_delivery>div').show(0);
    regionId = $('#order-region option:selected').data('regid');
    prodCount = $('.page_cart_body .popup_cart_cart_inner_item_info_bot .total_price').text();

    if (regionId == undefined   ) {
        $('.cartradios').addClass('unact')
    }
    else {
    $('.cart_form_radios.cart_form_radios_delivery>div').each(function () {
        $('.cartradios').removeClass('unact')
        ths = $(this);
        del_pr_cnts = $(this).data('products_count');
        del_region = explode(',', $(this).data('delivery_region'));

        var bycount = 'show';
        var byreg = 'show';
        if (prodCount > del_pr_cnts) {
            bycount = 'hide';
        }

        if ($.inArray(String(regionId), del_region) == -1) {
            byreg = 'hide';
        }

        console.log(bycount);
        console.log(byreg);

        if (bycount === 'hide' || byreg === 'hide') {
            ths.hide(0)
        }


    })
    }
    check_radios();
}

function check_radios() {
    ch_pay = $('.cart_form_radios_pay input:checked').data('paytrue');
    ch_del = $('.cart_form_radios_delivery input:checked').data('payprice');
    rules = $('.cart_form_cheks .jq-checkbox.checked').length
    if (!ch_pay || !ch_del || rules == 0) {
        $('.mmpvl').removeClass('active')
    }
    if (!ch_pay) {
        cartmes = 'Выберите способ оплаты.'
    }
    if (!ch_del) {
        cartmes = 'Выберите способ доставки.'
    }

    if (rules == 0) {
        cartmes = 'Дайте согласие на правила обработки заказов.'
    }

    if (!ch_pay && !ch_del && rules == 0) {
        cartmes = 'Выберите способы доставки и оплаты. Дайте согласие на правила обработки заказов.'
    }
    if (ch_pay && ch_del && rules == 1) {
        cartmes = '';
        $('.mmpvl').addClass('active')
    }

    $('.hidecarbut').attr('title', cartmes)

}
if($('.rowotzw ').length){
$('.rowotzw ').each(function(){
	heta = 0;
	$(this).children('.product_reviews_body_item ').each(function(){
		if(parseInt($(this).height()) > heta){
			heta = parseInt($(this).height());
		}	
	})
	$(this).children('.product_reviews_body_item ').height(heta)
})
}
function delivery_price() {
    delivery_price2 = $('.cart_form_radios_delivery input:checked').data('payprice');


    if (!delivery_price2) {
        delivery_price2 = 0
    }
    curprice = parseInt($('.result_price').first().text());
    freedelprice = $('.cart_form_radios_delivery input:checked').data('freepayprice');

    if (curprice >= freedelprice) {
        delivery_price2 = 0;
    }

    $('.result_price_delivery').html(delivery_price2);
    $('.delivery_price').val(delivery_price2)
    $('.result_price_final').html(curprice + delivery_price2);


    $('.cart_form_radios_delivery>div').each(function(){


        delivery_price2xx = $(this).find('input').data('payprice');


        if (!delivery_price2xx) {
            delivery_price2xx = 0
        }
        curpricexx = parseInt($('.result_price').first().text());
        freedelpricexx = $(this).find('input').data('freepayprice');
        if (curpricexx >= freedelpricexx) {
            delivery_price2xx = 0;
        }


        $(this).find('.delradioprice').html(delivery_price2xx+" руб.");
    })
}
delivery_price();
if ($('.cart_form_radios').length) {
    check_radios();
    $('.cart_form_radios input').change(function () {
        check_radios();
    })
}
if ($('.cart_form_radios_delivery').length) {

    $('.result_price_delivery').html('0')
    // delivery_price()

    $('.cart_form_radios_delivery input').change(function () {
        delivery_price()
        // alert($(this).data('payprice'))
    })
}


if ($('.product_body_left_mins, .popup_outer .popup_cart_cart_inner').length) {
    $('.product_body_left_mins, .popup_outer .popup_cart_cart_inner').niceScroll({
        cursorcolor: "#c1c1c1",
        cursorwidth: "7px",
        autohidemode: false,
    });

}



$('.usertabs .tab.deferred .incart_list.button').click(function(){
    $(this).closest('.min-md').remove()
})
$('.deffer_close').click(function(){
    torem = $(this).closest('.min-md');
    $.post('/system/deldeferr','id='+$(this).data('itemid'),function(data){
        torem.remove();
        if($('.min-md').length == 0){
            $('.deffernone').addClass('active')
            $('.defferallremove').removeClass('active')
        }
    })
})
$('.defferallremove').click(function(){

    $.post('/system/deldeferr_rall','',function(){
        $('.min-md').remove();
        $('.deffernone').addClass('active')
        $('.defferallremove').removeClass('active')
    });
})
/*Плавный переход между страницами*/
jQuery('a').click(function (e) {
    if (this.hasAttribute('data-fancybox'))
        return;
    href = $(this).attr('href');
    // document.location.href=href;
    trg = $(this).attr('target');
    if (href != "#" && href != "#chars" && href != "##" && trg != "_blank" && href.indexOf("@") == '-1') {
        e.preventDefault();
        jQuery(".preloader").show(0);
        jQuery('body').removeClass('ohid_vert');
        jQuery('body').addClass('ohid');
        document.location.href = href;
    }
})

$('.row.product_tabs .tabs_header a').click(function (e) {
    e.preventDefault();

    var id = $(this).attr('href'),
        top = $(id).offset().top;
    $('body,html').animate({scrollTop: top}, 700);

})


/*програзка вторых изображений*/
var simages = [];
jQuery('.product_list_cont_img').each(function () {
    simages.push(jQuery(this).data('second_img'))
})

function preloadImages(arguments) {
    for (var i = 0; i < arguments.length; i++) {
        new Image().src = arguments[i];
        jQuery('.preloadimage').append('<img src="' + arguments[i] + '"/>')
    }
}

preloadImages(simages);


$('#singup_form, #login_form, #remember_form').submit(function () {
    jQuery('#obj').show()
})

$('.haveacc').click(function () {
    $.pp_open('popup_enter')
})
function pp_text(text2){
	$('.pp_text .pp_text_text').html(text2);
}
/*Прелоадер для аякса*/
function drassy_callback(type, data) {
    console.log(type)
    console.log(data)

    switch (type) {

        case 'Запрос на добавление в корзину':     // добавление в корзину
            jQuery('.debug').show()
            jQuery('#obj').show()
            break;

        case 'Ответ на добавление в корзину':    // ответ добавления в корзину
            jQuery('.debug').hide()
            jQuery('.popup_addprod').show()
            jQuery('.popup_outer').show()
            jQuery('#obj').hide()
            jQuery('.cart_top_body>div').removeClass('hidden')
            jQuery('.cart_top_empty').addClass('hidden')
            //alert(data.message)
            break;

        case 'отложить товар':
            jQuery('.debug').show()
            jQuery('#obj').show()
            break;

        case 'товар отложен':
            jQuery('.debug').hide()
            jQuery('.popup_addfav').show()
            jQuery('.popup_outer').show()
            jQuery('#obj').hide()
            break;


        case 'Открытие корзины, запрос контента':      // открытие корзины
            jQuery('.popupempty').hide();
            jQuery('.popuppreloader').show();
            break;

        case 'Ответ на запрос контента корзины', 'Удаление товара из корзины. ответ':      // ответ открытие корзины
            check_empty_cart(data.content);
            if (data.totalCount == 0) {
                jQuery('.cart_top_body>div').addClass('hidden')
                jQuery('.cart_top_empty').removeClass('hidden')

                if (jQuery('.page_cart').length) {
                    jQuery('.page_cart_body').html('Корзина пуста');
                }
            }
            checkdelivery()

            break;

        case 'Ответ на запрос контента корзины':
            check_empty_cart(data.content);
            break;

        case 'login_form_remember_response':
            if (data.error) {
                message_show('Аккаунта с таким адресом в системе не найдено', 'red');
            }
            else {
                if (data == 'ok') {
                    message_show('Новый пароль был отправлен на Вашу почту.', 'blue');
                    $('#tabs_enter > ul > li:nth-child(1)').click()
                }
            }
            jQuery('#obj').hide()
            break;

        case 'login_form_submit_response':
            if (data.error) {
                message_show('Введен неверный email или пароль');
            }
            jQuery('#obj').hide()
            break;


        case 'singup_form_submit_response':
            if (data.ok == undefined) {
                lastdatames = '';
                if (data.content.email != undefined) {
                    if (data.content.email == "Email is not a valid email address.") {
                        lastdatames += "Введите корректный email адрес";
                    }
                    else {
                        lastdatames += data.content.email;
                    }
                    lastdatames += "<br>";
                }
                if (data.content.password != undefined) {
                    if (data.content.password == "Password should contain at least 4 characters.") {
                        lastdatames += "Пароль должен состоять из 4-х и более символов";
                    }
                    else if (data.content.password == 'Password must be equal to "Confirm".') {
                        lastdatames += "Пароли не совпадают. Повторите ввод";
                    }
                }
                message_show(lastdatames);
            }
            else {
                message_show("Спасибо за регистрацию", 'blue');
            }

            jQuery('#obj').hide()
            break;


        default:
        //alert( 'Я таких значений не знаю' );
    }


}


var timout;

function message_show(mes, color) {
    if (color == 'red') {
        $('.wrong_pass').css('border-top', '3px solid #b6001e')
    }
    if (color == 'blue') {
        $('.wrong_pass').css('border-top', '3px solid #3165ac')
    }
    if (color == 'green') {
        $('.wrong_pass').css('border-top', '3px solid #425002')
    }
    if (color == 'orange') {
        $('.wrong_pass').css('border-top', '3px solid #ffa700')
    }
    if (color == 'black') {
        $('.wrong_pass').css('border-top', '3px solid black')
    }
    if (color == 'yellow') {
        $('.wrong_pass').css('border-top', '3px solid yellow')
    }
    $('.wrong_pass').html(mes)
    if (timout) {
        clearTimeout(timout)
    }
    $('.wrong_pass').slideDown(130);
    timout = setTimeout(function () {
        $('.wrong_pass').slideUp(130);
    }, 2500)
}


function check_empty_cart(data) {
    if (data == '') {
        jQuery('.popupempty').show();
    }
    else {
        jQuery('.popupempty').hide();
    }
    setTimeout(function () {
        $('body').find('.color_picker select').styler({
            'selectSmartPositioning': false,
        });
    }, 50)
    setTimeout(function () {
        jQuery('.popuppreloader').hide();
    }, 100)

}


if ($('#obj').length > 0) {
    /* курсор за мышью */
    window.addEventListener('load', function () {
        var O = document.getElementById('obj'),
            X = 0,
            Y = 0, mouseX = 0, mouseY = 0; //надо  объявлять X/Y здесь, т.к они используются за пределами замыкания обработчика
        window.addEventListener('mousemove', function (ev) {
            ev = window.event || ev; //если window.event определен, то это IE<9, поддерживаем
            X = ev.pageX;
            Y = ev.pageY;
        });

        function move() { //зачем аргумент ?
            var p = 'px';
            //console.log(X,Y);
            O.style.left = X + p;
            O.style.top = Y + p;

            setTimeout(move, 100);
        }

        move();

    });
}

$('.popup_inner>div').append('<div class="mypopsclose">x</div>')
$('.popup_inner>div').append('<div class="wrong_pass"></div>')


if ($('.product_tabs_header').length) {
    startleft = $('.product_tabs_header.tabs_header ul li:first-of-type').offset().left;
    tabs_to_top = $('.product_tabs_header').offset().top;
    win_height = $(window).height();


    var link = $('.product_tabs_header');

    var offset = link.offset();
    var top = offset.top;
    var left = offset.left;
    var bottom = $(window).height() - link.height();
    bottom = offset.top - bottom;

    if ($(window).scrollTop() < bottom || $(window).scrollTop() > tabs_to_top) {
        if (tabs_to_top > win_height) {
            $('.product_tabs_header .product_tabs_header_inner').addClass('tabsfixed')
        }

        if (tabs_to_top < $(window).scrollTop()) {
            $('.product_tabs_header .product_tabs_header_inner').addClass('tabsfixed_top')
        }
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() + win_height - 53 > tabs_to_top) {
            $('.product_tabs_header .product_tabs_header_inner').removeClass('tabsfixed')
        }
        else {
            $('.product_tabs_header .product_tabs_header_inner').addClass('tabsfixed')
        }


        if (tabs_to_top > $(window).scrollTop()) {
            $('.product_tabs_header .product_tabs_header_inner').removeClass('tabsfixed_top')
        }
        else {
            $('.product_tabs_header .product_tabs_header_inner').addClass('tabsfixed_top')
        }

    })


    // $('.tabsfixed ul, .tabsfixed_top ul').css('left', startleft)
}


if ($(".chck").length) {
    $(".chck").checkboxradio();
}


if ($('.tabledropdown').length) {

    $('.tabledropdown tr:nth-child(odd)').click(function () {
        hidetabstable()
        if ($(this).next().outerHeight() < 2) {
            $(this).next().children('td').css('margin-top', '0px');
            $(this).addClass('active')
        }


    })

    function hidetabstable() {
        $('.tabledropdown>tbody>tr').each(function () {
            $(this).removeClass('active')
            if (parseInt($(this).index()) % 2 != 0) {
                htt = $(this).children('td').outerHeight();
                htt = parseInt(htt);
                $(this).children('td').css('margin-top', '-' + htt + 'px');
            }
        })
    }

    hidetabstable()


}
$('.header_phone_call').click(function () {
    $.pp_open('return_call')
})


$('.change_photo_account').click(function () {
    $('#user-photo').click();
})

$('.add_rev_form form').on('beforeSubmit',function(e){
		e.preventDefault();
		e.stopPropagation();
		action = $(this).attr('action')
		data = $(this).serialize();
		$('.add_rev_form').slideUp();
		$.post(action,data,function(){
			$.pp_open('pp_text');
			pp_text("Ваше сообщение принято и будет опубликовано после модерации.");
			
		})
		return false;	
	})	
    jQuery(".preloader").hide(0);
    setTimeout(function () {
        jQuery('body').addClass('ohid_vert')
    }, 200)
/*даем доп класс квадратным картинкам в плитках*/
$(window).on('load', function () {
    if ($('.products_container').length) {


           
			$('.preload_image_list').removeClass('preload_image_list')

            $('.products_container_outer').hover(function () {
                old_img = $(this).find('.product_list_cont_img').data().first_img;
                new_img = $(this).find('.product_list_cont_img').data().second_img;
				if(old_img == new_img){
					return false;
				}
                $(this).find('.product_list_cont_img').css('background-image', 'url(' + new_img + ')')
                if ($(this).find('.product_list_cont_img').attr('data-sq2') == 'true') {
                    $(this).find('.product_list_cont_img').addClass('square')
                }
            }, function () {
                $(this).find('.product_list_cont_img').css('background-image', 'url(' + old_img + ')')
                if ($(this).find('.product_list_cont_img').attr('data-sq1') != 'true') {
                    $(this).find('.product_list_cont_img').removeClass('square')
                }
            });



    }


	
	


    if ($('.tabledropdown').length) {
        $('.tabledropdown>tbody>tr>td').css('transition', 'margin-top 0.5s ease 0s')
    }





    if ($('.paginator li a').length) {
        $('.paginator li a').each(function () {
            href = $(this).attr('href');
            href = href.replace("%2F", "/")
            b2 = href.replace("%2F", "/");
            $(this).attr('href', b2)
        })
    }


    $(".field-order-email .help-block").bind( 'DOMSubtreeModified',function(){ // отслеживаем изменение содержимого блока 2
        if($(this).html() == 'У Вас уже есть личный кабинет'){
            $('.popup_enter_top ul li').first().click()
            $.pp_open('popup_enter')
            email = $('.field-order-email #order-email').val();
            $('.form_enter .emailenter').val(email)
            $('.popup_enter').addClass('entercart')
        }
    });


    $('.return_link').click(function(){$.pp_open('return')})
    $('.sotrud_link').click(function(){$.pp_open('sotrud')})





})

$('.filter_body>span').click(function(){
	
	
	if($(this).next('div').is(':visible')){
		$(this).next('div').hide()
	}
	else{
		$('.filter_body>div').hide()
		$(this).next('div').show()
	}
	
})
$('.filter_body input').styler()
$(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $(".filter_body>span, .filter_body>div"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			$('.filter_body>div').hide() // скрываем его
		}
	});
$('.filter_body>span.active').each(function(){
	thiss = $(this).parent('.filter_body').children('div').find('input[type=checkbox]:checked').first();
	addFilters(thiss)
})	
$('.filter_body>div input[type=checkbox]').change(function(){
	thiss = $(this);
	addFilters(thiss)
})	

function addFilters(thiss){
	count = thiss.parent('.jq-checkbox').parent('label').parent('p').parent('div').find('input[type=checkbox]:checked').length;
	div = thiss.parent('.jq-checkbox').parent('label').parent('p').parent('div').parent('.filter_body').children('span');
		if(count == 0){
		div.removeClass('active')
	}
	else if(count > 0){
		div.find('.doublename_text').html(thiss.parent('.jq-checkbox').parent('label').parent('p').parent('div').find('input[type=checkbox]:checked').first().parent('.jq-checkbox').parent('label').children('span').text())
		div.addClass('active');
	}
	if(count == 1){
		div.find('span.else').hide();
	}
	if(count > 1){
		div.find('span.else span').html(count-1);
		div.find('span.else').show();
	}
}
$('.buttonsloaas button').click(function(){
	jQuery(".preloader").show(0);	
})
$('.buttonsloaas .button2').click(function(){
	$('.filter_body').find('input[type=checkbox]').prop('checked', false).trigger('refresh');
	$('#filters-form').submit()
})
$('.filter_body>div').niceScroll({
        cursorcolor: "#c1c1c1",
        cursorwidth: "7px",
        autohidemode: false,
    });
	
	$('.filter_body .imgcross').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).closest('.filter_body').children('div').find('input[type=checkbox]').prop('checked', false).trigger('refresh');
		div = $(this).closest('.filter_body').children('span');
		div.find('span.else').hide();
		$('.filter_body>div').hide();
		div.removeClass('active')
	})
	
	

