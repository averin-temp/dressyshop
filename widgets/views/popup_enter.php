<?php
use yii\helpers\Url;
use app\classes\socials\Vk;
use app\classes\socials\Facebook;
use app\classes\socials\Odnoklassniki;

?>
<div class="popup_enter">
    <div class="popup_enter_top tabs_header" id="tabs_enter">
        <ul class="clearfix">
            <li class="active"><a href="##">Вход</a></li>
            <li class="lireg"><a href="##">регистрация</a></li>
        </ul>
    </div>
    <div class="popup_enter_bottom tabs_body" id="tabs_enter">
        <div class="tab active form_enter">
            <form id="login_form" action="<?=\yii\helpers\Url::to(['site/login']) ?>" method="post">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <input class="emailenter" required name="email" type="email" placeholder="E-mail">
                <input required name="password" type="password" placeholder="Пароль">
                <label for="checkbox-1">Запомнить меня</label>
                <input type="checkbox" name="checkbox-1" id="checkbox-1" class="chck">
                <input type="submit" value="Войти" class="button">
                <a class="rem_pass" href="##">Напомнить пароль</a>
            </form>
            <!--<div class="form_enter_middle">
                <span>Войти через соц.сети:</span>
                <div class="socsenter">
                    <a href="<?= (new Vk())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/vk.png')  ?>"" alt=""></a>
                    <a href="<?= (new Facebook())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/fb.png') ?>" alt=""></a>
                    <a href="<?= (new Odnoklassniki())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/ok.png')  ?>" alt=""></a>
                </div>
            </div>-->
            <div class="form_enter_bottom">
                <span>Не зарегистрированы?</span>
                <a href="##" onclick="$('.lireg').click()">Регистрация</a>
            </div>
			
        </div>
        <div class="tab form_enter">
			
            <form id="singup_form" action="<?=\yii\helpers\Url::to(['site/signup']) ?>" method="post">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
               <!-- <input name="username" type="text" placeholder="Логин">
                <input name="password" type="password" placeholder="Пароль">
                <input name="confirm" type="password" placeholder="Повторите пароль"> -->
                <input required name="email" type="email" placeholder="E-mail">
				<input required name="password" type="password" placeholder="Пароль">
                <input required name="confirm" type="password" placeholder="Повторите пароль">
                <input type="submit" value="Зарегистрироваться" class="button">				
                <span title="Вы получите дополнительные скидки за постоянные покупки" class="whyreg">Зачем?</span>
            </form>
            <div class="signup-error"></div>
            <!--<div class="form_enter_middle">
                <span>Войти через соц.сети:</span>
                <div class="socsenter">
                    <a href="<?= (new Vk())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/vk.png')  ?>" alt=""></a>
                    <a href="<?= (new Facebook())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/fb.png') ?>" alt=""></a>
                    <a href="<?= (new Odnoklassniki())->getAuthLink() ?>"><img src="<?= Url::to('@web/img/icons/ok.png')  ?>" alt=""></a>
                </div>
            </div>-->
        </div>
		
		<div class="tab form_remember form_enter">
			
            <form id="remember_form" action="<?=\yii\helpers\Url::to(['site/remember']) ?>" method="post">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <label>Введите Ваш email для восстановления пароля</label>
				<br><br>
                <input required name="email" type="email" placeholder="E-mail">
                <input type="submit" value="Отправить мне пароль" class="button">		                
            </form>
        </div>
		
		
    </div>
</div>

<?php
$script = <<< JS

/*----------------------------------------------------------------
    Форма входа
-------------------------------------------------------------- */

$('#singup_form').submit(function(e){
    e.preventDefault();
    var data = $(this).serialize();
    drassy_callback('singup_form_submit', data);
    $.post("/site/signup", data, function(response){
        drassy_callback('singup_form_submit_response', response);
            response.ok ? $.preloader_redirect("/ajax/account/index?success") : console.log();
        }, "json")
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    });
});

$('#login_form').submit(function(e){
    e.preventDefault();
    var data = $(this).serialize();
    drassy_callback('login_form_submit', data);
    $.post("/site/login", data, function(response){
        drassy_callback('login_form_submit_response', response);
            response.ok ? $.preloader_redirect(location.href) : console.log(response);
        }, "json")
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    });
});


$('#remember_form').submit(function(e){
    e.preventDefault();
    var data = $(this).serialize();
    drassy_callback('remember_form_submit', data);
    $.post("/site/remember", data, function(response){
        drassy_callback('login_form_remember_response', response);
            response.ok ? $.preloader_redirect(location.href) : console.log(response);
        }, "json")
    .fail(function(jqXHR, textStatus, error){
        console.log("Ошибка : " + error);
    });
});


$('.header_cart_enter a#login-form').click(function(){
    $.pp_open('popup_enter');
});

/*----------------------------------------------------------------
    Конец. Форма входа
-------------------------------------------------------------- */
JS;

$this->registerJS($script);