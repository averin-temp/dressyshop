<?php
namespace app\modules\settings\controllers;

use app\models\Banner;
use app\models\Model;
use Yii;
use yii\easyii\components\Controller;
use app\modules\settings\models\Settings;
use yii\helpers\ArrayHelper;

class CommonController extends Controller
{

    public function actionIndex()
    {
        $model = Settings::get();
        $banners = ArrayHelper::map(Banner::find()->all(), 'id', 'caption');

        return $this->render('index', [
            'model' => $model,
            'banners' => $banners
        ]);
    }

    public function actionSave()
    {

        $model = Settings::get();

        if($model->load(Yii::$app->request->post()) ) {
            $model->UploadImage('table_image');
            if($model->save())
            {
                // TODO: перерассчитать стоимость всех товаров (запросом)

                // простой перерассчет, но надо бы это делать запросом
                $models = Model::find()->all();
                foreach($models as $model)
                {
                    /** @var Model $model */
                    $model->setPurchasePrice($model->purchase_price);
                    if(!$model->save(false))
                    {
                        $this->flash('error', 'Ошибка при перерассчете');
                        return $this->redirect('index');
                    }
                }


                $this->flash('success', 'Настройки сохранены');
            }
        }


        return $this->back();

    }

}