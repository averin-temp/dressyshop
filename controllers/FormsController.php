<?php
/**
 * Created by PhpStorm.
 * User: hedindoom
 * Date: 02.11.2017
 * Time: 14:27
 */

namespace app\controllers;
use app\models\Returnform;
use yii\web\Controller;
use app\classes\Utilities;


class FormsController extends Controller
{
    public function actionIndex(){
        $model = new Returnform();
        $model->name = \Yii::$app->request->post()['name'];
        $model->email = \Yii::$app->request->post()['email'];
        $model->date = \Yii::$app->request->post()['date'];
        $model->articulsize = \Yii::$app->request->post()['sku'];
        $model->why = \Yii::$app->request->post()['why'];
        $model->type = \Yii::$app->request->post()['how'];
        $model->order_number = \Yii::$app->request->post()['number'];

        if($model->save()){
			Utilities::addNotice('41');
            echo $model->id;
        }
    }
}