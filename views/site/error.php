<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Страница не найдена';
?>
<main class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-error">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <p>
                       Запрашиваемая Вами страница отсутствует на сервере.
                    </p>
                    <p>
                        Если Вы уверены, что страница должна отображаться корректно, обратитесь к администраторам сайта.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

