<?php
namespace app\modules\pages;

use Yii;

class PagesModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'page';

    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}