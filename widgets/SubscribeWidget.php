<?php

namespace app\widgets;

use app\models\Subscribe;
use yii\bootstrap\Widget;

class SubscribeWidget extends Widget
{
    public function run()
    {
        $model = new Subscribe();
        return $this->render('subscribe', [ 'model' => $model] );
    }
}