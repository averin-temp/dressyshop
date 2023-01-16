<?php

namespace app\models;

use yii\db\ActiveRecord;


class Status extends ActiveRecord
{

    public function rules()
    {
        return [
            [ 'name', 'required', 'message' => 'Это обязательное поле' ],
            ['order', 'safe']
        ];
    }



}