<?php

namespace app\models;

use yii\db\ActiveRecord;

class SizeRange extends ActiveRecord
{
    public static function tableName()
    {
        return 'size_ranges';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['order', 'safe']
        ];
    }

    public function getSizes()
    {
        return $this->hasMany(Size::className(),[ 'parent_range' => 'id' ]);
    }
}