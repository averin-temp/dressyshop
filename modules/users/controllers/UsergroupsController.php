<?php
namespace app\modules\users\controllers;

use app\models\Group;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;

class UsergroupsController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Group::find()
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Group();
        return $this->render('edit', ['model' => $model]);
    }


    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));

        if($id) {
            $model = Group::findOne($id);

            if($model === null){
                $this->flash('error', 'такой группы не существует');
                return $this->redirect(['/admin/'.$this->module->id]);
            }
        } else {
            $model = new Group();
        }


        if ($model->load(Yii::$app->request->post())) {
            if($model->validate())
            {
                $model->save();
                $this->flash('success', 'Группа сохранена');
                return $this->redirect(['/admin/'.$this->module->id.'/usergroups']);
            }

        }

        return $this->render('edit', [ 'model' => $model ]);

    }

    public function actionEdit($id)
    {
        $model = Group::findOne($id);

        if($model === null){
            $this->flash('error', 'Группа не найдена');
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('edit', [ 'model' => $model ]);
    }



    public function actionDelete($id)
    {
        if(($model = Group::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Такая группа не найдена";
        }
        return $this->formatResponse('Группа удалена');
    }
}