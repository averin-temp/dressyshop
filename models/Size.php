<?php

namespace app\models;

use yii\db\ActiveRecord;

class Size extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'parent_range'], 'required'],
            ['name', 'trim'],
            ['parent_range', 'number'],
            ['order', 'safe'],
        ];
    }

    public function getRange()
    {
        return $this->hasOne(SIzeRange::className(),[ 'id' => 'parent_range' ]);
    }

}