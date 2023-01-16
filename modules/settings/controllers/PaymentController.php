<?php

namespace app\modules\settings\controllers;

use app\models\Delivery;
use app\models\Pay;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;

class PaymentController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Pay::find()->orderBy('order')
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
                $model = Pay::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }

    public function actionSave()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $model = Pay::findOne(intval($id));
        } else
            $model = new Pay();

        if ($model->load(Yii::$app->request->post())) {
            $model->delivery = implode(",", Yii::$app->request->post('Pay')['delivery']);
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
        $model = new Pay();
        $delivery = Delivery::find()->all();
        return $this->render('edit', ['model' => $model, 'delivery' => $delivery]);
    }

    public function actionEdit($id)
    {
        $model = Pay::findOne($id);
        $delivery = Delivery::find()->all();
        return $this->render('edit', ['model' => $model, 'delivery' => $delivery]);

    }

    public function actionDelete($id)
    {
        if (($model = Pay::findOne($id))) {
            $model->delete();
        } else {
            $this->error = "Способ оплаты не найден";
        }
        return $this->formatResponse("Способ оплаты удален", true);
    }

}