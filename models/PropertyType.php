<?php

namespace app\models;

use yii\db\ActiveRecord;

class PropertyType extends ActiveRecord
{
    const IS_NUMBER = 1;
    const IS_TEXT = 2;
    const IS_UNION = 3;
    const IS_BRAND = 4;
    const IS_COLOR = 5;
    const IS_SIZE = 6;


    public static function tableName()
    {
        return 'property_type';
    }

    public static function types()
    {
        return [
            static::IS_NUMBER => 'Число',
            static::IS_TEXT => 'Текст',
            static::IS_UNION => 'Значение из набора',
            static::IS_BRAND => 'Бренд(Системное)',
            static::IS_COLOR => 'Цвет(Системное)',
            static::IS_SIZE => 'Размер(системное)'
        ];
    }


    public function rules()
    {
        return [
            [['name', 'format'], 'required'],
            ['order', 'safe']
        ];
    }

    public function getValues()
    {
	    switch($this->format){
		    case static::IS_UNION :
			    return $this->hasMany(PropertyValue::className(), ['type_id' => 'id']);
		    case static::IS_BRAND :
		    	return Brand::find()->all();
		    case static::IS_COLOR :
			    return Color::find()->all();
		    case static::IS_SIZE :
			    return Size::find()->groupBy('name')->all();
	    }
	    return [];
    }

}