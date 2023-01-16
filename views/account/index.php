<?php
use app\widgets\Breads;
use yii\helpers\Url;
use app\models\Seo;
$action = $this->context->action->id;

if(!Seo::SetSeo(4)) $this->title = 'Личный кабинет';

?>
    <main>
        <div class="page account">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
						
                        <?=Breads::widget([
                            'path' => [ 'Личный кабинет' => '' ],
                            'home' => Url::to(['catalog/index'])
                        ]) ?>
                        <h1>Личный кабинет</h1>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <div class="account_nav">
                            <div class="account_nav_head">
                                <label class="account_nav_head_img" style="    background-image: url(<?php if($user->photo){echo $user->photo;}  else{ echo Url::to('@web/img/logo_min.png');} ?>);
    background-color: white;
    cursor: pointer;
<?php if(!$user->photo){echo "    background-position: center 57px;
    background-repeat: no-repeat;
    background-size: 130px;";}?>
">
                                    <input style="display: none" type="file" name="user-photo" id="user-photo">
									
                                </label>
								<span class="change_photo_account">Изменить фото</span>

                                <div class="account_nav_head_name"><?php if($user->firstname){echo $user->firstname; if($user->lastname){echo " ".$user->lastname;}} else { echo $user->email;} ?>
                                    <?php if ($userdiscount) { ?>
                                        <div class="mydisc">
                                            Текущая скидка: <span><?=$userdiscount?>%</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="account_nav_menu tabs_header" id="tab_user">
                                <ul>
                                    <li class="active" <?= ($action == 'settings' || $action == 'save') ? 'class="active"' : '' ?>><a href="##">мои данные</a></li>
                                    <li><a href="##">История моих заказов</a></li>
                                    <li><a href="##">отложенные товары</a></li>
                                   <!-- <li><a href="##">моя фотогалерея</a></li>-->
                                </ul>
                            </div>
                            <div class="account_nav_bottom">
                                <a href="<?= Url::to(['site/logout']) ?>">Выход</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tabs_body usertabs" id="tab_user">
                            <div class="account_mysettings tab<?= ($action == 'settings' || $action == 'save') ? ' active' : '' ?> active" >
                                <h2>мои данные</h2>
                                <?= $this->render('settings', ['userdiscount'=>$userdiscount, 'regions' => $regions,'model' => $user, 'delivery' => $delivery, 'payment' => $payment, 'colors' => $clrs]) ?>
                            </div>

                            <div class="tab history <?= $action == 'history' ? ' active' : '' ?>">
                                <h2>История моих заказов</h2>
                                <?= $this->render('history', [ 'orders' => $orders ]) ?>
                            </div>
                            <div class="tab deferred <?= $action == 'deferred' ? ' active' : '' ?>">
                                <h2>отложенные товары</h2>
                                <?= $this->render('deferred', ['products' => $products]) ?>
                            </div>
                            <!--<div class="tab<?= $action == 'photos' ? ' active' : '' ?>">
                                <h2>моя фотогалерея</h2>
                                <?= $this->render('photos', ['user' => $user]) ?>
                            </div>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

<?php
$user_photo_upload_url = Url::to(['upload']);
$script = <<< JS
/*--------------------------------------------------------------
    Личный кабинет
-------------------------------------------------------------- */


// загрузка картинки на сервер
$('#user-photo').change(function(){
    var file = this.files[0];
    if(!file) return;
    if(!/^image\/(jpg|png|jpeg|bmp)$/.test(file.type))
    {
        alert('Неверный формат файла');
        return;
    }
        
    var formData = new FormData();
    formData.append('user-photo', file );
    $.ajax({
        url: '$user_photo_upload_url',
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false 
    }).done(function(data){
        data.ok ? (
            $('.account_nav_head_img').css({backgroundImage: "url("+data.url+")"})
        ): console.log(data.message);
    }).fail(function(data){
        console.log(data)
    });
});


/*--------------------------------------------------------------
   Конец. Личный кабинет
-------------------------------------------------------------- */
JS;
$this->registerJS($script);