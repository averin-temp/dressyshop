<?php

namespace app\widgets;

use app\models\Brand;
use yii\bootstrap\Widget;

class Brands extends Widget
{
    public function run()
    {
        $brands = Brand::find()->all();
        return $this->render('brands' , [ 'brands' => $brands ] );
    }
}