<?php
namespace app\modules\settings\controllers;


use app\models\SizeRange;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;

class SizerangeController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => SizeRange::find()->orderBy('order'),
			'pagination' => [
				'pageSize' => 100,
			],
        ]);


        return $this->render('index', [
            'data' => $data
        ]);
    }
    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = SizeRange::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));

        if($id) {
            $model = SizeRange::findOne($id);
        } else {
            $model = new SizeRange();
        }

        if($model->load(Yii::$app->request->post()))
        {

            if($model->validate())
            {
                if($model->save()){
                    if(!$model->order){
                        $model->order = $model->id;
                        $model->save();
                    }
                }

                $this->flash("success", "Размерный ряд сохранен");
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new SizeRange();

        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = SizeRange::findOne($id))
            return $this->render('edit', ['model' => $model]);

        $this->flash('error', "Такого ряда нет");
        return $this->redirect('index');


    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = SizeRange::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Модельный ряд не найден";
        }
        return $this->formatResponse("Модельный ряд удален", true);
    }

}