<?php
namespace app\modules\settings\controllers;

use app\classes\PropertyHelper;
use app\classes\Utilities;
use app\models\Property;
use app\models\PropertyValue;
use Yii;
use yii\web\Response;
use app\models\PropertyType;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;

class PropertyController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => PropertyType::find()->orderBy('order')
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = PropertyType::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));
        if($id) {
            $model = PropertyType::findOne($id);
        } else {
            $model = new PropertyType();
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

                // если изменен тип свойства с набора значений на другой ,
                // удаляем все значения
                if($model->oldAttributes['format'] !== PropertyType::IS_UNION) {
                    PropertyValue::deleteAll(['type_id' => $model->id]);
                }

                $this->flash("success", "Тип сохранен");
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $type = new PropertyType();
        return $this->render('edit', ['model' => $type]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = PropertyType::findOne($id))
            return $this->render('edit', ['model' => $model]);

        $this->flash('error', "Такого ряда нет");
        return $this->redirect('index');


    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = PropertyType::findOne($id))){
            Utilities::removePropertyType($model);
        } else {
            $this->error = "Тип свойства не найдено";
        }
        return $this->formatResponse("Тип свойства удален", true);
    }


    public function actionAjaxgetproperty()
    {
        $request = Yii::$app->request;
        if($request->isAjax === false) {
            return "Неверный формат запроса";
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            if(!$type_id = intval($request->post('type')))
                throw new Exception("Неверный параметр");

            if(!$type = PropertyType::findOne($type_id))
                throw new Exception("Несуществующий тип свойства");

            if($type->format == PropertyType::IS_UNION)
            {
                $content = PropertyHelper::union([], $type);
            }
            else
            {
                $property = new Property([ 'type_id' => $type->id ]);
                $content = PropertyHelper::field($property);
            }

            return  [ 'error' => false, 'message' => 'ок', 'content' => $content ];
        } catch( Exception $e)
        {
            return [ 'error' => true, 'message' => $e ];
        }
    }

}