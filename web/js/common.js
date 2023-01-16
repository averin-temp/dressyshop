// изменить путь
// history.pushState(null, null, '/anypath')
// if(document.location.pathname == '/anypath'){
//     jQuery('body').hide(0)
//     document.location.href='/catalog/7402';
// }
if(jQuery('#order-phone').length || jQuery('#user-phone').length ) {
    window.addEventListener("DOMContentLoaded", function () {
        function setCursorPosition(pos, elem) {
            elem.focus();
            if (elem.setSelectionRange) elem.setSelectionRange(pos, pos);
            else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd("character", pos);
                range.moveStart("character", pos);
                range.select()
            }
        }

        function mask(event) {
            var matrix = "+7 (___) ___ ____",
                i = 0,
                def = matrix.replace(/\D/g, ""),
                val = this.value.replace(/\D/g, "");
            if (def.length >= val.length) val = def;
            this.value = matrix.replace(/./g, function (a) {
                return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? "" : a
            });
            if (event.type == "blur") {
                if (this.value.length == 2) this.value = ""
            } else setCursorPosition(this.value.length, this)
        };
        var input2 = document.querySelector("#order-phone");
        if(input2){
            input2.addEventListener("input", mask, false);
            input2.addEventListener("focus", mask, false);
            input2.addEventListener("blur", mask, false);

        }
        var input = document.querySelector("#user-phone");
        if(input){
            input.addEventListener("input", mask, false);
            input.addEventListener("focus", mask, false);
            input.addEventListener("blur", mask, false);

        }
    });
}

jQuery(document).ready(function ($) {
    (function ($) {

	
	$('.add_rev').click(function () {
    $(this).parent('div').children('.add_rev_form').slideToggle();
});

        if($('.cart_form').length){
            checkdelivery();
            $('.cart_form_radios_delivery input').change(function(){
                $('.cart_form_radios_pay input:radio').prop('checked', false).trigger('refresh');
                $('.cart_form_radios_pay').hide(0);
                $('.cart_form_radios_pay>div').hide(0);
                curdelId = $(this).val()
                $('.cart_form_radios_pay>div').each(function(){
                    string = $(this).find('input').data('deliverys');
                    if(string.indexOf(curdelId) > -1){
                        $('.cart_form_radios_pay').show(0);
                        $('.selecdeliverytype').hide(0);
                        $(this).show(0)
                    }
                })
                check_radios();
            })

            $('#order-region').change(checkdelivery)
        }

 




        $(document).tooltip({
            show: {effect: "blind", duration: 1},
            hide: {effect: "blind", duration: 1}
        });


        /*---------------------------------------------------
         MOBILE
         ---------------------------------------------------*/

        menuiter = 0;

        function vals() {
            h100 = $(window).height();
            w100 = $(window).width();
            $('.mob_navigation').height(h100)
            $('.general_container').width(w100)
            $('.mob_navigation').width(w100 / 100 * 75)
        }

        function togglemenu() {
            w100 = $(window).width();
            h100 = $(window).height();
            left = parseInt($('.main_container').css('left'));
            if (left == 0) {
                $('.main_container').css('left', w100 / 100 * 75)
                $('.burger_cont').addClass('active')
                $('.main_container').addClass('ohid')
                $('.main_container').css('height', h100)
            }
            else {
                $('.main_container').css('left', 0)
                $('.burger_cont').removeClass('active')
                $('.main_container').removeClass('ohid')
                $('.main_container').css('height', 'auto')
            }
        }

        function mob_header() {
            mobheader_action = $(this).data('mobheader_action');
            if (menuiter == 0) {
                $('.header_mobile_middle_' + mobheader_action).show(0);
                setTimeout(function () {
                    $('.header_mobile_middle').addClass('active');
                }, 100)
                menuiter = 1;
            }
            else {
                $('.header_mobile_middle').removeClass('active');
                setTimeout(function () {
                    $('.header_mobile_middle>div').hide(0);
                }, 200)
                menuiter = 0;
            }
        }


        $('.header_mob_right a').click(mob_header);

        $('.header_mob_left').click(togglemenu)


        /*---------------------------------------------------
         Табы
         ---------------------------------------------------*/

        $('.tabs_header li').click(function () {
            tid = $(this).closest('.tabs_header').attr('id');
            $('.tabs_header#' + tid + ' li').removeClass('active');
            $(this).addClass('active');
            $('.tabs_body#' + tid + ' .tab').removeClass('active');
            $('.tabs_body#' + tid + ' .tab').eq($(this).index()).addClass('active');
        });

        $('.rem_pass').click(function () {
            $('.popup_enter').find('.active').removeClass('active')
            $('.form_remember').addClass('active')
        })


        /*---------------------------------------------------
         Parallax
         ---------------------------------------------------*/

        var elems = $([]);

        $.prlx = function (selector) {
            elems = elems.add(selector);
            core_parallax();
        };

        function core_parallax() {
            elems.each(function () {
                var speed = $(this).data('speed');
                var yPos = -($(window).scrollTop() / speed);
                var coords = 'center ' + yPos + 'px';
                $(this).css({backgroundPosition: coords})
            });
        }

        $(window).scroll(function () {
            core_parallax();
        });


        $.prlx('div.prlx1');


        /*---------------------------------------------------
         Redirect
         ---------------------------------------------------*/

        $.preloader_redirect = function (href) {
            jQuery(".preloader").show(0, function () {
                if (href == location.href) location.reload();
                location.href = href;
            });
            jQuery('body').addClass('ohid');
            jQuery('body').removeClass('ohid_vert');
        }


        /*---------------------------------------------------
         Popup
         ---------------------------------------------------*/

        $.pp_open = function (pp) {
            $('.popup_outer, .popup_inner>div.' + pp).show();
            if (pp == 'popup_menu')
                $('.popup_bg').addClass('white');
        };

        $.pp_close = function () {
            $('.popup_outer, .popup_inner>div').hide();
            $('.popup_bg').removeClass('white');
            $('.popup_inner form')[0].reset();
            $('.add_rev_form form')[0].reset();
            $('.tnathkpp').hide(0);

            if($('.entercart').length > 0){

                $('.form_enter .emailenter').val('')
                $('.field-order-email #order-email').val('');
                $('.field-order-email #order-email').change();
                $('.popup_enter').removeClass('entercart')
            }
        };

        $('.popup_bg').click(function () {
            $.pp_close();
        });

        $('body').on('click', '.mypopsclose', function () {
            $.pp_close();
        })

        $('.header_bot .container').click(function () {
            //$.popup('popup_menu')
        });

        /*
         $.widget("custom.iconselectmenu", $.ui.selectmenu, {
         _renderItem: function (ul, item) {
         var li = $("<li>"),
         wrapper = $("<div>", {text: item.label});

         if (item.disabled) {
         li.addClass("ui-state-disabled");
         }

         $("<span>", {
         style: item.element.attr("data-style"),
         "class": "ui-icon " + item.element.attr("data-class")
         })
         .appendTo(wrapper);

         return li.append(wrapper).appendTo(ul);

         }
         });

         */


        function stars_hover() {
            var stars_cur;
            $('#stars_cont li').hover(function () {
                stars_cur = $(this).closest('#stars_cont').attr('class');
                stars_count = $(this).index() + 1;
                $('#stars_cont').attr('class', '');
                $('#stars_cont').addClass('stars' + stars_count);
            }, function () {
                $('#stars_cont').attr('class', '');
                $('#stars_cont').addClass(stars_cur);
            })
            $('#stars_cont li').click(function () {
                stars_cur = $('#stars_cont').attr('class');
                $('#stars_cont').attr('class', '');
                $('#stars_cont').addClass(stars_cur);
            })
        }


        vals();

        $(window).resize(vals);

        /*$(document).tooltip({
         position: {
         my:'center top',
         at:'center bottom'
         }
         });*/


        stars_hover();


        /*---------------------------------------------------
         Корзина
         ---------------------------------------------------*/

        sumCounter = 0;

        /*
         * При нажатии на любую ссылку с атрибутом data-product
         * посылается ajax запрос на добавление товара с id = data-product
         */
        var timersizesli;
        var timersizesspan;
        $("a[data-product]").click(function () {

            sizeLength = $(this).parent('.prod_body_bottom_top').parent('.prod_body_bottom').parent('.prod_body').children('.prod_body_middle').find('.prod_sizes li.active').length;
            sizeOn = $(this).parent('.prod_body_bottom_top').parent('.prod_body_bottom').parent('.prod_body').children('.prod_body_middle').find('.prod_sizes').length;
            if (sizeLength == 0 && sizeOn == 1) {
                if (timersizesli) {
                    clearInterval(timersizesli)
                }
                if (timersizesspan) {
                    clearInterval(timersizesspan)
                }
                //alert('Выберите размер')
                $('.prod_sizes li').addClass('sizno')
                $('.selectsizepan').css('opacity', '1')
                timersizesli = setTimeout(function () {
                    $('.prod_sizes li').removeClass('sizno')
                }, 350)
                timersizesspan = setTimeout(function () {
                    $('.selectsizepan').css('opacity', '0')
                }, 1000)
            }
            else {

                var params = {id: $(this).attr('data-product')};

                var $container = $(this).closest('.products_container_inner');
                if ($container.length) {
                    var chosen = $container.find('.product_list_cont_size_inner li.active.selected');
                    if (chosen.length == 0)
                        chosen = $container.find('.product_list_cont_size_inner li.active').first();

                    if (chosen.length != 0)
                        params.size = chosen.attr('data-size-id');
                }

                var $container = $(this).closest('#product_container');
                if ($container.length) {
                    var chosen = $container.find('.prod_sizes li.active');

                    params.size = chosen.attr('data-size-id');
                }

                /****************************************** */
                drassy_callback('Запрос на добавление в корзину', params);

                $.post('/cart/ajax_put', params, function (data) {

                    /****************************************** */
                    drassy_callback('Ответ на добавление в корзину', data);

                    $('.cart-products-count, .popup_cart_cart_inner_item_info_bot span.total_price').html(data.totalCount + ' ');
                    $('.cart-products-price, .popup_cart_cart_inner_item_info_bot span.result_price').html(data.totalPrice);
                }, 'json')
                    .fail(function (jqXHR, textStatus, error) {
                        console.log(error);
                    });
            }
        });


        $('body').find('.color_picker select').styler({
            'selectSmartPositioning': false,
        });

        $('.cart_form_radios input').styler()
       
        $('.cart_form_cheks input').styler()
        $('.return.return_call_ppp .col-md-12 input').styler()
        $('.cart_form_cheks label').click(function () {
            // alert($(this).find('.jq-checkbox.checked').length);

            check_radios();
        })


    })(jQuery);
});

