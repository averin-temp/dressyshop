<?php

namespace app\modules\settings\controllers;

use app\models\Regions;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\easyii\components\Controller;

class RegionsController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Regions::find()->orderBy('order' )
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = Regions::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    public function actionSave()
    {



        if ($id = Yii::$app->request->post('id')) {
            $model = Regions::findOne($id);
        } else $model = new Regions();



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(!$model->order){
                $model->order = $model->id;
                $model->save();
            }
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Regions();
        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = Regions::findOne($id);
        return $this->render('edit', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (($model = Regions::findOne($id))) {
            $model->delete();
        } else {
            $this->error = "Регион не найден";
        }
        return $this->formatResponse("Регион удален", true);
    }

}