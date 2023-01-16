<?php
namespace app\modules\settings\controllers;

use app\models\Promocode;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\models\Badge;
use yii\web\UploadedFile;

class PromocodeController extends Controller
{

    public function actionIndex()
    {

        $data = new ActiveDataProvider([
            'query' => Promocode::find()
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSave()
    {
        if($id = Yii::$app->request->post('id'))
        {
            $model = Promocode::findOne($id);
        }
        else
        {
            $model = new Promocode();
        }

        if(!$model) {
            $this->flash("error", 'такого промокода не найдено');
            return $this->redirect(['index']);
        }


        if($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                $this->flash('success', "Промокод сохранен");
                return $this->redirect(['index']);            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Promocode();

        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = Promocode::findOne($id);

        if(!$model) {
            $this->flash("error", 'такого промокода не найдено');
            return $this->redirect('index');
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        if(($model = Promocode::findOne($id))){
            $model->delete();
            $this->flash('success', "Прмокод удален");
        } else {
            $this->error = "Промокод не найден";
        }
        return $this->redirect(['index']);
    }

}