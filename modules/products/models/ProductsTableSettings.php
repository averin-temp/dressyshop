<?php
namespace app\modules\products\models;

use app\models\Model;
use yii\base\Object;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\Settings;
use yii\helpers\Url;

class ProductsTableSettings extends Object
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
        Settings::updateAll(['table_products' => $data ],['id' => 1]);
    }

    public static function get()
    {
        $settings = Settings::findOne(1);
        $settings = $settings->table_products;

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
            'vendorcode' => [
                'label' => 'Артикул',
                'order' => 2,
                'active' => true,
                'dbrelation' => 'vendorcode',
                'relation' => 'vendorcode',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a(Html::encode($data->vendorcode), Url::to(['edit', 'id' => $data->id ])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],
            'lastmodifed' => [
                'label' => 'Изменено',
                'order' => 5,
                'active' => true,
                'dbrelation' => 'lastmodifed',
                'relation' => 'lastmodifed',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a(Html::encode($data->lastmodifed), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],
            'price' => [
                'label' => 'Цена',
                'order' => 7,
                'active' => true,
                'dbrelation' => 'final_price',
                'relation' => 'final_price',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a(Html::encode($data->final_price), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],
            'brand' => [
                'label' => 'Бренд',
                'order' => 8,
                'active' => true,
                'dbrelation' => 'brand.name',
                'relation' => 'brand.name',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a(Html::encode($data->brand->name), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],
            'sort' => [
                'label' => 'Сортировка',
                'order' => 9,
                'active' => true,
                'dbrelation' => 'sort',
                'relation' => 'sort',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->sort), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ],
            'name' => [
                'label' => 'Имя товара',
                'order' => 10,
                'active' => true,
                'dbrelation' => 'name',
                'relation' => 'name',
                'format' => 'raw',
                'value' => function (Model $data) {
                    return Html::a(Html::encode($data->name), Url::to(['edit', 'id' => $data->id])); // $data['name'] для массивов, например, при использовании SqlDataProvider.
                }
            ]
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