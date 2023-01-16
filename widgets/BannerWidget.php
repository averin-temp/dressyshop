<?php

namespace app\widgets;

use app\models\Banner;
use app\modules\settings\models\Settings;
use yii\bootstrap\Widget;

class BannerWidget extends Widget
{
    public function run()
    {
        $query = Banner::find()->where(['enable' => 1]);

        $home_id = Settings::get('home_banner');
        if($home_id) $query->andWhere(['<>', 'id', $home_id]);

        $banners = $query->all();

        return $this->render('banners' , [ 'banners' => $banners ] );
    }
}