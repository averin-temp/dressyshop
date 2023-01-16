<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;


class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
    ];
    public $js = [
		"libs/jquery-ui-1.12.1.custom/jquery-ui.min.js",
        '/web/libs/tmc/tinymce.min.js'
    ];
    public $depends = [
	
        'yii\easyii\assets\AdminAsset'
    ];
}