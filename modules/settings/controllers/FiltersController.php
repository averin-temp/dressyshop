<?php
namespace app\modules\settings\controllers;

use app\models\PropertyType;
use Yii;
use app\models\Filters;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\helpers\ArrayHelper;

class FiltersController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Filters::find()
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));
        if($id) {
            $model = Filters::findOne($id);
        } else {
            $model = new Filters();
        }

        if($model->load(Yii::$app->request->post()))
        {

            if($model->validate())
            {
                $model->save();

                $this->flash("success", "Фильтр сохранен");
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $type = new Filters(['property_types' => []]);
        $filters = ArrayHelper::map(Filters::find()->all(),'id','name');
        $propertyTypes = PropertyType::find()->all();
        return $this->render('edit', ['model' => $type, 'filters' => $filters, 'propertyTypes' => $propertyTypes ]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = Filters::findOne($id)) {
            $filters = ArrayHelper::map(Filters::find()->where(['!=', 'id', $id ])->all(), 'id', 'name');
            $propertyTypes = PropertyType::find()->all();
            $model->property_types = json_decode($model->property_types, false);
            return $this->render('edit', ['model' => $model, 'filters' => $filters, 'propertyTypes' => $propertyTypes ]);
        }

        $this->flash('error', "Такого фильтра нет");
        return $this->redirect('index');


    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = Filters::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Фильтр не найден";
        }
        return $this->formatResponse("Фильтр удален", true);
    }
}