<?php

use yii\helpers\Html;
use app\models\Menu;
use app\assets\AppAsset;
use app\widgets\EnterForm;
use app\widgets\CartModal;
use app\widgets\HeaderMiddleMenu;
use app\widgets\Search;
use app\widgets\MainMenu;
use yii\helpers\Url;
use app\classes\Cart;
use app\assets\LightAsset;
use app\classes\CatalogUrl;
use app\modules\settings\models\Settings;

LightAsset::register($this);
$this->beginPage();

if(!isset($_SERVER['HTTP_REFERER'])){
	Yii::$app->session->remove('filters');
}
?><!DOCTYPE html>
<!-- made by hedindoom http://freelance.ru/hedindoom -->
<!--[if lt IE 7]>
<html lang="<?= Yii::$app->language ?>" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>
<html lang="<?= Yii::$app->language ?>" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>
<html lang="<?= Yii::$app->language ?>" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="<?= Yii::$app->language ?>">
<!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="viewport" content="width=1300"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?= Url::to('@web/favicon.png') ?>"/>

    <?= $this->head() ?>
    <style>
        <?php
        $all_colors = \app\models\Color::find()->all();
        foreach($all_colors as $color): ?>

        <?php $color_class = mb_substr( $color->code, 1);
        $color_class = "c_".$color_class;
        $border_color = '';
        $bgmulti = '';
        if($color->code == '#ffffff'){
        $border_color = 'border: 1px solid #ccc;';
        }
        if($color->name == 'Мультиколор'){
            $bgmulti = 'background: url(/web/img/multi.jpg) no-repeat center center;background-size: contain;';
        }
        if($color->name == 'Черно-белый'){
            $bgmulti = 'background: url(/web/img/bw.png) no-repeat center center;background-size: contain;';
        }
        if($color->name == 'Черно-серый'){
            $bgmulti = 'background: url(/web/img/bg.png) no-repeat center center;background-size: contain;';
        }
         echo "
        .jq-selectbox.jqselect.".$color_class." .jq-selectbox__select-text:before,
        .jq-selectbox.jqselect .jq-selectbox__dropdown li.".$color_class.":before{
            content: '';
            display: inline-block;
            background: ".$color->code.";
            width: 20px;
            height: 20px;
            vertical-align: bottom;
            margin-right: 9px;".$border_color.$bgmulti."
        }";

        ?>

        <?php endforeach; ?>

    </style>
    <meta name="yandex-verification" content="cf4e352ab2afebde"/>
    <meta name="moscowtop100plus_ru-verification" content="858c65cd0cfc7e2ff05b245619f30f6f"/>
</head>
<body class="ohid">

<?php
//$all_colors = \app\models\Color::find()->all();
//var_dump($all_colors);
?>


<div id="obj"></div>
<?php $this->beginBody() ?>
<div class="general_container">

    <!-- mob_nav end -->

    <div class="main_container">

        <div class="preloader"></div>
        <div class="debug"></div>


        <header class="header_desctop">
            <div class="header_top has_tbl">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <a class="logo" href="<?= Url::home() ?>"><img src="<?= Url::to('@web/img/logo.png') ?>"
                                                                           alt=""></a>
                        </div>
                        <div class="col-md-4">

                            <?= HeaderMiddleMenu::widget([
                                "items" => array_merge(Menu::getMenuList(1))
                            ]) ?>

                            <?= Search::widget() ?>
                        </div>
                        <div class="col-md-2 header_phones">
                            <div class="table">
                                <div class="table_cell">
                                    <span class="header_phone"><?= Settings::get('phone1') ?></span>
                                    <!--<span class="header_label">Бесплатный звонок по России</span>-->
                                    <span class="header_phone"><?= Settings::get('phone2') ?></span></span>
                                    <a class="header_phone_call" href="##">Обратный звонок</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 header_cart">
                            <div class="header_cart">

                                <div class="header_cart_enter  <?php if (!\Yii::$app->user->isGuest) {
                                    echo 'header_cart_enter_min';
                                } ?>">
                                    <?php if (\Yii::$app->user->isGuest): ?>
                                        <a id="login-form" href="##">Вход / Регистрация</a>
                                    <?php else: ?>

                                        <span style="    color: #e64c65;"><?php if (\Yii::$app->user->identity->firstname) {
                                                echo \Yii::$app->user->identity->firstname;
                                                if (\Yii::$app->user->identity->lastname) {
                                                    echo " " . \Yii::$app->user->identity->lastname;
                                                }
                                            } else {
                                                echo \Yii::$app->user->identity->email;
                                            } ?><br></span>
                                        <a href="<?= Url::to(['site/logout']) ?>">Выход</a> / <a
                                                href="<?= Url::to(['account/index']) ?>">Личный кабинет</a>
                                    <?php endif; ?>
                                </div>


                                <div class="header_cart_cart clearfix button">
                                    <div class="table">
                                        <div class="table_cell clearfix">
                                            <div class="cart_top_icon"><img
                                                        src="<?= Url::to('@web/img/icons/cart_white.png') ?>" alt="">
                                            </div>
                                            <div class="cart_top_body">

                                                <div class=" <?php if (Cart::get()->total->count == 0) {
                                                    echo 'hidden';
                                                } ?>">
                                                    <span class="cart-products-count"><?= Cart::get()->total->count ?></span>
                                                    товаров
                                                </div>
                                                <div class=" <?php if (Cart::get()->total->count == 0) {
                                                    echo 'hidden';
                                                } ?>">на <span
                                                            class="cart-products-price"><?= Cart::get()->total->price ?></span>
                                                    руб.
                                                </div>

                                                <div class="<?php if (Cart::get()->total->count > 0) {
                                                    echo 'hidden';
                                                } ?> cart_top_empty">Корзина пуста
                                                </div>
                                            </div>
                                            <div class="cart_top_arrow">
                                                <img src="<?= Url::to('@web/img/icons/arb.png') ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= MainMenu::widget() ?>
        </header>
        <header class="header_mobile">
            <div class="header_mobile_top clearfix">
                <div class="has_tbl header_mob_left">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table">
                                    <div class="table_cell">
                                        <a href="##" class="mobile_logo">
                                            <div class="burger_cont">
                                                <div class="burger_trans"></div>
                                                <div class="burger_nottrans"></div>
                                                <div class="burger_trans"></div>
                                            </div>
                                            <img src="<?= Url::home(true) ?>img/logo_min.png" alt="" class="logomin">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="has_tbl header_mob_right">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table">
                                    <div class="table_cell">
                                        <a data-mobheader_action="search" href="##"><img
                                                    src="<?= Url::home(true) ?>img/icons/lupa_black.png"
                                                    alt=""></a>
                                        <a data-mobheader_action="cart" class="carticon_mob" href="##">
                                            <img src="<?= Url::home(true) ?>img/icons/cart_black.png" alt="">
                                            <span class="mob_incart">4</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_mobile_middle">
                <div class="header_mobile_middle_search">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <?= '' //Search::widget()   ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header_mobile_middle_cart">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                корзина
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php echo $content; ?>

        <footer>
            <div class="footer_string has_tbl">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table">
                                <div class="table_cell">
                                    <!-- SubscribeWidget -->
                                    <?= \app\widgets\SubscribeWidget::widget() ?>
                                    <!-- SubscribeWidget -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer_body">
                <div class="footer_body_top ">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3 botmen col-xs-6">
                                <h3>Сервис и поддержка</h3>
                                <ul>
                                    <?php foreach (Menu::getMenuList(2) as $caption => $link): ?>
                                        <li><a href="<?= $link ?>"><?= $caption ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="col-sm-3 botmen col-xs-6">
                                <ul>
                                    <?php foreach (Menu::getMenuList(3) as $caption => $link): ?>
                                        <li><a href="<?= $link ?>"><?= $caption ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="clearfix2"></div>
                            <div class="col-sm-3 botmen col-xs-6">
                                <h3>О компании</h3>
                                <ul>
                                    <?php foreach (Menu::getMenuList(4) as $caption => $link): ?>
                                        <li><a href="<?= $link ?>"><?= $caption ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="col-sm-3 fot_socs col-xs-6">
                                <h3>Мы в соц. сетях</h3>
                                <ul>
                                    <!-- <li><a style="    background-position: 0 center;" href="<?= Settings::get('facebook_url') ?>" target="_blank"></a></li>
                                    <li><a style="    background-position: -32px center;" href="<?= Settings::get('google_url') ?>" target="_blank"></a></li>-->
                                    <li>
                                        <a style="background: url(/web/img/insta.png) no-repeat center bottom;    background-size: 15px;"
                                           href="<?= Settings::get('skype_url') ?>" target="_blank"></a></li>
                                    <li><a style="background-position: -104px center;"
                                           href="<?= Settings::get('vk_url') ?>" target="_blank"></a></li>
                                </ul>
                                <a href="##" class="linkblog">DressyБлог</a>
                                <span>Сделано в</span>
                                <a href="http://globalcode.ru/" class="madeby" title="Создение сайтов и веб-дизайн"
                                   target="_blank">globalcode</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer_body_bot">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                © Все права защищены
                                <div class="metrika">
                                    <!-- Yandex.Metrika informer -->
                                    <a href="https://metrika.yandex.ru/stat/?id=45810990&amp;from=informer"
                                       target="_blank" rel="nofollow"><img
                                                src="https://informer.yandex.ru/informer/45810990/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
                                                style="width:61px; height:26px; border:0;" alt="Яндекс.Метрика"
                                                title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"
                                                class="ym-advanced-informer" data-cid="45810990" data-lang="ru"/></a>
                                    <!-- /Yandex.Metrika informer -->

                                    <!-- Yandex.Metrika counter -->
                                    <script type="text/javascript" > 
										(function (d, w, c) { 
										(w[c] = w[c] || []).push(function() { 
										try { 
										w.yaCounter45810990 = new Ya.Metrika({ 
										id:45810990, 
										clickmap:true, 
										trackLinks:true, 
										accurateTrackBounce:true, 
										webvisor:true 
										}); 
										} catch(e) { } 
										}); 

										var n = d.getElementsByTagName("script")[0], 
										s = d.createElement("script"), 
										f = function () { n.parentNode.insertBefore(s, n); }; 
										s.type = "text/javascript"; 
										s.async = true; 
										s.src = "https://mc.yandex.ru/metrika/watch.js"; 

										if (w.opera == "[object Opera]") { 
										d.addEventListener("DOMContentLoaded", f, false); 
										} else { f(); } 
										})(document, window, "yandex_metrika_callbacks"); 
									</script> 
                                    <!-- /Yandex.Metrika counter -->
                                </div>
                                <!--                                <a title="Раскрутка и продвижение сайтов" href="http://servis-goroda.ru/prodvizhenie" target="_blank"><img src="http://i.servis-goroda.ru/u/22/040cb61acd11e7a6dba522f7691469/-/logo3.png" style="    width: 61px; height: 26px; border: 1px solid #ababab; padding: 4px;"></a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


    </div>
</div>

<div class="popup_outer">
    <div class="popup_bg"></div>
    <div class="popup_inner">
        <div class="minppg popup_addprod">
            <div class="table">
                <div class='table_cell'>
                    <span>Товар добавлен в корзину</span>
                    <div class="popup_cart_bottom clearfix">
                        <a href="<?= Url::to(['/cart/index']) ?>" class="button">Перейти к оформлению</a>
                        <a href="##" onclick="$('.popup_bg').click()">Продолжить покупки</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="minppg popup_addfav">
            <div class="table">
                <div class='table_cell'>
                    <span>Товар добавлен в избранное</span>
                    <div class="popup_cart_bottom clearfix">
                        <a href="<?= Url::to(['/account/index']) ?>" class="button">Перейти к личный кабинет</a>
                        <a href="##" onclick="$('.popup_bg').click()">Продолжить покупки</a>
                    </div>
                </div>
            </div>
        </div>
        <?= CartModal::widget() ?>
        <?= EnterForm::widget() ?>
        <div class="return_call return_call_ppp">
            <div class="table">
                <div class='table_cell'>
                    <form class="mainpp">
                        <div class="popup_cart_bottom clearfix">
                            <span>Оставьте Ваши данные, и мы перезвоним <br>Вам в ближайшее время</span>
                            <input type="text" name='name' placeholder="Ваше имя"/>
                            <input required type="text" name='phone' placeholder="Номер телефона"/>
                            <input type="hidden" name='formtype' value="return_call"/>
                            <input type="hidden" name='admin_email' value="<?= Settings::get('admin_email') ?>"/>
                            <input class='button' type='submit'/>
                        </div>
                    </form>
                    <div class="tnathkpp">
                        <div class="table">
                            <div class='table_cell'>
                                Спасибо за обращение!<br>Ожидайте звонка нашего менеджера.

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		
		<div class="pp_text return_call_ppp">
            <div class="table">
                <div class='table_cell'>
                    <div class="tnathkpp">
                        <div class="table">
                            <div class='table_cell pp_text_text'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
        <div class="sotrud return_call_ppp">
            <div class="table">
                <div class='table_cell'>
                    <form class="mainpp">
                        <div class="popup_cart_bottom clearfix">
                            <span>Оставьте Ваши данные для <br>обработки запроса</span>
                            <select required name="typepred" id="">
                                <option value="">Тип предложения</option>
                                <option value="предлагаем продукцию">Предлагаем продукцию</option>
                                <option value="предлагаем рекламу">Предлагаем рекламу</option>
                                <option value="иное коммерческое предложение">Иное коммерческое предложение</option>
                            </select>
                            <input required type="text" name='name' placeholder="Представьтесь"/>
                            <input required type="text" name='phone' placeholder="Контактный телефон"/>
                            <input required type="text" name='email' placeholder="E-mail"/>
                            <input type="hidden" name='admin_email' value="<?= Settings::get('admin_email') ?>"/>
                            <input type="hidden" name='formtype' value="sotrud"/>
                            <textarea placeholder="Коротко о предложении с указанием координат сайта (если имеется)"
                                      name="text" id="" cols="30" rows="10"></textarea>
                            <input class='button' type='submit'/>
                        </div>
                    </form>
                    <div class="tnathkpp">
                        <div class="table">
                            <div class='table_cell'>
                                Благодарим за Ваш запрос! <br>Наши менеджеры свяжутся с Вами в течении 2-х рабочих дней
                                по данным, указанным в запросе.

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="return return_call_ppp">
            <div class="table">
                <div class='table_cell'>
                    <form class="mainpp">
                        <div class="popup_cart_bottom clearfix">
                            <span>Форма возврата товара</span>

                            <input required type="text" name="name"
                                   placeholder="ФИО полностью (на кого оформлялся заказ)">
                            <input required type="email" name="email" placeholder="Ваш e-mail">
                            <input required type="text" name="number"
                                   placeholder="Номер заказа (можно уточнить в личном кабинете)">
                            <label for="" style="display: block;
    text-align: left;
    padding: 0 20px;">Дата выкупа заказа (указана в товарном/почтовом чеке):</label>
                            <input required type="date" name="date" placeholder="">
                            <input required type="text" name="sku" placeholder="Артикул и размер возвращаемого товара">

                            <textarea required placeholder="Причина возврата" name="why" id="" cols="30"
                                      rows="10"></textarea>
                            <select required name="how" id="">
                                <option value="">Способ компенсации суммы возврата</option>
                                <option value="Cкидка на новый заказ">Cкидка на новый заказ</option>
                                <option value="Денежный перевод">Денежный перевод</option>
                            </select>

                            <div class="col-md-12">
                                <div style="display: inline-block;     text-align: left!important;">
                                    <label for="iagreeret">С правилами возврата согласен</label>
                                    <input required type="checkbox" name="iagreeret" id="iagreeret">
                                </div>
                            </div>
                            <br>
                            <br>
                            <div>
                                <input class='button' type='submit'/>
                            </div>
                        </div>
                    </form>
                    <div class="tnathkpp">
                        <div class="table">
                            <div class='table_cell'>
                                Ваше заявление на возврат принято! Номер заявления: <strong><span
                                            class="numberzaya"></span></strong><br>
                                В течении одного рабочего дня Ваше заявление будет рассмотрено. Ответ с адресом для
                                направления возврата будет отправлен на указанный в заказе
                                электронный адрес.<br>
                                Уточнить статус рассмотрения заявления или получить дополнительную информацию по
                                процедуре возврата товара
                                Вы можете связавшись с нами по почте <a href="mailto:vozvrat@dressyshop.ru">vozvrat@dressyshop.ru</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php //PopupMenu::widget() ?>
        <div class="popup_menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        123
                    </div>
                </div>
            </div>
        </div>
        <?php // END PopupMenu ?>


        <div class="popup_delivery">
            <div>
                <?= Settings::get('delivery_description') ?>
            </div>
        </div>

        <div class="popup_pay">
            <div>
                <?= Settings::get('payment_description') ?>
            </div>
        </div>

    </div>
</div>
<div class="preloadimage"/>
</div>
<div class="preloadimage2"/>
</div>
<div class="preloadimage3"/>
</div>
<?php //$size = getimagesize ("https://dressyshop.ru/images/banner/catalog.jpg"); var_dump($size);?>
<?php $this->endBody() ?>
<script>
    $('.return_call form, .sotrud form').submit(function () {
        ths = $(this)
        $.ajax({
            type: "GET",
            url: "/web/mail.php",
            data: $(this).serialize()
        }).done(function () {
            ths.parent('.table_cell').children('.tnathkpp').show(0);
        });
        return false;
    });

    $('.return form').submit(function () {
        ths = $(this)
        $.ajax({
            type: "POST",
            url: "/forms/",
            data: $(this).serialize()
        }).done(function (data) {
            $('.numberzaya').html(data)
            ths.parent('.table_cell').children('.tnathkpp').show(0);
            console.log(data);
        });
        return false;
    });
</script>

</body>
</html>
<?php $this->endPage() ?>
