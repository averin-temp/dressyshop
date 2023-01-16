<?php
namespace app\modules\users;

use Yii;

class UsersModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Users',
            'ru' => 'Пользователи',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}