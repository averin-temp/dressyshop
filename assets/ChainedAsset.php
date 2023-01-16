<?php

namespace app\assets;

use yii\web\AssetBundle;


class ChainedAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/jquery.chained.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}