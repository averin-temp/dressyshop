<?php
namespace app\modules\gallery\controllers;

use Yii;
use app\models\Guestbook;
use app\classes\Utilities;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use yii\easyii\components\Controller;

class GalleryController extends Controller
{

    public function actionIndex()
    {
          return $this->render('index');
    }


}