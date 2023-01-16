<?php
namespace app\models;

use Yii;
use yii\base\Model;

class ApplyCode extends Model
{
    public $captcha;
    public $code;

    function rules()
    {
        return [
            ['captcha', 'captcha'],
            [['captcha'], 'required'],
            ['code', 'trim'],
            ['code', function ($attribute, $params) {
                if($promocode = Promocode::findOne([ 'code' => $this->$attribute ])) {
                    $this->addError($attribute, 'Несуществующий код');
                }
            }]
        ];
    }

    public function Apply(Order $order)
    {
        $promocode = Promocode::findOne([ 'code' => $this->code ]);
        $order->promocode = $promocode->id;
    }

}