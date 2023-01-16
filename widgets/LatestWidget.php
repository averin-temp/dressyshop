<?php

namespace app\widgets;

use app\models\Product;
use yii\bootstrap\Widget;

class LatestWidget extends Widget
{
    public function run()
    {
        $query = Product::find()
            ->joinWith('model')
            ->select('product.*, model.added')
            ->orderBy('model.added DESC')
            ->groupBy('model_id')
            ->limit(4);

        $data = $query->all();
        return $this->render('latest' , [ 'data' => $data ] );
    }
}