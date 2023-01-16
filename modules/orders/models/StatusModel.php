<?php

namespace app\modules\orders\models;

use yii\base\Model;

class StatusModel extends Model
{
    public $status;
    public $status_add;

    function rules()
    {
        return [
            [['status','status_add'], 'safe']
        ];
    }
}