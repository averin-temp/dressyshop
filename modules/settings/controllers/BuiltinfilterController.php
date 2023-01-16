<?php
namespace app\modules\settings\controllers;


use app\models\BuiltinFilter;
use app\models\Filters;
use Yii;
use app\models\Brand;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class BuiltinfilterController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => BuiltinFilter::find()
        ]);


        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));

        if($id) {
            $model = BuiltinFilter::findOne($id);
        } else {
            $model = new BuiltinFilter();
        }

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->flash('success', 'Фильтр сохранен');
            return $this->redirect('index');
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new BuiltinFilter();
        $filters = Filters::find()->all();
        $filters = ArrayHelper::map($filters, 'id', 'name');

        return $this->render('edit', ['model' => $model, 'filters' => $filters ]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = BuiltinFilter::findOne($id))
        {
            $filters = Filters::find()->all();
            $filters = ArrayHelper::map($filters, 'id', 'name');

            return $this->render('edit', ['model' => $model, 'filters' => $filters ]);
        }

        $this->flash('error', "Такого фильтра нет");
        return $this->redirect('index');


    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = BuiltinFilter::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Фильтр не найден";
        }
        return $this->formatResponse("Фильтр удален", true);
    }

}