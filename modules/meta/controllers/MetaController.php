<?php

namespace app\modules\meta\controllers;

use app\models\Meta;
use app\models\Model;
use yii\data\Pagination;
use yii\easyii\components\Controller;
use Yii;

class MetaController extends Controller
{
    function actionIndex()
    {
        $categories = Category::find()->all();
        return $this->render('index', ['categories' => $categories] );
    }

    function actionCreate($id = '')
    {
        $categories = Category::find()
            ->where([ 'parent_id' => null ])->all();

        return $this->render(
            'create',
            [
                'model' => new Category(),
                'categories' => $categories,
                'selected' => $id
            ]
        );
    }

    function actionEdit($id)
    {

        $category = Category::findOne($id);
        $categories = Category::find()->where([ 'parent_id' => null ])->all();

        return $this->render('create', [ 'model' => $category, 'categories' => $categories, 'selected' => $id]);
    }

    function actionSave()
    {
        if($id = Yii::$app->request->post('id'))
            $cat = Category::findOne($id);
        else
            $cat = new Category();

        $post = Yii::$app->request->post();

        if($cat->load($post) && $cat->save())
        {
            $this->flash('success', "Категория сохранена");
        }
        else{
            $this->flash('error', "ошибка сохранения");
        }

        return $this->redirect(['index']);
    }

    function actionDelete($id = '')
    {
        if($category = Category::findOne($id) )
        {
            $category->delete();
            $this->flash('success', "категория удалена");
        } else {
            $this->flash('error', "категория не найдена");
        }

        return $this->redirect(['index']);
    }
}