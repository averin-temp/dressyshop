<?php

namespace app\assets;

use yii\web\AssetBundle;

class OwlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "libs/owl-carousel/owl.carousel.min.js"
    ];
    public $css = [
        'libs/owl-carousel/owl.carousel.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
