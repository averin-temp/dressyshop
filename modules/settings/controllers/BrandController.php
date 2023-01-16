<?php
namespace app\modules\settings\controllers;


use Yii;
use app\models\Brand;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\widgets\ActiveForm;

class BrandController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Brand::find()->orderBy('order')
        ]);


        return $this->render('index', [
            'data' => $data
        ]);
    }
    public function actionSavedrag(){
        if($datacar = Yii::$app->request->post('data')){
            $data = json_decode($datacar);
            foreach ($data as $key=>$value){
                $model = Brand::findOne(intval($value->id));
                $model->order = $value->order;
                $model->save();
            }

        }
    }
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id'));

        if($id) {
            $model = Brand::findOne($id);
        } else {
            $model = new Brand();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->scenario = Brand::SCENARIO_AJAX;
            if($model->load(Yii::$app->request->post()))
                return ActiveForm::validate($model);
            else return array('empty parameters');
        }

        if($model->load(Yii::$app->request->post()))
        {
            if($image = UploadedFile::getInstance($model, 'image'))
                $model->image = $image;

            if($model->validate())
            {
                if($image)
                {
                    $filename = $image->name;
                    $path = Yii::$app->basePath.'/web/images/brand/';
                    $image->saveAs( $path.$filename );
                    $model->image = '/images/brand/'.$filename;
                } else {
                    $model->image = $model->oldAttributes['image'];
                }

                if($model->save()){
                    if(!$model->order){
                        $model->order = $model->id;
                        $model->save();
                    }
                }

                $this->flash("success", "Брэнд сохранен");
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', ['model' => $model]);

    }

    public function actionCreate()
    {
        $model = new Brand();

        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $id = intval($id);
        if($model = Brand::findOne($id))
            return $this->render('edit', ['model' => $model]);

        $this->flash('error', "Такого брэнда нет");
        return $this->redirect('index');


    }

    public function actionDelete($id = '')
    {
        $id = intval($id);
        if(($model = Brand::findOne($id))){
            $model->delete();
        } else {
            $this->error = "Брэнд не найден";
        }
        return $this->formatResponse("Брэнд удален", true);
    }

}