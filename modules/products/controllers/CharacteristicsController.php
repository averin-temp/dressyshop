<?php
namespace app\modules\products\controllers;

use Yii;
use app\models\Model;
use app\models\Property;
use app\models\PropertyType;
use yii\base\Exception;
use yii\easyii\components\Controller;
use yii\helpers\ArrayHelper;


class CharacteristicsController extends Controller
{
    function actionEdit($model)
    {
        if($model = Model::findOne($model))
        {

            $properties = Property::findAll(['model_id' => $model->id]);

            $types = PropertyType::find()->asArray()->all();
            $formats = ArrayHelper::map($types, 'id', 'format');

            $data = [];
            foreach($properties as $property) {
                $format = $formats[$property->type_id];
                if($format == PropertyType::IS_TEXT )
                    $data[$property->type_id][] =  $property->value_text;
                else $data[$property->type_id][] =  $property->value;
            }

            return $this->render('edit', [
                'model' => $model,
                'properties' => $data,
                'property_types' => PropertyType::find()->all(),
            ]);
        }
        $this->flash('error','товар не найден');
        return $this->redirect('/admin/products');
    }

    function actionSave()
    {
        $model_id = Yii::$app->request->post('model');
        $properties = Yii::$app->request->post('properties');

        try
        {
            if(!$model = Model::findOne($model_id))
                throw new Exception('Товар не найден');

            if(!empty($properties)) {

                $propertyObjects = [];

                $types = PropertyType::find()->asArray()->all();
                $types = ArrayHelper::map($types, 'id', 'format');

                foreach($properties as $type => $value) {

                    if(is_array($value)) {

                        foreach($value as $val) {
                            if($val = intval($val))
                                $propertyObjects[] = new Property(['type_id' => $type, 'value' => $val]);
                        }

                    } else {

                        if($types[$type] == PropertyType::IS_NUMBER && $value !== '' && is_numeric($value))
                            $propertyObjects[] = new Property([ 'type_id' => $type, 'value' => $value ]);

                        if($types[$type] == PropertyType::IS_TEXT && $value !== '')
                            $propertyObjects[] = new Property([ 'type_id' => $type, 'value_text' => $value ]);

                    }
                }

                $properties = $propertyObjects;

            } else $properties = [];

            Property::deleteAll(['model_id' => $model->id]);
            foreach($properties as $property) {
                $property->model_id = $model->id;
                $property->save();
            }


            $this->flash('success', "Характеристики сохранены");

        }
        catch(Exception $e)
        {
            $this->flash('error', $e->getMessage());
        }

        return $this->back();
    }

}