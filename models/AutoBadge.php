<?php

namespace app\models;


use yii\db\ActiveRecord;

class AutoBadge extends ActiveRecord
{
    const SCENARIO_NEW = 'new';
    const SCENARIO_POPULAR = 'pop';

    public static function tableName()
    {
        return 'auto_badges';
    }


    public function rules()
    {
        return [
            [['name', 'viewcount', 'days', 'text', 'css', 'class'],'trim'],
            ['name', 'required'],
            [ 'viewcount' , 'required', 'on' => self::SCENARIO_POPULAR],
            [ 'days', 'required', 'on' => self::SCENARIO_NEW],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
        ];
    }


    /**
     * @param Model $model
     * @return null|ActiveRecord
     */
    public static function getBadge($model)
    {
        $autobadges = AutoBadge::find()->all();

        $latest = $autobadges[0];
        $days = $latest->days;
        $interval = date_interval_create_from_date_string("$days days");
        $current = date_create();
        $lowerDate = date_sub( $current, $interval);
        $added = date_create_from_format("Y-m-d H:i:s", $model->added );
        if($lowerDate < $added)
            return $latest;

        $popular = $autobadges[1];
        if($popular->viewcount < $model->viewed)
            return $popular;

        return null;
    }

}