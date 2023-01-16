<?php

namespace app\models;

use yii\db\ActiveRecord;

class Banner extends ActiveRecord
{
    public static function tableName()
    {
        return 'banner';
    }

    function rules()
    {
        return [
            [ [ 'caption','url'], 'required', 'message' => 'Поле не должно быть пустым'],
            [ [ 'caption', 'image','url', 'parallax_image', 'class'], 'trim'],
            [['image', 'parallax_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 1, 'message' => 'Укажите изображение (jpg или png)'],
            ['enable', 'number' ],
            [['enable_parallax','order'], 'safe']
        ];
    }

}