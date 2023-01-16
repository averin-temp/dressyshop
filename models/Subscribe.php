<?php
namespace app\models;


use yii\db\ActiveRecord;


class Subscribe extends ActiveRecord
{
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
            ['active', 'default', 'value' => 1]
        ];
    }

    public static function tableName()
    {
        return 'subscribe_email';
    }


}