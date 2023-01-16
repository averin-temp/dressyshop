<?php
namespace app\modules\categories;

use Yii;

class CategoriesModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'categories';

    public static $installConfig = [
        'title' => [
            'en' => 'Categories',
            'ru' => 'Категории',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}