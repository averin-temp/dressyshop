<?php
namespace app\modules\gallery;

use Yii;

class GalleryModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'gallery';

    public static $installConfig = [
        'title' => [
            'en' => 'Questions',
            'ru' => 'Гостевая книга',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}