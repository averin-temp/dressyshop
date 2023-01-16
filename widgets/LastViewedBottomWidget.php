<?php

namespace app\widgets;
use yii\bootstrap\Widget;
use app\classes\LastViewed;

/**
 * Created by PhpStorm.
 * User: hust
 * Date: 16.06.2017
 * Time: 5:46
 */

class LastViewedBottomWidget extends Widget
{
    function run()
    {
        $products = LastViewed::getProducts();

        if(!count($products))
            return '';

        return $this->render('last_viewed_bottom_widget', ['products' => $products]);
    }
}