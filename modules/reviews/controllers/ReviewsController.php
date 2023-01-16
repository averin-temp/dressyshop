<?php
namespace app\modules\reviews\controllers;

use Yii;
use yii\easyii\components\Controller;
use app\models\Reviews;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\classes\Utilities;

class ReviewsController extends Controller
{

    public function actionIndex()
    {
		$data = new ActiveDataProvider([
            'query' => Reviews::find()->where(['avalible'=>0])->orderBy('created DESC'),
			'pagination' => [
				'pageSize' => 10,
			],
        ]);
		
		$data2 = new ActiveDataProvider([
            'query' => Reviews::find()->where(['avalible'=>1])->orderBy('created DESC'),
			'pagination' => [
				'pageSize' => 20,
			],
        ]);
        return $this->render('index', [
            'data' => $data,
			'data2' => $data2
        ]);
		
		
		

    }

    public function actionView($id)
    {
        $model = Reviews::findOne($id);
        if($model === null){
            $this->flash('error', 'Не найдено');
            return $this->redirect(['index']);
        }
		$oldAns = $model->avalible;
        $model->scenario = Reviews::SCENARIO_EDIT;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($oldAns == 0 && $model->avalible != 0){
				Utilities::removeNotice('25');
			}
			if($oldAns != 0 && $model->avalible == 0){
				Utilities::addNotice('25');
			}
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model
        ]);

    }

    public function actionDelete($id)
    {
        if(($model = Reviews::findOne($id))){
			if($model->avalible == 0){
				Utilities::removeNotice('25');
			}
            $model->delete();
        } else {
            $this->flash('error', 'Не найдено');
        }
        return $this->redirect(['index']);
    }


}