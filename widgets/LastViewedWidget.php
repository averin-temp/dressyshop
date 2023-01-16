<?php

namespace app\widgets;

use app\classes\LastViewed;
use app\models\Product;
use yii\bootstrap\Widget;

class LastViewedWidget extends Widget
{
    /**
     * @var array
     */
    public $idList;

    public function run()
    {
        $products = LastViewed::getProducts();
        return $this->render( 'lastviewed', [ 'products' => $products ] );
    }
}