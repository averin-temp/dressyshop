<?php

namespace app\assets;

use yii\web\AssetBundle;

class ZoomslAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "libs/zoomsl-3.0.js"
    ];
    public $css = [];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
