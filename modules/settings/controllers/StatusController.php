<?php

namespace app\modules\settings\controllers;


use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use app\modules\orders\models\Status;

class StatusController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Status::find()->orderBy('order')
        ]);

        return $this->render('index', [ 'data' => $data ]);
    }

    function actionEdit($id = '')
    {
        $status = Status::findOne(intval($id));
        if(empty($status)) {
            $this->flash('error', "Такого статуса не существует");
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $status]);

    }
    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = Status::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    function actionSave()
    {
        $id = Yii::$app->request->post('id');
        if($id) {
            $status = Status::findOne(intval($id));
        } else
            $status = new Status();

        if($status->load(Yii::$app->request->post()) && $status->save())
        {
            if(!$status->order){
                $status->order = $status->id;
                $status->save();
            }
            $this->flash('success', "Статус сохранен");
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $status]);
    }


    function actionDelete($id = '')
    {
        $status = Status::findOne(intval($id));
        if(empty($status)) {
            $this->flash('error', "Такого статуса нет");
        }
        $status->delete();
        $this->flash('success', "Статус удален");
        return $this->redirect(['index']);

    }


    function actionCreate()
    {
        $status = new Status();
        return $this->render('edit', ['model' => $status]);
    }

}