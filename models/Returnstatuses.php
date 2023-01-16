<?php

namespace app\models;

use app\modules\settings\models\Settings;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use Yii;


/**
 * sizes, colors - хранятся в таблице в виде json строки
 */
class Returnstatuses extends ActiveRecord
{
    public function rules()
    {
        return [
            [ 'order', 'safe' ],
            [ 'name', 'required' ]
        ];
    }

    public static function tableName()
    {
        return 'return_statuses';
    }

}