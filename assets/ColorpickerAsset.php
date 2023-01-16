<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;


class ColorpickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "libs/picker/css/jPicker-1.1.6.css",
        "libs/picker/jPicker.css"
    ];
    public $js = [
        'libs/picker/jpicker-1.1.6.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}