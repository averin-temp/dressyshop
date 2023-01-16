<?php

namespace app\widgets;

class Thumbnail extends \yii\bootstrap\Widget
{
    public $product;

    public function run()
    {
        return $this->render("thumbnail", [ "product" => $this->product ] );
    }

}