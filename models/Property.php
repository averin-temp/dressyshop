<?php

namespace app\models;

use yii\db\ActiveRecord;

class Property extends ActiveRecord
{

    public function rules()
    {
        return [
            [['model_id', 'type_id'], 'required'],
            ['order', 'safe']
        ];
    }

    public function getModel()
    {
        return $this->hasOne(Model::className(),['id' => 'model_id']);
    }


    public function getType()
    {
        return $this->hasOne(PropertyType::className(),['id' => 'type_id']);
    }


}