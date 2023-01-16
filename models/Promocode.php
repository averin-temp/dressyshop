<?php
/**
 * Created by PhpStorm.
 * User: hust
 * Date: 04.06.2017
 * Time: 10:49
 */

namespace app\models;

use yii\db\ActiveRecord;

class Promocode extends ActiveRecord
{
    public function rules()
    {
        return [
            [['discount', 'name', 'code'], 'required', 'message' => 'Это поле не должно быть пустым'],
            [['discount', 'code'], 'number', 'message' => 'Значение должно быть числом']
        ];
    }
}