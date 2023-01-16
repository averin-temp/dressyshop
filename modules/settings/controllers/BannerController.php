<?php
namespace app\modules\settings\controllers;

use Yii;
use app\models\Banner;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\web\UploadedFile;

class BannerController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Banner::find()
        ]);


        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionSave()
    {
        if($id = Yii::$app->request->post('id'))
        {
            $model = Banner::findOne($id);
        }
        else
        {
            $model = new Banner();
        }


        if($model->load(Yii::$app->request->post()))
        {
            if($image = UploadedFile::getInstance($model, 'image'))
                $model->image = $image;
            if($parallax_image = UploadedFile::getInstance($model, 'parallax_image'))
                $model->parallax_image = $parallax_image;

            if($model->validate())
            {
                if($image)
                {
                    $filename = $image->name;
                    $path = Yii::$app->basePath.'/web/images/banner/';
                    $image->saveAs( $path.$filename );
                    $model->image = '/images/banner/'.$filename;
                } else {
                    $model->image = $model->oldAttributes['image'];
                }

                if($parallax_image)
                {
                    $filename = $parallax_image->name;
                    $path = Yii::$app->basePath.'/web/images/banner/';
                    $parallax_image->saveAs( $path.$filename );
                    $model->parallax_image = '/images/banner/'.$filename;
                } else {
                    $model->parallax_image = $model->oldAttributes['parallax_image'];
                }
                $model->save();
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Banner();

        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = Banner::findOne($id);

        return $this->render('edit', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        if(($model = Banner::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Баннер не найден";
        }
        return $this->formatResponse("Баннер удален", true);
    }

}