<?php
namespace app\modules\guestbook\controllers;

use Yii;
use app\models\Guestbook;
use app\classes\Utilities;
use app\classes\MailTemplateSend;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use yii\easyii\components\Controller;

class GuestbookController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Guestbook::find()->where(['answer'=>''])->orderBy('created DESC')
        ]);
		
		$data2 = new ActiveDataProvider([
            'query' => Guestbook::find()->where(['!=','answer',''])->orderBy('created DESC')
        ]);
        return $this->render('index', [
            'data' => $data,
			'data2' => $data2
        ]);
    }


    public function actionView($id)
    {
        $model = Guestbook::findOne($id);
        if($model === null){
            $this->flash('error', 'Не найдено');
            return $this->redirect(['index']);
        }
		$oldAns = $model->answer;
        $model->scenario = Guestbook::SCENARIO_ANSWER;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($oldAns == '' && $model->answer != ''){
				Utilities::removeNotice('8');
				if($model->email != ''){
					$to      = $model->email;	
					$one = array('{answer}');
					$two = array($model->answer);
					MailTemplateSend::sendMail($to, $one, $two, 'answer');
				}
			}
			if($oldAns != '' && $model->answer == ''){
				Utilities::addNotice('8');
			}
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model
        ]);

    }

    public function actionDelete($id)
    {
		
        if(($model = Guestbook::findOne($id))){
			if($model->answer == ''){
				Utilities::removeNotice('8');
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
            'query' => Guestbook::find()->where(['answer' => ''])
        ]);
        return $this->render('noanswer', [
            'data' => $data
        ]);
    }
	
	
	
}