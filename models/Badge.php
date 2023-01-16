<?php

namespace app\models;

use yii\db\ActiveRecord;

class Badge extends ActiveRecord
{
    public static function tableName()
    {
        return 'badges';
    }

    function rules()
    {
        return [
            [['text','class','css'], 'safe'],
            [ [ 'name', 'discount'], 'required'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1]
        ];
    }

}