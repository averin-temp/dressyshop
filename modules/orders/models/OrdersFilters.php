<?php

namespace app\modules\orders\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\web\Request;
use yii\helpers\ArrayHelper;

class OrdersFilters extends Model
{
    public $fullcostFrom;
    public $fullcostTo;
    public $fullcostDot;
    public $fromDate;
    public $toDate;
    public $order_number;
    public $lastname;
    public $firstname;
    public $patronymic;
    public $status_id;
    public $delivery_id;
    public $pay_method;
    public $regions;
    public $adress;
    public $city;
    public $phone;
    public $admin_comment;
    public $user_comment;
    public $articul;

    public $activeFilters = [];

    public function rules()
    {
        return [
            [ ['regions','user_comment','articul','phone','admin_comment','adress','city','fullcostFrom', 'fullcostTo', 'fullcostDot', 'fromDate', 'toDate', 'order_number', 'lastname', 'firstname' , 'patronymic', 'status_id','delivery_id','pay_method'] , 'safe' ],
        ];
    }

    public function load($data)
    {
        $this->activeFilters = isset($data['filter']) ? $data['filter'] : [] ;
        return parent::load($data);
    }

    public function applyFilters($query)
    {
        foreach($this->filters as $filter => $config) {
            if(key_exists($filter, $this->activeFilters))
                $this->activeFilters[$filter] = $this->filter($query, $config);
        }
    }

    public static function label($param)
    {
        $labels = [
            'fullcost' => 'Сумма',
            'created' => 'Дата создания',
            'order_number' => '№ Заказа',
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'patronymic' => 'Отчество',
            'status_id' => 'Статус',
            'delivery_id' => 'Способ доставки',
            'pay_method' => 'Способ оплаты',
            'regions' => 'Регион',
            'adress' => 'Адрес',
            'city' => 'Город',
            'phone' => 'Телефон',
            'admin_comment' => 'Комментарий администратора',
            'user_comment' => 'Комментарий покупателя',
            'articul' => 'Артикул',
        ];

        return $labels[$param];
    }

    public function getFilters()
    {
        return [

           
            'created' =>
                [['fromDate', 'toDate'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '>=', 'created' , $this->fromDate ]);
                        $query->andFilterWhere([ '<=', 'created' , $this->toDate." 23:59:59" ]);
                    }],
			'order_number' =>
                [['order_number'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order_number' , $this->order_number ]);
                    }],		
			'lastname' =>
                [['lastname'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.lastname' , $this->lastname ]);
                    }],							
			'firstname' =>
                [['firstname'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.firstname' , $this->firstname ]);
                    }],	
			'patronymic' =>
                [['patronymic'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.patronymic' , $this->patronymic ]);
                    }],	
					
			'status_id' =>
                [['status_id'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'status_id' , $this->status_id ]);
                    }],	
			
			'fullcost' =>
                [['fullcostFrom', 'fullcostTo', 'fullcostDot'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '>=', 'fullcost' , $this->fullcostFrom ]);
                        $query->andFilterWhere([ '<=', 'fullcost' , $this->fullcostTo ]);
                        $query->andFilterWhere([ 'like', 'fullcost' , $this->fullcostDot ]);
                    }],
			'delivery_id' =>
                [['delivery_id'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '=', 'delivery_id' , $this->delivery_id ]);
                    }],	
					
			'pay_method' =>
                [['pay_method'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '=', 'pay_method' , $this->pay_method ]);
                    }],			
			'regions' =>
                [['regions'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '=', 'order.region' , $this->regions ]);
                    }],			
			'adress' =>
                [['adress'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.adress' , $this->adress ]);
                    }],					
			'city' =>
                [['city'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.city' , $this->city ]);
                    }],						
			'phone' =>
                [['phone'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.phone' , $this->phone ]);
                    }],							
			'admin_comment' =>
                [['admin_comment'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.admin_comment' , $this->admin_comment ]);
                    }],									
			'user_comment' =>
                [['user_comment'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.user_comment' , $this->user_comment ]);
                    }],											
			/*'articul' =>
                [['articul'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'like', 'order.articul' , $this->articul ]);
                    }],		*/			
        ];
    }


    private function filter($query, $config)
    {
        foreach ($config[0] as $var)
            if($this->hasErrors($var))  return null;

        $config[1]($query);

        return true;
    }


    public function getFormParams()
    {
        $request = Yii::$app->getRequest();
        $params = $request instanceof Request ? $request->getQueryParams() : [];

        unset($params['OrdersFilters'], $params['filter']);

        $params[0] = 'index';

        return $params;

    }



}