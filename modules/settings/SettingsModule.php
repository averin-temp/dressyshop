<?php
namespace app\modules\settings;

use Yii;

class SettingsModule extends \yii\easyii\components\Module
{
    public $defaultRoute = "common";

    public static $installConfig = [
        'title' => [
            'en' => 'Settings',
            'ru' => 'Настройки',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}