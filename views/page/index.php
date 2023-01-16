<?php
use yii\widgets\ActiveForm;
use app\models\Guestbook;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

?>

<main>
    <div class="page textpages">
        <div class="container">
            <?php
            switch ($page->id) {
                case 41: ?>
                    <h1>Контакты</h1>
                    <div class="conts_left">
                        <?= $page->content; ?>
                    </div>
                    <div class="conts_right">
                        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Af7406783e6acb4b3f25682f9c904272042539af3c70877a6292207a32e69f135&amp;lang=ru_RU&amp;scroll=true&amp;source=constructor"
                                width="100%" height="282" frameborder="0"></iframe>
                    </div>
                    <?php
                    break;
				case 26: ?>
					<?= $page->content; ?>
					<div class="product_reviews_header">
					<br>
						<a href="##" class="add_rev" style="    width: 210px;">Отправить сообщение</a>
						<div class="add_rev_form guestform" >
							
							<?php 
							$question = new Guestbook;
							$form = ActiveForm::begin(['action' => ['/system/guestadd'] ]) ?>
							<?= $form->field($question, 'name', ['inputOptions' => ['required'=>'required','placeholder' => 'Ваше имя', 'class' => '']])->label(false); ?>
							<?= $form->field($question, 'email', ['inputOptions' => ['type'=>'email','placeholder' => 'Ваш email', 'class' => '']])->label(false); ?>
							<?= $form->field($question, 'model_id')->hiddenInput()->label(false); ?>
							<?php /* $form->field($question, 'email', ['inputOptions' => [ 'placeholder' => 'Ваш e-mail' , 'class' => '' ]])->label(false); */ ?>
							<?= $form->field($question, 'content')->textarea(['required'=>'required','placeholder' => 'Текст вопроса', 'cols' => 30, 'rows' => 10, 'class' => ''])->label(false); ?>
							<?= Html::submitButton("Отправить", ['class' => 'button']) ?>
							<?php ActiveForm::end() ?>
						</div>
					</div>
					<?php 
					$questions = Guestbook::find()->where(['!=','answer',''])->orderBy('created DESC')->all();
					if(count($questions) > 0){?>
					<div class="product_quests_body"> 
						<?php foreach($questions as $item){ ?>
						<div class="product_reviews_body_item clearfix">
							<div class="product_reviews_body_item_name"><?=$item->name?>,  <?= date_create($item->created)->Format('d-m-Y'); ?></div>
								<span style="text-decoration: underline;">Вопрос Покупателя:</span><br><div class="product_reviews_body_item_quest"><?=$item->content?></div>
								<br><span style="text-decoration: underline;">Ответ магазина:</span><br> <div class="product_reviews_body_item_text"><?=$item->answer?>
							</div>
						</div>
						<?php } ?>		
                    </div>
					<?php } ?>												
				<?php 
					break;
                case 29: ?>
                    <?= $page->content; ?>
                    <ul class="brands_in_brands clearfix">
                        <?php
                        $brands = \app\models\Brand::find()->all();
                        foreach ($brands as $item) {
                            ?>
                            <li>
                                <a class="brandssss" href="<?= \yii\helpers\Url::toRoute(['catalog/brand', 'slug' => $item->slug]) ?>">
                                    <img src="<?= $item->image ?>" alt=""/> <span><?= $item->name ?></span>
                                </a>
                            </li>
                        <?php }
                        ?>
                    </ul>
                    <?php
                    break;
                case 27:
                    ?>
                    <?= $page->content; ?>
                    <div class="rewspagecont">
                        <?php
                        $i = 0;

		
                        $reviews = \app\models\Reviews::find()->where(['avalible' => 1])->orderBy('created DESC')->all(); 
						// $reviews = new ActiveDataProvider([
							// 'query' => \app\models\Reviews::find()->where(['avalible' => 1])->orderBy('created DESC')->all()
						// ]);						
						?>
                        <div class="rowotzw clearfix">
                        <?php foreach ($reviews as $review): ?>

                                <?php
                            if($i % 2 == 0 && $i != 0){echo '</div><div class="rowotzw clearfix">';}
                                $product = \app\models\Product::find()
                                    ->where(['model_id' => $review->model_id])
                                    ->One();
                                ?>
                                <div class="product_reviews_body_item clearfix">
                                    <div class="rwpagerw_img"
                                         style="background-image: url('/images/models/<?= \app\models\Image::find()->where(['model_id' => $review->model_id])->one()->filename ?>_small.jpg');">
                                        <a href="/<?= $product->model->slug ?>">

                                        </a>
                                    </div>
                                    <div class="rwpagerw_img_riga">
									<a href="/<?= $product->model->slug ?>"><?= \app\models\Model::find()->where(['id' => $review->model_id])->One()->vendorcode?></a>
                                        <div
                                                class="product_reviews_body_item_name" style="    margin-bottom: 5px;"><?= $review->name ?>   <?= date_create($review->created)->Format('d-m-Y'); ?></div>
                                        <div class="product_reviews_body_item_stars" style="    margin-bottom: 0px;">
                                            <div class="noactivestars stars<?= $review->evaluation ?>">
                                                <ul class="rewspage_rewsa">
                                                    <li><span></span></li>
                                                    <li><span></span></li>
                                                    <li><span></span></li>
                                                    <li><span></span></li>
                                                    <li><span></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div
                                                class="product_reviews_body_item_text"><?= $review->content ?></div>
                                    </div>
                                </div>
                            <?php $i++; endforeach; ?>
                        </div>
                    </div>
                    <?php
                    if (!$reviews) {
                        echo 'Отзывы пока отсутствуют';
                    }
                    ?>
                    <?php
                    break;
                default:
                    echo $page->content;
            }
            ?>


        </div>
    </div>
</main>