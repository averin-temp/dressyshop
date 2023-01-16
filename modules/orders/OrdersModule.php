<?php
namespace app\modules\orders;

use Yii;

class OrdersModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'orders';

    public static $installConfig = [
        'title' => [
            'en' => 'Orders',
            'ru' => 'Заказы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}