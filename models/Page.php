<?php

namespace app\models;


use yii\db\ActiveRecord;

class Page extends ActiveRecord
{
    public static function tableName()
    {
        return "pages";
    }

    public function rules()
    {
        return [
            [['meta_title', 'meta_keywords', 'meta_description'], 'trim'],
            [ ['slug', 'caption'] , 'required', 'message' => 'Это поле нельзя оставлять пустым'],
            [ ['content', 'menu_id', 'menu_order'] , 'safe' ]
        ];
    }

    public function getLink()
    {
        return '/service/'.$this->slug;
    }

}