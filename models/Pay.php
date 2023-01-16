<?php

namespace app\models;

use yii\db\ActiveRecord;

class Pay extends ActiveRecord
{

    public static function tableName()
    {
        return 'pay_methods';
    }
    public function rules()
    {
        return [
            [ ['caption', 'order','delivery', 'desc'], 'safe' ]
        ];
    }

}