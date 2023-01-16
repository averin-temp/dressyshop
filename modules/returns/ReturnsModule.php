<?php
namespace app\modules\returns;

use Yii;
use yii\easyii\components\Module;

class ReturnsModule extends Module
{
    public $defaultRoute = 'returns';

    public static $installConfig = [
        'title' => [
            'en' => 'Returns',
            'ru' => 'Возврат товара',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}