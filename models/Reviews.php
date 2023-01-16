<?php

namespace app\models;

use yii\db\ActiveRecord;

class Reviews extends ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_EDIT] = ['avalible'];
        $scenarios[static::SCENARIO_CREATE] = ['name', 'content', 'email','evaluation', 'created'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['name', 'content', 'email'], 'trim'],
            [['avalible', 'evaluation'], 'safe'],
            ['created', 'default', 'value' => date_format(date_create(),'Y-m-d H:i:s')],
        ];
    }

    public function getDate()
    {
        return date_create_from_format("Y-m-d H:i:s",$this->created)->format('h:i:s');
    }

    public function getTime()
    {
        return date_create_from_format("Y-m-d H:i:s",$this->created)->format('h:i:s');
    }

    public function getModel()
    {
        return $this->hasOne(Model::className(), ['id' => 'model_id']);
    }

}