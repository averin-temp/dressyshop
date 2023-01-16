<?php
/**
 * Created by PhpStorm.
 * User: hedindoom
 * Date: 20.10.2017
 * Time: 17:25
 */

namespace app\models;


use yii\db\ActiveRecord;

class Regions extends ActiveRecord
{

    public static function tableName()
    {
        return 'regions';
    }
    public function rules()
    {
        return [
            [ ['name', 'order'], 'safe' ]
        ];
    }

}