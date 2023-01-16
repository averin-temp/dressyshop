<?php
namespace app\modules\meta;

use Yii;

class MetaModule extends \yii\easyii\components\Module
{
    public $defaultRoute = "common";

    public static $installConfig = [
        'title' => [
            'en' => 'Meta',
            'ru' => 'META',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}