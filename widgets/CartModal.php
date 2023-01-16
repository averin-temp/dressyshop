<?php

namespace app\widgets;

use yii\bootstrap\Widget;

class CartModal extends Widget
{

    public function run()
    {
        return $this->render("popup_cart");
    }
}