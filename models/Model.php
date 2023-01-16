<?php

namespace app\models;

use app\modules\settings\models\Settings;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use Yii;


/**
 * sizes, colors - хранятся в таблице в виде json строки
 */
class Model extends ActiveRecord
{

    const SCENARIO_SEO = 'scenario-seo';
    const SCENARIO_AJAX = 'scenario-ajax';

    /**
     * @var array ID категорий,
     * заполняется при сохранении из формы.
     */
    public $categories_id = null;

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(){

        return [
            ['badge_id', 'number', 'on' => static::SCENARIO_DEFAULT],
            [['vendorcode','purchase_price', 'bel_price','fixed_price','сalculated_price', 'slug', 'name', 'meta_title', 'meta_keywords', 'meta_description'], 'trim', 'on' => static::SCENARIO_DEFAULT],
            [[ 'brand_id','fixed_price','final_price'], 'double', 'on' => static::SCENARIO_DEFAULT],
            [['description','description', 'sort', 'delivery_details', 'payment_details', 'model_type' ], 'safe', 'on' => static::SCENARIO_DEFAULT],
            ['active', 'safe', 'on' => static::SCENARIO_DEFAULT],
            [['vendorcode', 'slug', 'name'], 'required', 'message' => "Это поле не может быть пустым", 'on' => static::SCENARIO_DEFAULT],
            [['brand_id', 'categories_id', 'sizerange'], 'required', 'message' => "Выберите значение", 'on' => static::SCENARIO_DEFAULT],
            [['purchase_price', 'final_price','bel_price', 'fixed_price'], 'number', 'message' => 'Значение должно быть числом', 'on' => static::SCENARIO_DEFAULT],
            [['purchase_price','bel_price', 'final_price', 'fixed_price'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number', 'message' => "Цена не может быть отрицательной", 'on' => static::SCENARIO_DEFAULT],

            [['meta_title', 'meta_keywords', 'meta_description'], 'trim' , 'on' => static::SCENARIO_SEO ],
            ['vendorcode', 'validateVendorcode' , 'on' => static::SCENARIO_AJAX ],
            ['slug', 'validateSlug' , 'on' => static::SCENARIO_AJAX ]

        ];
    }

    public function  validateVendorcode()
    {
        $model = Model::findOne(['vendorcode' => $this->vendorcode]);

        if ($model && ( $this->id != $model->id )) {
            $this->addError('vendorcode', 'Такой артикул уже существует');
        }
    }

    public function  validateSlug()
    {
        $model = Model::findOne(['slug' => $this->slug]);

        if ($model && ( $this->id != $model->id )) {
            $this->addError('slug', 'Такой slug уже существует');
        }
    }


    /**
     * Я использую ее для перерассчета цены по формулам
     *
     * @param $value
     */
    public function setPurchasePrice($value)
    {
		$value2 = $this->bel_price;
        $this->purchase_price = $value;
        
		
		if($value2 != ''){
			$this->bel_price = $value2;
			$value = $value2;
		}
		
        $fixed = $this->fixed_price;

        $formula = Settings::get('formula');

        if(!empty($fixed)){
            $this->final_price = $fixed;
            return;
        }

        if(empty($formula)){
            $this->final_price = $value;
            return;
        }

		
		if(!empty($this->bel_price)){
			 $formula = Settings::get('formula_bel');
		}
		
        $result = '';
        if(preg_match('/^\s*[*]\s*(\d+)\s*(([+-])\s*(\d+))?$/',$formula, $result))
        {
            $multiplier = $result[1];
            $value *= $multiplier;

            if(!(empty($result[4]) || empty($result[3])) )
            {
                $sign = $result[3];
                $number = $result[4];

                switch($sign){
                    case '+':
                        $value += $number;
                        break;
                    case '-':
                        $value -= $number;
                }
            }
        }


        $this->final_price = $value;
    }

    /**
     * После сохранения модели создаются связи с категориями.
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if($this->categories_id !== null)
            Model_to_category::saveCategories($this->id, $this->categories_id);
    }

    /**
     * Перед удалением модели, удаляются все связи с категориями
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete())
            return false;

        Model_to_category::removeModel($this->id);

        return true;
    }

    /**
     * Форма модели не использует ActiveForm
     *
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * Возвращает экземпляр Brand
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * Возвращает категории, связанные с моделью.
     *
     * @return $this
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'cat_id'] )
            ->viaTable('model_to_category', ['model_id' => 'id']);
    }




    /**
     * Возвращает объекты Image для модели
     *
     * @return static[]
     */
    public function getImages()
    {
        return Image::findAll(['model_id' => $this->id]);
    }

    /**
     * Возвращает все доступные цвета для этой модели
     *
     * @return static[]
     */
    public function getColors()
    {
        $query = Product::find()
            ->select(['color_id'])
            ->where(['model_id' => $this->id])
            ->groupBy(['color_id']);

        $colors = Color::findAll([ 'id' => $query ]);

        return $colors;
    }

    /**
     * Возвращает все продукты этой модели
     *
     * @return static[]
     */
    public function getProducts()
    {
        return Product::findAll(['model_id' => $this->id]);
    }


    /**
     * Возвращает размеры существующих в базе продуктов этой модели.
     *
     * @return static[]
     */
    public function getSizes()
    {
        $query = Product::find()
            ->select(['size_id'])
            ->where(['model_id' => $this->id])
            ->groupBy(['size_id']);

        $sizes = Size::findAll([ 'id' => $query ]);

        return $sizes;
    }


    public function getBadge()
    {
        return $this->hasOne(Badge::className(), ['id' => 'badge_id']);
    }

    public function getAdminLink()
    {
        return Url::to(['/admin/catalog/models/edit', 'id' => $this->id ]);
    }

    /**
     * Возвращает размерный ряд модели
     *
     * @property string $sizeRange The scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]].
     * @return \yii\db\ActiveQuery
     */
    public function getSizeRange()
    {
        return $this->hasOne(SizeRange::className(), ['id' => 'sizerange']);
    }

    public function getCharacteristics()
    {
        return $this->hasMany(Property::className(), ['model_id' => 'id']);
    }

    public function getDiscount()
    {

        if(!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $group = $user->group;
            $group_discount = empty($group) ? 0 : intval($group->discount);
        } else
            $group_discount = 0;

        $badge = $this->badge;
        $badge_discount = empty($badge) ? 0 : intval($badge->discount);

        $discount = max($badge_discount, $group_discount);

        return $discount;
    }


    public function getPrice()
    {
        $discount = ($this->discount / 100) * $this->final_price;
        return round($this->final_price - $discount);
    }

    public function getType()
    {

        if($this->model_type != '')
            return $this->model_type;

        $categories = $this->categories;

        if(empty($categories))
            throw new ErrorException(__METHOD__.": не найдена категория у товара (ID={$this->id})");

        $category = $categories[0];

        if($category->caption_one != '')
            return $category->caption_one;

        $category = $category->parent;

        if(!$category)
            throw new ErrorException(__METHOD__.": не найдена категория 2го уровня у товара (ID={$this->id})");

        if($category->caption_one != '')
            return $category->caption_one;

        return $category->caption;
    }

}