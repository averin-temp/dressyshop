<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LightAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "libs/bootstrap/bootstrap-grid-3.3.1.min.css",
        "libs/font-awesome-4.2.0/css/font-awesome.min.css",
        "css/fonts.css",
        "libs/select/jquery.formstyler.css",
        "libs/jquery-ui-1.12.1.custom/jquery-ui.css",
        "css/style.css",
        "css/safe.css",
        "css/media.css"
    ];
    public $js = [
        "libs/jquery-ui-1.12.1.custom/jquery-ui.min.js",
        "libs/select/jquery.formstyler.min.js",
        "js/mask.js",
        "libs/nice/jquery.nicescroll.min.js",
        "libs/jquery-columnlist-master/jquery.columnlist.min.js",
        
        "js/common.js",
        "js/safe.js"
    ];
    public $depends = [
        'app\assets\IEAsset',
        'yii\web\JqueryAsset'
    ];
}