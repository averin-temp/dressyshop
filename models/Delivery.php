<?php


namespace app\models;


use yii\db\ActiveRecord;

class Delivery extends ActiveRecord
{

    public function rules()
    {
        return [
            [ ['freesumm', 'price', 'caption', 'region', 'products_count'] , 'required' ],
            [['order','desc'],'safe']
        ];
    }

}