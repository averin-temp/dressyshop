<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IEAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['condition' => 'lte IE9'];
    public $js = [
        "libs/html5shiv/es5-shim.min.js",
        "libs/html5shiv/html5shiv.min.js",
        "libs/html5shiv/html5shiv-printshiv.min.js",
        "libs/respond/respond.min.js",
    ];

    public $depends = [
    ];
}
