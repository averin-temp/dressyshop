<?php
namespace app\classes;

use app\models\Brand;
use app\models\Color;
use app\models\Filters;
use app\models\Model;
use app\models\Product;
use app\models\Property;
use app\models\Size;
use Yii;
use yii\base\Object;
use yii\db\ActiveQuery;
use app\models\PropertyType;
use yii\helpers\ArrayHelper;

/*
 * Формат фильтров в сессии:
 *
 * session['filters'] = [
 *      filter_id => [
 *          property_id =>  options = [                опции поиска.  По типу фильтра определяется
 *                              1 => '' ,              метод фильтрации, а тут данные для него
 *                              2 => '',                К примеру тип у фильтра - диапазон значений.
 *                              3 => '',                тогда тут содержится начальное и конечное значение.
 *                              ...  ]                  если тип - начилие, массив пуст.
 *                   ]
 * ]
 *
 */


class QueryFilter extends Object
{
    public $options = [];
    public $avalible;
    public $filters;
    /** @var ActiveQuery   */
    public $query;

    public function apply($query)
    {
        $this->query = $query;

        foreach($this->filters as $filter){

            if(!isset($this->options[$filter->id])) continue;

            $options = $this->options[$filter->id];
            $properties = $filter->properties;
            $type = $filter->type;

            foreach($properties as $property){

                $exists = isset($options[$property->id]);
                $avalible = isset($this->avalible[$property->id]);

                if($exists && $avalible) {

                    $params = $options[$property->id];

                    if($property->format == PropertyType::IS_COLOR)
                    	$this->addColorParams($params);
                    elseif($property->format == PropertyType::IS_BRAND)
	                    $this->addBrandParams($params);
                    elseif($property->format == PropertyType::IS_SIZE)
	                    $this->addSizeParams($params);
                    else
                        $this->addQueryParams($type, $property->id, $params);
                }
            }
        }
    }


    public function addSizeParams($params)
    {
    	$namesQuery = Size::find()->select('name')->where(['id' => $params]);
	    $sizesQuery = Size::find()->select('id')->where(['name' => $namesQuery]);
    	$modelsQuery = Product::find()->select('model_id')->where(['size_id' => $sizesQuery ]);
	    $this->query->andWhere([ 'model_id' => $modelsQuery ]);
    }

	public function addColorParams($params)
	{
		$modelsQuery = Product::find()->select('model_id')->where(['color_id' => $params ]);
		$this->query->andWhere([ 'model_id' => $modelsQuery ]);
	}

	public function addBrandParams($params)
	{
		$modelsQuery = Model::find()->select('id')->where(['brand_id' => $params ]);
		$this->query->andWhere([ 'model_id' => $modelsQuery ]);
	}

    public function addQueryParams($type, $option, $params)
    {

        $query = Property::find()->select('model_id')->where([ 'type_id' => $option ]);
		
        if($type == Filters::TYPE_RANGE)
        {
            $from = isset($params[0]) && is_numeric($params[0]) ? $params[0]: '';
            $to = isset($params[1]) && is_numeric($params[1]) ? $params[1]: '';

            /*
             * select model.id as id from model join badges where model.badge_id = badges.id and (model.final_price - model.final_price*badges.discount*0.01) > 400;
             */


            if($from !== ''  )  $query->andWhere([ '>=', 'value', $from ]);
            if($to !== '') $query->andWhere([ '<=', 'value', $to ]);

            if($from === '' && $to === '') return;
        }

        if($type == Filters::TYPE_EXIST)
        {
            if(empty($params[0])) return;
        }

        if($type == Filters::TYPE_UNION)
        {
            $query->andWhere(['value' => $params]);
        }

//die(var_dump($query->all()));
        $this->query->andWhere([ 'model_id' => ArrayHelper::getColumn($query->all(), 'model_id') ]);
		
    }


    public function save()
    {
        Yii::$app->session->set('filters', $this->options);
    }

    /**
     * Ищет все свойства, которые есть в моделях,
     * определенных запросом $modelsQuery
     *
     * @param ActiveQuery $modelsQuery
     * @return array Массив ID свойств, индексированный
     */
    public static function avalibleProperties($modelsQuery)
    {
        $query = Property::find()->select('type_id')
            ->where(['model_id' => $modelsQuery])
            ->groupBy('type_id');

        $avalible_properties =
            PropertyType::find()
                ->select('id')
                ->where([ 'id' => $query ])     // системные свойства всегда присутствуют
	            ->orWhere(['format' => [PropertyType::IS_BRAND, PropertyType::IS_COLOR, PropertyType::IS_SIZE]])
                ->indexBy('id')->asArray()->all();

        return $avalible_properties;


    }

}