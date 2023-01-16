<?php

namespace app\modules\settings\controllers;

use app\models\Regions;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\models\Delivery;

class DeliveryController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Delivery::find()->orderBy('order')
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
    public function actionSavedrag()
    {
        if ($datacar = Yii::$app->request->post('data')) {
            $data = json_decode($datacar);
            foreach ($data as $key => $value) {
                $model = Delivery::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }

    public function actionSave()
    {

//        die(var_dump());
        if ($id = Yii::$app->request->post('id')) {
            $model = Delivery::findOne($id);
        } else $model = new Delivery();


        if ($model->load(Yii::$app->request->post())) {
            $model->region = implode(",", Yii::$app->request->post('Delivery')['region']);
            if ($model->save()) {

                if (!$model->order) {
                    $model->order = $model->id;
                    $model->save();
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Delivery();
        $regions = Regions::find()->orderBy('order')->all();
        return $this->render('edit', ['model' => $model, 'regions' => $regions]);
    }

    public function actionEdit($id)
    {
        $model = Delivery::findOne($id);
        $regions = Regions::find()->orderBy('order')->all();

        return $this->render('edit', ['model' => $model, 'regions' => $regions]);

    }

    public function actionDelete($id)
    {
        if (($model = Delivery::findOne($id))) {
            $model->delete();
        } else {
            $this->error = "Доставка не найдена";
        }
        return $this->formatResponse("Доставка удалена", true);
    }

}