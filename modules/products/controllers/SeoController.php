<?php
namespace app\modules\products\controllers;

use Yii;
use app\models\Brand;
use app\models\Color;
use app\models\Model;
use app\models\Category;
use yii\base\Exception;
use yii\easyii\components\Controller;
use app\models\Badge;


class SeoController extends Controller
{
    function actionEdit($model)
    {
        if($model = Model::findOne($model))
        {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
        $this->flash('error','товар не найден');
        return $this->redirect('/admin/products');
    }

    function actionSave()
    {
        $post = Yii::$app->request->post();
        $model_id = $post['model'];

        try
        {
            if(!$model = Model::findOne($model_id))
                throw new Exception('Товар не найден');

            $model->scenario = Model::SCENARIO_SEO;
            if($model->load($post))
            {
                if($model->save($post))
                {
                    $this->flash('success', "Seo настройки сохранены");
                    return $this->back();
                }

            }

            throw new Exception('Ошибка');


        }
        catch(Exception $e)
        {
            $this->flash('error', $e->getMessage());
        }

        return $this->back();
    }


}