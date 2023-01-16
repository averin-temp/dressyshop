<?php

namespace app\models;

use yii\db\ActiveRecord;


class Color extends ActiveRecord
{
    function formName()
    {
        return '';
    }

    function rules()
    {
        return [
            [ [ 'name', 'code', 'order'], 'safe']
        ];
    }
	public static function getAll(){
		return self::find()->all();
	}

    public static function getAllWithAvalible($avalibleColors)
    {
        $allColors = self::find()->all();

        $avalible = array();
        foreach($avalibleColors as $color)
            $avalible[] = $color->id;

        $colors = array();
        foreach($allColors as $color) {
            $colors[ $color->id ] = [
                "name" => $color->name,
                "code" => $color->code,
                "avalible" => in_array($color->id, $avalible)
            ];
        }

        return $colors;
    }

    /**
     * Возвращает массив объектов Color из строки ID или массива
     *
     * @param string|array $colors
     * @return static[]
     */
    public static function getByIDs($colors)
    {
        if(is_string($colors))
            $colors = explode(',', $colors);

        return Color::findAll(['id' => $colors]);
    }
}