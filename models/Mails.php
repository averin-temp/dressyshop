<?php

namespace app\models;

use yii\db\ActiveRecord;

class Mails extends ActiveRecord
{

    public function rules()
    {
        return [
            [ ['name'] , 'required' ],
            [ ['content','zone','content','subject','from'], 'safe' ]
        ];
    }
}