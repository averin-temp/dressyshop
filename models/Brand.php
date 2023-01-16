<?php

namespace app\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    const SCENARIO_AJAX = 'scenario-ajax';

    public function rules()
    {
        return [
            [ [ 'name','slug' ], 'required', 'message' => 'Поле не должно быть пустым'],
            [ [ 'name' ], 'trim'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 1, 'message' => 'Укажите изображение (jpg или png)'],
            ['slug', 'validateSlug' , 'on' => static::SCENARIO_AJAX ],
            ['order','safe']
        ];
    }

    public function getLink()
    {
        return "Brand::getLink(): LINK ON BRAND";
    }

    public function  validateSlug()
    {
        $model = Brand::findOne(['slug' => $this->slug]);

        if ($model && ( $this->id != $model->id )) {
            $this->addError('slug', 'Такой slug уже существует');
        }
    }

}