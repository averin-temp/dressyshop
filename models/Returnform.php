<?php

namespace app\models;

use app\modules\settings\models\Settings;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use Yii;
use app\models\Returnstatuses;


/**
 * sizes, colors - хранятся в таблице в виде json строки
 */
class Returnform extends ActiveRecord
{
    public function rules()
    {
        return [
            [ ['name', 'email', 'date', 'articulsize', 'why', 'type', 'order_number','status','comment'], 'safe' ]
        ];
    }

    public static function tableName()
    {
        return 'return';
    }
	
	public function getStatus_name()
    {
        return $this->hasOne(Returnstatuses::className(), ['id' => 'status']);
    }

}