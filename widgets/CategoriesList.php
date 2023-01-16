<?php

namespace app\widgets;

use app\models\CategoryTree;
use Yii;
use app\models\Category;
use app\models\Model;
use app\models\Product;
use yii\data\ActiveDataProvider;
use app\classes\CatalogUrl;

class CategoriesList extends \yii\bootstrap\Widget
{

    public function run()
    {
        $category_slug_path = Yii::$app->request->get('category');
        $category_id = CatalogUrl::findByPath($category_slug_path);


        if($category_id === null)
        {
            $label = null;
            $list = CategoryTree::getTree();
            $active = null;
        }
        else
        {
            $categoryInfo = CategoryTree::getCategoryTree($category_id);

            if( isset($categoryInfo['childrens']) )
            {
                $list = $categoryInfo['childrens'];
                $label = $categoryInfo['caption'];
                $active = null;
            }
            else
            {
                $active = $categoryInfo['id'];

                if(isset($categoryInfo['parent']))
                {
                    $label = $categoryInfo['parent']['caption'];
                    $list = $categoryInfo['parent']['childrens'];
                }
                else
                {
                    $label = null;
                    $list = CategoryTree::getTree();
                }
            }
        }

        $_list = [];
        foreach($list as $item)
        {      
				if ($category_id = $item['id'])
				{
					$category = Category::findOne($category_id);
					$subquery = $category->allModelsQuery();// IDs
				}

				else
					$subquery = Model::find()->select('id');
				
		
				$query = Product::find()
				->joinWith('model')
				->where(['model_id' => $subquery, 'model.active' => 1])
				->groupBy('model_id')
				->orderBy('id DESC');
			
				$provider = new ActiveDataProvider([
					'query' => $query
				]);
				
				$item['c_count'] = $provider->getTotalCount();
				
				if($item['c_count'] != '0')
					$_list[] = $item;
        }


		
        return $this->render("categorieslist", [ 'label' => $label, 'list' => $_list, 'active' => $active ]);
    }

}