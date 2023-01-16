<?php
namespace app\modules\settings\controllers;

use app\models\AutoBadge;
use app\models\Seo;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\models\Badge;
use yii\web\UploadedFile;

class SeoController extends Controller
{

    public function actionIndex()
    {
        $seo = new ActiveDataProvider([
            'query' => Seo::find()
        ]);

        return $this->render('index', [
            'data' => $seo,
        ]);
    }

    public function actionSave()
    {
        $post = Yii::$app->request->post('Seo');
        if($id = $post['id'])
        {
            $model = Seo::findOne($id);
        }
        else
        {
            $model = new Seo();
        }


        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate() && $model->save())
            {
                $this->flash('success', 'настройка сохранена');
                return $this->redirect(['index']);
            }
        }

        $this->flash('error', 'настройка не сохранена');
        return $this->render('edit', ['seo' => $model]);

    }

    public function actionEdit($id)
    {
        $model = Seo::findOne($id);

        return $this->render('edit', ['seo' => $model]);

    }
}