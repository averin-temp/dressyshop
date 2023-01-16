<?php
namespace app\modules\products;

use Yii;

class ProductsModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'models';

    public static $installConfig = [
        'title' => [
            'en' => 'Products',
            'ru' => 'Товары',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}