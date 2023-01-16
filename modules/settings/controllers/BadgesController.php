<?php
namespace app\modules\settings\controllers;

use app\models\AutoBadge;
use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;
use app\models\Badge;
use yii\web\UploadedFile;

class BadgesController extends Controller
{

    public function actionIndex()
    {
        $custom = new ActiveDataProvider([
            'query' => Badge::find()
        ]);

        $auto = new ActiveDataProvider([
            'query' => AutoBadge::find()
        ]);

        return $this->render('index', [
            'custom' => $custom,
            'auto' => $auto
        ]);
    }

    public function actionSave()
    {
        if($id = Yii::$app->request->post('id'))
        {
            $model = Badge::findOne($id);
        }
        else
        {
            $model = new Badge();
        }


        if($model->load(Yii::$app->request->post()))
        {
            if($file = UploadedFile::getInstance($model, 'image'))
                $model->image = $file;

            if($model->validate())
            {
                if($file)
                {
                    $filename = $model->image->baseName . '.' . $model->image->extension;
                    $path = Yii::$app->basePath.'/web/images/badges/';
                    $model->image->saveAs( $path.$filename );
                    $model->image = $filename;
                } else {
                    $model->image = $model->oldAttributes['image'];
                }

                $model->save();
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Badge();

        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = Badge::findOne($id);

        return $this->render('edit', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        if(($model = Badge::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Бэйдж не найден";
        }
        return $this->formatResponse("Бэйдж удален", true);
    }

}