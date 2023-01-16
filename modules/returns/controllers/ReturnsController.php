<?php
namespace app\modules\returns\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use app\models\Returnform;
use app\models\Returnstatuses;
use app\classes\Utilities; 

class ReturnsController extends Controller
{

    public function actionIndex()
    {
		$query = Returnform::find()->where(['status'=>1]);
		$query->joinWith(['status_name']);
		
		$query2 = Returnform::find()->where(['!=','status',1])->orderBy('date DESC');
		$query2->joinWith(['status_name']);
		
        $data = new ActiveDataProvider([
            'query' => $query 
        ]);

		
		$data2 = new ActiveDataProvider([
            'query' => $query2
        ]);
		
		//$statuses = Returnstatuses::find()->all();
        return $this->render('index', [
            'data' => $data,
			'data2' => $data2
			//'statuses'=>$statuses
        ]);
    }
	
	
	
	
	
	
	
	
	public function actionSave()
    {

//        die(var_dump());
        if ($id = Yii::$app->request->post('id')) {
            $model = Returnform::findOne($id);
        } else $model = new Returnform();

$status_old = $model->status;
        if ($model->load(Yii::$app->request->post())) {
			
            //$model->region = implode(",", Yii::$app->request->post('Returnform')['region']);
            if ($model->save()) {      
				if($status_old == 1 && $model->status != 1){
					Utilities::removeNotice('41');
				}
				if($status_old != 1 && $model->status == 1){
					Utilities::addNotice('41');
				}
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }


    public function actionEdit($id)
    {
        $model = Returnform::findOne($id);
        $statuses = Returnstatuses::find()->orderBy('order')->all();

        return $this->render('edit', ['model' => $model, 'statuses' => $statuses]);

    }

    public function actionDelete($id)
    {
        if (($model = Returnform::findOne($id))) {
			if($model->status == 1){
				Utilities::removeNotice('41');
			}
            $model->delete();
        } else {
            $this->error = "Возврат не найден";
        }
        return $this->formatResponse("Возврат удалена", true);
    }
	
	

}