<?php
namespace app\modules\settings\controllers;

use app\models\PropertyValue;
use Yii;
use app\models\PropertyType;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ValuesController extends Controller
{

    public function actionIndex($type_id = '')
    {
        if(empty($type_id) ||!$type = PropertyType::findOne(intval($type_id))) {
            $this->flash('error', 'не указано свойство');
            return $this->redirect(['property/index']);
        }

        $data = new ActiveDataProvider([
            'query' => PropertyValue::find()->orderBy('order')->where(['type_id' => $type->id]),
            'pagination'=> false
        ]);

        return $this->render('index', [
            'data' => $data,
            'type' => $type
        ]);
    }

    public function actionAjaxsave()
    {
        $request = Yii::$app->request;

        try{

            if(!$request->isAjax)
                throw new Exception("Неверный формат запроса");

            Yii::$app->response->format = Response::FORMAT_JSON;

            $type = intval($request->post('type'));
            $name = $request->post('name');

            if(!$type = PropertyType::findOne($type))
                throw new Exception("Такое свойства не существует");

            if($value = PropertyValue::find()->where(['type_id' => $type->id, 'name' => $name])->One())
                throw new Exception("Значение уже существует");

            $model = new PropertyValue(['name' => $name, 'type_id' => $type->id]);
            if(!$model->save())
                throw new Exception("ошибка сохранения");

            return [
                'error' => false,
                'id' => $model->id,
                'name' => $model->name
            ];


        } catch(Exception $e)
        {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;

        if(!$request->isAjax)
            throw new BadRequestHttpException("Неверный формат запроса");

        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            $id = intval($request->post('id'));

            if(!$value = PropertyValue::findOne($id))
                throw new Exception("Такого значения не существует");

            $value->delete();

            return [
                'error' => false,
                'message' => 'значение удалено'
            ];


        } catch(Exception $e)
        {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

}