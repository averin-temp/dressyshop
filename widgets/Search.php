<?php

namespace app\widgets;

use app\models\SearchForm;
use yii\base\Widget;

class Search extends Widget
{
    public function run()
    {
        return $this->render("search");
    }
}