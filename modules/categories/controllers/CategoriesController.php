<?php

namespace app\modules\categories\controllers;

use app\classes\Utilities;
use app\models\Category;
use app\models\CategoryTree;
use app\models\Model;
use yii\base\Exception;
use yii\data\Pagination;
use yii\easyii\components\Controller;
use Yii;

class CategoriesController extends Controller
{
    function actionIndex()
    {
        $tree = CategoryTree::getTree();
		
		// echo '<pre>';
		// var_dump($tree);		
		// echo '</pre>';
		// die();
		
        return $this->render('index', ['tree' => $tree] );
    }

    function actionCreate($id = '')
    {
        $tree = CategoryTree::getTree();

        if($id !== '')
        {
            $level = CategoryTree::level($id);

            try{

                if($level === null)
                    throw new Exception('категория не найдена');

                if($level > 1)
                    throw new Exception('error', "у категорий третьего уровня не может быть дочерних категорий");

            }
            catch(Exception $e)
            {
                $this->flash('error', $e->getMessage());
                return $this->redirect(['index']);
            }
        }

        return $this->render(
            'create',
            [
                'model' => new Category(),
                'categories' => $tree,
                'selected' => $id
            ]
        );
    }

    function actionEdit($id)
    {

        $category = Category::findOne($id);
        $tree = CategoryTree::getTree();

        return $this->render(
            'create',
            [
                'model' => $category,
                'categories' => $tree,
                'selected' => $id
            ]
        );
    }

    function actionSave()
    {
        if($id = Yii::$app->request->post('id'))
            $cat = Category::findOne($id);
        else
            $cat = new Category();

        $post = Yii::$app->request->post();

        if($cat->load($post) && $cat->save()) {
            $this->flash('success', "Категория сохранена");
        } else {
            $this->flash('error', "ошибка сохранения");
        }

        return $this->redirect(['index']);
    }

    function actionDelete($id = '')
    {
        if($category = Category::findOne($id) )
        {
            Utilities::removeCategory($category);
            $this->flash('success', "категория удалена");
        } else {
            $this->flash('error', "категория не найдена");
        }

        return $this->redirect(['index']);
    }
}