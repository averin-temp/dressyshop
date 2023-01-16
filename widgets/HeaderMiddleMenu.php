<?php

namespace app\widgets;

class HeaderMiddleMenu extends \yii\bootstrap\Widget
{
    public $items = array();

    public function run()
    {
        return $this->render("top_middle_menu", [ "list" => $this->items ] );
    }
}