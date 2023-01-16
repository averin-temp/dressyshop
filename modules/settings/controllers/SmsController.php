<?php
namespace app\modules\settings\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use yii\easyii\modules\page\models\Page;

class SmsController extends Controller
{

    public function actionIndex()
    {

        return $this->render('index', [
            'data' => []
        ]);
    }

}