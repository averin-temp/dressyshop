<?php

namespace app\classes;

use app\models\CategoryTree;
use app\models\Model;
use yii\helpers\Url;
use yii\base\Object;
use app\models\Category;

class CatalogUrl extends Object
{

    public static function findByPath($path = '')
    {
        if ($path == '')
            return null;

        $pattern = '/([\w_-]+)/';
        if (!preg_match_all($pattern, $path, $pieces)) {
            return null;
        }

        $path = $pieces[0];
        $id = null;
        do {
            $slug = array_shift($path);
            $id = CategoryTree::getChildrenWithSlug($slug, $id);
        } while ($id && count($path));

        return $id;
    }

    /**
     * @param string $category_id
     * @return string
     * @internal param Category|string $category
     */
    public static function createPath($category_id = 0)
    {
        $categoryInfo = CategoryTree::getCategoryTree($category_id);

        if ($categoryInfo === null)
            return '';


        $path = $categoryInfo['slug'];
        while ($parentInfo = isset($categoryInfo['parent']) ? $categoryInfo['parent'] : false) {
            $slug = $parentInfo['slug'];
            $path = $slug . '/' . $path;
            $categoryInfo = $parentInfo;
        }
        $result = Url::base() . '/catalog/' . $path;
        return $result;
    }

}