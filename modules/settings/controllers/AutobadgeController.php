<?php

namespace app\modules\settings\controllers;

use app\models\AutoBadge;
use Yii;
use yii\easyii\components\Controller;
use yii\web\UploadedFile;

class AutobadgeController extends Controller
{
    function actionEdit($id = '')
    {
        $badge = AutoBadge::findOne($id);
        if($id === '1') $badge->scenario = AutoBadge::SCENARIO_NEW;
        if($id === '2') $badge->scenario = AutoBadge::SCENARIO_POPULAR;
        return $this->render('edit'.$id, ['model' => $badge]);

    }

    function actionSave()
    {
        $id = Yii::$app->request->post('id');

        if($badge = AutoBadge::findOne($id))
        {
            if($id === '1') $badge->scenario = AutoBadge::SCENARIO_NEW;
            if($id === '2') $badge->scenario = AutoBadge::SCENARIO_POPULAR;


            if($badge->load(Yii::$app->request->post()))
            {
                if($file = UploadedFile::getInstance($badge, 'image'))
                    $badge->image = $file;

                if($badge->validate())
                {
                    if($file)
                    {
                        $filename = $badge->image->baseName . '.' . $badge->image->extension;
                        $path = Yii::$app->basePath.'/web/images/badges/';
                        $badge->image->saveAs( $path.$filename );
                        $badge->image = $filename;
                    } else {
                        $badge->image = $badge->oldAttributes['image'];
                    }
                    $badge->save();
                    return $this->redirect(['badges/index']);
                }
            }
        }

        return $this->redirect(['badges/index']);
    }

}