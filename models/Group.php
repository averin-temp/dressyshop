<?php

namespace app\models;

use yii\db\ActiveRecord;


class Group extends ActiveRecord
{
    function rules()
    {
        return [
            [['name', 'discount'], 'required', 'message' => 'заполните поле'],
            ['discount', 'number', 'message' => 'Неверный формат, введите число']
        ];
    }

    function attributeLabels()
    {
        return [
            'name' => 'Название группы',
            'discount' => 'Скидка группы',
        ];
    }

    function getUsers()
    {
        return $this->hasMany(User::className(), ['group_id' => 'id']);
    }

    function getDiscount($group_id)
    {
        return Group::find()->where(['id'=>$group_id])->one()->discount;
    }
}