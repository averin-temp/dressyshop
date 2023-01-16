<?php

namespace app\widgets;

use app\models\CategoryTree;
use yii\bootstrap\Widget;

#namespace app\models;

#use app\classes\Utilities;
#use yii\db\ActiveRecord;
#use yii\db\mssql\QueryBuilder;
#use yii\db\Query;
use Yii;

class MainMenu extends Widget
{
    public function run()
    {
        $categories = CategoryTree::getTree();
        return $this->render("mainmenu", [ 'categories' => $categories ] );
    }
}