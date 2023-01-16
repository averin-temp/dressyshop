<?php

namespace app\widgets;

use Yii;
use yii\bootstrap\Widget;

class EnterForm extends Widget
{

    public function run()
    {
        if(!Yii::$app->user->isGuest)
            return '';

        return $this->render("popup_enter", [  ] );
    }
}