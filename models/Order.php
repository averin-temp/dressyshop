<?php
namespace app\models;

use app\modules\orders\models\Status;
use yii\db\ActiveRecord;
use app\models\User;

class Order extends ActiveRecord
{
    function rules()
    {
        return [
            [[
                'patronymic',
                'firstname',
                'lastname',
                'phone',
                'region',
                'city',
                'email',
                'zip_code',
                'adress',
                'user_comment',
                'delivery_id',
                'promocode',
                'pay_method',
                'cost',
                'products_id',
                'delivery_price',
                'fullcost',
            ],
                'safe' ],
            [
                [
                    'patronymic',
                    'firstname',
                    'lastname',
                    'phone',
                    'region',
                    'city',
                    'email',
                    'zip_code',
                    'adress',
                    'delivery_id',
                    'pay_method',
                    'cost',
                    'products_id'
                ],
                'required', 'message' => 'Поле не должно быть пустым'
            ],
            [
                [
                    'patronymic',
                    'firstname',
                    'lastname',
                    'phone',
                    'region',
                    'city',
                    'promocode'
                ],
                'trim'
            ],
            [['email'], function($attribute, $params){
                if(\Yii::$app->user->isGuest && $founded = User::find()->where([$attribute => $this->$attribute])->One())
                    $this->addError($attribute, "У Вас уже есть личный кабинет");
            }]
        ];
    }


    /**
     * Кем заблокирован,  в таблице хранится id пользователя, кто заблокировал страницу заказа.
     * Если пусто - блокировки нет.
     *
     * @return \yii\db\ActiveQuery
     */
    function getBlocked()
    {
        return $this->hasOne(User::className(), ['id' => 'blocked_by_id']);
    }

    function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    function getProducts()
    {
        $pIDs = $this->products_id;
        if(empty($pIDs))
            $products_id = [];
        else
            $products_id = json_decode($pIDs, true);

        return Product::findAll(['id' => $products_id]);
    }


    function getPromo()
    {
        return $this->hasOne(Promocode::className(), ['id' => 'promocode']);
    }


    function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }
	function getRegiona()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region']);
    }
	function getPay()
    {
        return $this->hasOne(Pay::className(), ['id' => 'pay_method']);
    }

}