<?php
namespace app\modules\questions\controllers;

use Yii;
use app\models\Questions;
use app\classes\Utilities;
use yii\data\ActiveDataProvider;

use yii\easyii\components\Controller;

class QuestionsController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Questions::find()->where(['answer'=>''])->orderBy('created DESC'),
			'pagination' => [
				'pageSize' => 10,
			],
        ]);
		
		$data2 = new ActiveDataProvider([
            'query' => Questions::find()->where(['!=','answer',''])->orderBy('created DESC'),
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
        $model = Questions::findOne($id);
        if($model === null){
            $this->flash('error', 'Не найдено');
            return $this->redirect(['index']);
        }
		$oldAns = $model->answer;
        $model->scenario = Questions::SCENARIO_ANSWER;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($oldAns == '' && $model->answer != ''){
				Utilities::removeNotice('24');
			}
			if($oldAns != '' && $model->answer == ''){
				Utilities::addNotice('24');
			}
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model
        ]);

    }

    public function actionDelete($id)
    {
		
        if(($model = Questions::findOne($id))){
			if($model->answer == ''){
				Utilities::removeNotice('24');
			}
            $model->delete();
        } else {
            $this->flash('error', 'Не найдено');
        }
		
        return $this->redirect(['index']);
    }

    public function actionNoanswer()
    {
        $data = new ActiveDataProvider([
            'query' => Questions::find()->where(['answer' => ''])
        ]);
        return $this->render('noanswer', [
            'data' => $data
        ]);
    }

}