<?php

namespace app\modules\orders\models;

use app\models\User;
use Yii;
use app\models\Order;
use yii\base\Model;
use app\modules\settings\models\Settings;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Html;

class OrdersTableSettings extends Model
{
    public $columns;

    public function __construct()
    {
        $this->columns = $this->default;
    }

    public function load($params)
    {
        if(empty($params['columns']) || !is_array($params['columns']))
            return false;

        $order = 1;
        $active = $params['columns'];
        foreach($this->columns as $name => &$data)
            if($data['active'] = in_array($name, $active))
                $data['order'] = $order++;

        return true;
    }

    public function save()
    {
        $data = [];
        foreach($this->columns as $name => $options) {
            $data[$name]['active'] = $options['active'];
            $data[$name]['order'] = $options['order'];
        }
        $data = json_encode($data);
        Settings::updateAll(['table_orders' => $data ],['id' => 1]);
    }
	
	



    public static function get()
    {
        $settings = Settings::findOne(1);
        $settings = $settings->table_orders;

        $instance = new self();

        if(!empty($settings)) {
            $settings = json_decode($settings, true);
            foreach($instance->columns as $name => &$data) {
                $data['active'] = $settings[$name]['active'];
                $data['order'] = $settings[$name]['order'];
            }
        }

        return $instance;
    }

    public function getDefault()
    {
        return [
            'id' => [
                'label' => 'номер заказа',
                'order' => 1,
                'active' => true,
                'dbrelation' => 'id',
                'relation' => 'id',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a($data->id, Url::to(['edit', 'id' => $data->id]));
                }
            ],
            'created' => [
                'label' => 'заказ создан',
                'order' => 1,
                'active' => true,
                'dbrelation' => 'created',
                'relation' => 'created',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->created, Url::to(['edit', 'id' => $data->id]));
                }
            ],
			'lastname' => [
                'label' => 'Имя',
                'order' => 1,
                'active' => true,
                'dbrelation' => 'lastname',
                'relation' => 'lastname',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->lastname, Url::to(['edit', 'id' => $data->id]));
                }
            ],
            'status' => [
                'label' => 'статус',
                'order' => 1,
                'active' => true,
                'dbrelation' => 'status_id',
                'relation' => 'status.caption',
                'format' => 'raw',
                'value' => function (Order $data) {
                    return Html::a($data->status->name, Url::to(['edit', 'id' => $data->id]));
                }
            ],
            'fullcost' => [
                'label' => 'Сумма',
                'order' => 1,
                'active' => true,
                'dbrelation' => 'fullcost',
                'relation' => 'fullcost',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->fullcost, Url::to(['edit', 'id' => $data->id]));
                }
            ],
            'delivery' => [
                'label' => 'Доставка',
                'order' => 9,
                'active' => true,
                'dbrelation' => 'delivery.caption',
                'relation' => 'delivery.caption',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->delivery->caption), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],            
            'pay' => [
                'label' => 'Оплата',
                'order' => 9,
                'active' => true,
                'dbrelation' => 'pay_methods.caption',
                'relation' => 'pay_methods.caption',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->pay_methods->caption), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],

        ];


    }

    public function getGridColumns()
    {
        ArrayHelper::multisort($this->columns, 'order', SORT_DESC);

        $columns = [];
        foreach($this->columns as $column => $params)
        {
            if($params['active']) {
                $data = [
                    'attribute' => $params['relation'],
                    'format' => $params['format'],
                    'label' => $params['label']
                ];

                if(isset($params['value']))
                    $data['value'] = $params['value'];

                $columns[] = $data;
            }
        }

        return $columns;
    }

    public function getSort()
    {
        $sort = [];
        foreach($this->columns as $column => $params)
        {
            $sort['attributes'][$params['relation']] = [
                'asc' => [ $params['dbrelation'] => SORT_ASC ],
                'desc' => [ $params['dbrelation'] => SORT_DESC ],
            ];
        }
        return $sort;
    }

    public function getInactiveColumns()
    {
        $inactiveColumns = [];
        foreach($this->columns as $name => $data)
        {
            if($data['active'] === false)
                $inactiveColumns[$name] = $data;
        }
        return $inactiveColumns;
    }

    public function getActiveColumns()
    {
        $activeColumns = [];
        foreach($this->columns as $name => $data)
        {
            if($data['active'] === true)
                $activeColumns[$name] = $data;
        }
        return $activeColumns;
    }

}