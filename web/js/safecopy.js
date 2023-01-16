/**
 * Created by hust on 26.06.2017.
 */


/*Плавный переход между страницами*/
jQuery('a').click(function(e){

    if(this.hasAttribute('data-fancybox'))
        return ;

    e.preventDefault();
    href = $(this).attr('href');
    document.location.href=href;
    trg = $(this).attr('target');
    console.log(trg)
    if(href != "#" && href != "##" && trg != "_blank"){
        jQuery(".preloader").show(0);
        jQuery('body').removeClass('ohid_vert');
        jQuery('body').addClass('ohid');
        document.location.href=href;
    }

})







/*програзка вторых изображений*/
var simages = [];
jQuery('.product_list_cont_img').each(function(){
    simages.push(jQuery(this).data('second_img'))
})
function preloadImages() {
    for (var i = 0; i < arguments.length; i++) {
        new Image().src = arguments[i];
    }
}
preloadImages(simages);






/*Прелоадер для аякса*/
function drassy_callback(type,data){
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
            //alert(data.message)
            break;

        case 'Открытие корзины, запрос контента':      // открытие корзины
            jQuery('.popuppreloader').show();
            break;

        case 'Ответ на запрос контента корзины':      // открытие корзины
            jQuery('.popuppreloader').hide();
            break;

        case 'Ответ на запрос контента корзины','Удаление товара из корзины. ответ':      // ответ открытие корзины
            check_empty_cart(data.content);
            break;







        default:
        //alert( 'Я таких значений не знаю' );
    }
}


function check_empty_cart(data){
    if(data == ''){
        jQuery('.popupempty').show();
    }
    else{
        jQuery('.popupempty').hide();
    }
    jQuery('.popuppreloader').hide();
}















/* курсор за мышью */
window.addEventListener('load', function () {
    var O = document.getElementById('obj'),
        X = 0,
        Y = 0,mouseX=0,mouseY=0; //надо  объявлять X/Y здесь, т.к они используются за пределами замыкания обработчика
    window.addEventListener('mousemove', function (ev) {
        ev = window.event || ev; //если window.event определен, то это IE<9, поддерживаем
        X=ev.pageX;
        Y=ev.pageY;
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





















