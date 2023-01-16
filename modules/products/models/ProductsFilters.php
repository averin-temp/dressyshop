<?php
namespace app\modules\products\models;

use Yii;
use yii\web\Request;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ProductsFilters extends Model
{
    public $priceFrom;
    public $priceTo;
    public $brand;
    public $vendorcode;
    public $fromDate;
    public $category;
    public $toDate;

    public $activeFilters = [];

    public function rules()
    {
        return [
            [ ['priceFrom', 'priceTo', 'brand','vendorcode', 'fromDate', 'category', 'toDate'] , 'safe' ],
            [ ['priceFrom', 'priceTo'] , 'number' ],
            [ [ 'fromDate' , 'toDate' ] ,
                function($attribute, $params) {
                    if(!$res = date_create_from_format("Y-m-d",$this->$attribute)){
                        $this->addError($attribute, 'Неверный формат даты');
                    }
                }
            ],
        ];
    }

    public static function label($param)
    {
        $labels = [
            'price' => 'Цена',
            'category' => 'Категория',
            'vendorcode' => 'Артикул',
            'created' => 'Время добавления'
        ];

        return $labels[$param];
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

    public function getFilters()
    {
        return [

            'price' =>
                [['priceFrom', 'priceTo'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ '>=', 'final_price' , $this->priceFrom ]);
                        $query->andFilterWhere([ '<=', 'final_price' , $this->priceTo ]);
                    }],
            'category' =>
                [['category'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere([ 'category_id' => $this->category ]);
                    }],
            'vendorcode' =>
                [['vendorcode'],
                    function(ActiveQuery $query)
                    {
                        $query->andFilterWhere(['like', 'vendorcode', $this->vendorcode ]);
                    }],
            'created' =>
                [['fromDate', 'toDate'],
                    function(ActiveQuery $query)
                    {
                        if(!empty($this->fromDate)) {
                            $fromDatetime = date_create_from_format("Y-m-d", $this->fromDate);
                            $query->andFilterWhere([ '>=', 'added' , $fromDatetime->format('Y-m-d H:i:s') ]);
                        }
                        if(!empty($this->fromDate)) {
                            $toDatetime = date_create_from_format("Y-m-d", $this->toDate);
                            $query->andFilterWhere([ '<=', 'added' , $toDatetime->format('Y-m-d H:i:s') ]);
                        }


                    }],
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

        unset($params['ProductsFilters'], $params['filter']);

        $params[0] = 'index';

        return $params;

    }


}