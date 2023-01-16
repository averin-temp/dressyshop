<?php
namespace app\modules\questions;

use Yii;

class QuestionsModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'questions';

    public static $installConfig = [
        'title' => [
            'en' => 'Questions',
            'ru' => 'Вопросы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}