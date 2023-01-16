<?php
namespace app\modules\reviews;

use Yii;
use yii\easyii\components\Module;

class ReviewsModule extends Module
{
    public $defaultRoute = 'reviews';

    public static $installConfig = [
        'title' => [
            'en' => 'Reviews',
            'ru' => 'Отзывы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}