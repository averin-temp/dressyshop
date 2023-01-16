<?php

namespace app\assets;

use yii\web\AssetBundle;

class FancyBox extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "js/jquery.fancybox.js"
    ];
    public $css = [
        'css/jquery.fancybox.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
