<?php

namespace app\models;

use app\models\Page;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{
    public function getPages()
    {
        return $this->hasMany( Page::className(), ['menu_id' => 'id'] )->orderBy('menu_order');
    }

    public static function getMenuList($menu_id)
    {
        if(!$menu = static::findOne(['id' => $menu_id]))
            return [];

        $result = [];
        foreach($menu->pages as $page){
            $result[$page->caption] = $page->link;
        }

        return $result;
    }
}