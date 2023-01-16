<?php

namespace app\models;
use app\classes\CatalogUrl;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\Query;

class Category extends ActiveRecord
{

    public function rules()
    {
        return [
            ['parent_id', 'number'],
            ['parent_id', 'default', 'value' => 0],
            ['parent_id', function ($attribute, $params) {

                $val = $this->$attribute;
                if($val != 0){
                    $category = CategoryTree::getCategoryTree($val);
                    if($category === null)
                        $this->addError($attribute, 'Не найдена родительская категория');
                    elseif(($level = CategoryTree::level($val)) && $level > 1)
                        $this->addError($attribute, 'Нельзя создавать подкатегории 3го уровня');
                }
            }],
            [['meta_title', 'meta_keywords', 'meta_description'], 'trim'],
            [['caption', 'slug'], 'required'],
            [['description', 'use_parent_description', 'caption_one', 'order', 'icon_link'], 'safe']
        ];
    }


    /**
     * Возвращает все категории.
     *
     * @return array|ActiveRecord[]
     */
    static function getList()
    {
        return self::find()->all();
    }

    /**
     * Возвращает ссылку на категорию
     *
     * @return string
     */
    public function getLink()
    {
        return CatalogUrl::createPath($this->id);
    }

    /**
     * Заглушка, не помню зачем)
     *
     * @return bool
     */
    public function getActive()
    {
        return false;
    }

    public function getChildrensw()
    {
        return self::find()->where(['parent' => $this->id])->all();
    }

    public function getParentCategory()
    {
        return self::findOne($this->parent_id);
    }

    /**
     * Возвращает ID всех дочерних категорий.
     * То есть не только прямых потомков но и их детей тоже
     *
     * @param $category
     * @return array
     */
    public static function getAllChildrensID($category)
    {
        $childrens = $category->childrens;
        $ids = array();
        if (!empty($childrens)) {
            foreach ($childrens as $child) {
                $ids[] = $child->id;
                array_merge($ids, self::getAllChildrensID($child));
            }

        }
        return $ids;


    }


    /**
     * Возвращает массив данных для виджета Хлебных крошек. В каталоге.
     *
     * @param string $category
     * @return array
     */
    public static function getBreadPath($category = '')
    {
        if ($category == '') {
            $category = Yii::$app->request->get('category');
            $category = CatalogUrl::findByPath($category);
        }

        $breads = array();
        if($category === null)
            return $breads;

        $category = Category::findOne($category);
        if (empty($category))
            return $breads;

        $breads[$category->caption] = $category->link;
        while ($parent = $category->getParentCategory()) {
            $breads[$parent->caption] = $parent->link;
            $category = $parent;
        }
        $breads = array_reverse($breads);


        return $breads;
    }

    /**
     * Возвращает прямых потомков категории
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildrens()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }


    /**
     * Возвращает родителя категории или null
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }


    /**
     * Возвращает описание категории.  Если у категории стоит флаг - использовать описание родительской категории,
     * и при этом у самой описания нету, то будет искаться ближайший родитель с заполненным описанием.
     *
     * @return mixed|string
     */
    public function getDescriptionWithParent()
    {
        if (empty($this->description)) {
            $current = $this;
            while ($current = $current->parent) {
                if ($description = $current->description)
                    return $description;
            }
        } else
            return $this->description;

        return '';
    }


    /**
     * Считает сколько моделей с этой категорией в базе
     *
     * @return int|string
     */
    public function getModelsCount()
    {
        return $this->allModelsQuery()->count();
    }


    /**
     * Возвращает все модели в категории(без дочерних)
     *
     * @return $this
     */
    public function getModels()
    {
        return $this->hasMany(Model::className(), ['id' => 'model_id'])
            ->viaTable(Model_to_category::className(), ['cat_id' => 'id']);
    }

    /**
     * Возвращает ActiveQuery , возвращающий все Product этой категории
     *
     * @return ActiveQuery
     */
    public function allProductsQuery()
    {
        $query = $this->allModelsQuery();
        return Product::find()->where(['model_id' => $query]);
    }


    /**
     * Возвращает ActiveQuery на все модели этой категории, и всех дочерних
     *
     * @return ActiveQuery
     */
    public function allModelsQuery()
    {
        $category = CategoryTree::getCategoryTree($this->id);

        $level = CategoryTree::level($category['id']);

        $categoriesID = [];
        if( $level == 2 )
        {
            $categoriesID = [$this->id];
        }

        if( $level == 1 )
        {
            if(isset($category['childrens']))
            {
                foreach($category['childrens'] as $child)
                {
                    $categoriesID[] = $child['id'];
                }
            }
        }

        if( $level == 0 )
        {
            if(isset($category['childrens']))
            {
                foreach($category['childrens'] as $child)
                {
                    if(isset($child['childrens']))
                    {
                        foreach($child['childrens'] as $child2)
                        {
                            $categoriesID[] = $child2['id'];
                        }
                    }
                }
            }
        }



        //return Model::find()->joinWith(['m_to_c' => Model_to_category::tableName()])->select('id')->where(['m_to_c.cat_id' => $categoriesID]);
        return Model_to_category::find()->select('model_id')->where(['cat_id' => $categoriesID])->groupBy('model_id')->asArray();
    }


    /**
     * Возвращает ID всех дочерних категорий, всех потомков
     *
     * @return array
     */
    public function getSubcategoriesID()
    {
        $categories = static::find()->select(['id', 'parent_id'])->asArray()->all();

        $childrensFrom = [];
        foreach ($categories as $category) {
            $parent = $category['parent_id'];
            if (empty($parent)) $parent = 'top';
            $childrensFrom[$parent][] = $category;
        }

        return $this->subcategoriesIDRecursive($this->id, $childrensFrom);
    }


    /**
     * Рекурсивная функция для поиска потомков категории
     *
     * @param $id
     * @param $childrensFrom
     * @return array
     */
    private function subcategoriesIDRecursive($id, $childrensFrom)
    {
        $childrens = isset($childrensFrom[$id]) ? $childrensFrom[$id] : [];

        $result = [];
        foreach ($childrens as $child) {
            $result[] = $child['id'];
            $result = array_merge($result, $this->subcategoriesIDRecursive($child['id'], $childrensFrom));
        }

        return $result;
    }

}
