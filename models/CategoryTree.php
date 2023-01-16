<?php
namespace app\models;

use yii\base\Object;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Class CategoryTree
 *
 * Я поразбирался с жадной загрузкой, и че то мне не понравился стандартный способ.
 * Пришла идея вот такого класса. У нас куча запросов тратится на работу с категориями.
 * Тут они кэшируются , и я хочу понаписать тут вспомогательных функций, и перевести
 * обработчик url-ов тоже на нее.
 *
 *
 *
 *
 * @package app\models
 */
class CategoryTree extends Object
{
    public static $tree = null;
    public static $links = [];

    /**
     * Строит дерево категорий.
     *
     * Вот такого вида:
     *
     * [ id =>  [  id =>
     *             caption =>
     *             count // количество моделей внутри
     *             parent_id
     *             childrens => [
     *                      'id' => [ ..... ],
     *                      'id' => [ ..... ],
     *                      'id' => [ ..... ],
     *             ]
     *          ],
     * ]
     *
     * В массиве links[] содержатся ссылки на категории по ID, для быстрого доступа. (пример getCategoryTree($id))
     *
     * @return array|null
     */
    public static function &getTree()
    {
        if(static::$tree !== null)
            return static::$tree;

        static::$tree = [];



        $query = (new Query())->select([
                'category_table.id',
                'category_table.slug',
                'category_table.caption',
                'category_table.caption_one',
                'category_table.icon_link',
                'category_table.order',
                'category_table.parent_id']
        )->from([ 'category_table' => Category::tableName() ]);

        $rows = $query->all();

        $parents = ArrayHelper::index($rows, 'id');

        foreach($parents as &$item)
        {
            static::$links[$item['id']] = &$item;
        }


        foreach($parents as $id => &$child) {

            $itsParent = $child['parent_id'];

            if(isset($parents[$itsParent]))
            {
                $child['parent'] = &$parents[$itsParent];
                $parents[$itsParent]['childrens'][$id] = &$child;
            }
            else static::$tree[$id] = &$child;
        }

        static::countModels();



        return static::$tree;
    }


    /**
     * Подсчитывает количество товара в категориях второго и первого уровня
     */
    private static function countModels()
    {
	    $query = (new Query())
			->select('model_id')
			->from(['model_to_category','model'])
			->where('model_to_category.model_id = model.id')
			->andWhere(['model.active'=>1])
			->groupBy('model_to_category.model_id');
			
			
			//die(var_dump($query->count()));

// SELECT `model_id` 
// FROM `model_to_category`,`model` 
// WHERE `model_to_category`.`model_id` = `model`.`id` 
// AND `model`.`active` = 1 
// GROUP BY `model_to_category`.`model_id`

        foreach(static::$tree as $category)
        {

            if(isset($category['childrens']))
            {
                $lvl1Ids = array();
                foreach( $category['childrens'] as $level2category)
                {
                    $category_id = $level2category['id'];
                    $ids = array();
                    if(isset($level2category['childrens']))
                    {
                        $ids = array_keys($level2category['childrens']);
                        $_query = clone $query;
                        $_query->andWhere(['model_to_category.cat_id' => $ids]);
                        $count = $_query->count();

                        static::$links[$category_id]['count'] = $count;
                    }
                    else static::$links[$category_id]['count'] = 0;

                    $lvl1Ids = array_merge($lvl1Ids, $ids);
                }

                $_query = clone $query;
	            $_query->andWhere(['model_to_category.cat_id' => $lvl1Ids]);
                $count = $_query->count();
                static::$links[$category['id']]['count'] = $count;

            }
            else static::$links[$category['id']]['count'] = 0;



        }
    }


    /**
     * Возвращает ветку категорий
     *
     * @param $category_id
     * @return mixed
     */
    public static function getCategoryTree($category_id)
    {
        static::getTree();
        return isset(static::$links[$category_id]) ? static::$links[$category_id] : null;
    }

    public static function childrensSortedByName($category_id)
    {
        $childrens = static::$links[$category_id]['childrens'];
        return $childrens;
    }


    public static function getChildrenWithSlug($slug, $id = null)
    {
        static::getTree();

        if($id === null)
        {
            $childrens = static::$tree;
        }
        else
        {
            $root = static::$links[$id];
            if(!isset($root['childrens']))
                return null;
            $childrens = $root['childrens'];
        }

        foreach($childrens as $item)
        {
            if($item['slug'] ===  $slug)
                return $item['id'];
        }

        return null;

    }

    /**
     * Возвращает уровень категории
     *
     * @param $id
     * @return int
     */
    public static function level($id)
    {
        $data = static::getCategoryTree($id);

        if($data === null)
            return null;

        $level = 0;

        while(isset($data['parent']))
        {
            $level++;
            $data = $data['parent'];
        }

        return $level;
    }

}