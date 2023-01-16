<?php

namespace app\widgets;

use app\models\Banner;
use app\modules\settings\models\Settings;

class HomeBanner extends \yii\bootstrap\Widget
{
    public function run()
    {
        $banner = Settings::get('home_banner');
        if($banner) {
            $banner = Banner::findOne($banner);
            if($banner)
                return $this->render("homebanner", [ "banner" => $banner ] );
        } else return '';
    }
}