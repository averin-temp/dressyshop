<?php

namespace app\assets;

use yii\web\AssetBundle;

class JcropAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "js/jquery.Jcrop.js",
    ];
    public $css = [
        "css/jquery.Jcrop.css",
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
