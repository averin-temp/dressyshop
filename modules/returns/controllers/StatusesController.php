<?php
namespace app\modules\returns\controllers;

use app\models\Returnstatuses;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use app\models\Returnform;

class StatusesController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Returnstatuses::find()
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }



    function actionEdit($id = '')
    {
        $status = Returnstatuses::findOne(intval($id));
        if(empty($status)) {
            $this->flash('error', "Такого статуса не существует");
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $status]);

    }

    function actionSave()
    {
        $id = Yii::$app->request->post('id');
        if($id) {
            $status = Returnstatuses::findOne(intval($id));
        } else
            $status = new Returnstatuses();

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
        $status = Returnstatuses::findOne(intval($id));
        if(empty($status)) {
            $this->flash('error', "Такого статуса нет");
        }
        $status->delete();
        $this->flash('success', "Статус удален");
        return $this->redirect(['index']);

    }


    function actionCreate()
    {
        $status = new Returnstatuses();
        return $this->render('edit', ['model' => $status]);
    }



}