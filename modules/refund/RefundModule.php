<?php
namespace yii\easyii\modules\page;

use Yii;

class RefundModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Возврат',
            'ru' => 'Возврат',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}