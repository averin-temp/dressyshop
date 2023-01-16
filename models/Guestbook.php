<?php

namespace app\models;

use yii\db\ActiveRecord;


class Guestbook extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_ANSWER = 'answer';


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_ANSWER] = ['answer', 'approved'];
        $scenarios[static::SCENARIO_CREATE] = ['name', 'content', 'email', 'created'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['name', 'content', 'email'], 'trim'],
            ['created', 'default', 'value' => date_format(date_create(),'Y-m-d H:i:s')],

            [['answer'], 'safe'],
            ['approved', 'number']
        ];
    }

    public function getDate() {
        return date_create_from_format("Y-m-d H:i:s",$this->created)->format('d-m-Y');
    }

    public function getTime() {
        return date_create_from_format("Y-m-d H:i:s",$this->created)->format('h:i');
    }

    public function getModel()
    {
        return $this->hasOne(Model::className(), ['id' => 'model_id'] );
    }

}