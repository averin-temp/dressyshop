<?php
namespace app\modules\settings\controllers;


use app\models\Size;
use app\models\SizeRange;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;

class SizeController extends Controller
{

    public function actionIndex($range = '')
    {
        $rangeID = intval($range);
        if(!$rangeID || !$range = SizeRange::findOne($rangeID)) {
            $this->flash('error', 'Такого модельного ряда нет');
            return $this->redirect(['sizerange/index']);
        }

        $data = new ActiveDataProvider([
            'query' => $range->getSizes()->orderBy('order')
        ]);

        return $this->render('index', [
            'data' => $data,
            'range' => $range
        ]);
    }
    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = Size::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));
        if($id) {
            $model = Size::findOne($id);
        } else {
            $model = new Size();
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


                $this->flash("success", "Размер сохранен");
                return $this->redirect(['index', 'range' => $model->parent_range]);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate($range = '')
    {
        $rangeID = intval($range);

        if(!$range = SizeRange::findOne($rangeID))
        {
            $this->flash('error', 'Не указан размерный ряд');
            return $this->redirect('sizerange/index');
        }

        $model = new Size(['parent_range' => $range->id ]);
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
        if(($model = Size::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Размер ряд не найден";
        }
        return $this->formatResponse("Размер удален", true);
    }

}