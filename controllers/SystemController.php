<?php

namespace app\controllers;


use app\classes\Cart;
use app\classes\Deferred;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Guestbook;
use app\classes\Utilities;
use Yii;

class SystemController extends Controller
{
    public function actionIndex()
    {
        return false;
    }

	public function actionClearfilter(){
		Yii::$app->session->remove('filters');
	}
	
	
    public function actionDeldeferr(){
        $id = \Yii::$app->request->post('id');
        $normal_id = Cart::getnormalId($id);
        Deferred::removeID($normal_id);
        Deferred::removeID($id);
    }
    public function actionDeldeferr_rall(){
        Deferred::removeAll();
    }
	
	public function actionGuestadd()
    {
        $model = new Guestbook();
        if ($model->load(Yii::$app->request->post())) {
			Utilities::addNotice('8');
			$model->save();
			//return $this->redirect(Url::to(['/page/index','slug' => 'gostevaya_kniga']));
		}
    }
}

?>