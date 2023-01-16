<?php
namespace app\modules\settings\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Color;
use yii\easyii\components\Controller;

class ColorController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Color::find()->orderBy('name')
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));



        if($id) {
            $model = Color::findOne($id);
        } else {
            $model = new Color();
        }

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->flash("success", "Цвет сохранен");
            return $this->redirect(['index']);
        } else
            return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = Color::findOne($id))
            return $this->render('edit', ['model' => $model]);

        $this->flash('error', "Такого цвета нет");
        return $this->redirect('index');


    }

    public function actionCreate()
    {
        $model = new Color();
        return $this->render('edit', ['model' => $model]);
    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = Color::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Цвет не найден";
        }
        return $this->formatResponse("Цвет удален", true);
    }

}