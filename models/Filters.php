<?php
namespace app\models;

use yii\db\ActiveRecord;

class Filters extends ActiveRecord
{
    const TYPE_RANGE = 1;
    const TYPE_EXIST = 2;
    const TYPE_UNION = 3;

    public function rules()
    {
        return [
            [['type', 'name'], 'required', 'message' => 'это обязательное поле'],
            ['name', 'trim'],
            [['property_types', 'parent_filter'], 'safe']
        ];
    }

    public function beforeSave($insert)
    {
        $this->property_types = json_encode($this->property_types);

        if (!parent::beforeSave($insert)) {
            return false;
        }
        return true;
    }

    public static function getTypes()
    {
        return [
            1 => 'диапазон значений',
            2 => 'наличие значения',
            3 => 'значение из набора',
        ];
    }

    public function getTypeLabel()
    {
        $types = static::getTypes();
        return isset($types[$this->type]) ? $types[$this->type] : 'undefined';
    }

    public function getProperties()
    {
        $ids = $this->property_types;

        if(!is_array($ids))
        {
            $ids = json_decode($ids);
            if($ids == null) return [];
        }

        return PropertyType::findAll(['id' => $ids]);
    }

}

