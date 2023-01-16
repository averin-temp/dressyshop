<?php

namespace app\modules\orders\models;

use app\models\Order;
use yii\base\Model;

class BatchAction extends Model
{
    public $action;
    public $keys;

    public function rules()
    {
        return [
            [ ['action', 'keys'] , 'safe' ]
        ];
    }

    public function formName()
    {
        return 'actionform';
    }

    public function Execute()
    {
        if(empty($this->keys))
            return false;

        $action = 'execute'.ucfirst($this->action);

        if(method_exists($this, $action))
            return $this->$action();

        else return null;
    }

    private function executeDelete()
    {
        Order::deleteAll(['id' => $this->keys]);
        return true;
    }



}