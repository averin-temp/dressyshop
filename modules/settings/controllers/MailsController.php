<?php

namespace app\modules\settings\controllers;


use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\easyii\components\Controller;
use app\models\Mails;

class MailsController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Mails::find()
        ]);

        return $this->render('index', ['data' => $data]);
    }

    function actionEdit($id = '')
    {
        $mail = Mails::findOne(intval($id));
        if (empty($mail)) {
            $this->flash('error', "Такого шаблона не существует");
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $mail]);

    }

    function actionSave()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $mail = Mails::findOne(intval($id));
        } else
            $mail = new Mails();

        if ($mail->load(Yii::$app->request->post()) && $mail->save()) {
            $this->flash('success', "Шаблон сохранен");
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['model' => $mail]);
    }


    function actionDelete($id = '')
    {
        $mail = Mails::findOne(intval($id));
        if (empty($mail)) {
            $this->flash('error', "Такого шаблона нет");
        }
        $mail->delete();
        $this->flash('success', "Шаблон удален");
        return $this->redirect(['index']);

    }


    function actionCreate()
    {
        $mail = new Mails();
        return $this->render('edit', ['model' => $mail]);
    }

}