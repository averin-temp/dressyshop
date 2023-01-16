<?php
namespace app\modules\guestbook;

use Yii;

class GuestbookModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'guestbook';

    public static $installConfig = [
        'title' => [
            'en' => 'Questions',
            'ru' => 'Гостевая книга',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}