<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Signup form
 */
class Seo extends ActiveRecord
{

    public function rules()
    {
        return [
            [['name', 'meta_key', 'meta_description', 'title'], 'safe']
        ];
    }

    public static function SetSeo($id)
    {
        $seo = Seo::findOne($id);
        if(!$seo) return false;
        Yii::$app->view->title = trim($seo->title) ? $seo->title : '';
        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $seo->meta_description]);
        Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $seo->meta_key]);
        return true;
    }
}
