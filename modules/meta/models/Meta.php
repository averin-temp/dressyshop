<?php
namespace app\modules\meta\models;

use Yii;
use yii\db\ActiveRecord;
use app\classes\Formula;
use yii\helpers\Url;
use yii\web\UploadedFile;

class Meta extends ActiveRecord
{
    public function rules()
    {
        return [
            [ ['phone1', 'phone2', 'payment_description', 'delivery_description', 'banneron','homepage_text', 'facebook_url', 'google_url', 'skype_url', 'vk_url', 'table_image', 'home_banner'] , 'safe'],
            ['formula', 'match', 'pattern' => Formula::PATTERN, 'message' => 'Неправильный формат формулы'],
            ['admin_email', 'email', 'skipOnEmpty' => true ,'message' => 'Неверный формат email']
        ];
    }


    public static function get($option = null)
    {
        $settings = self::findOne(1);

        if($option == null) return $settings;

        if(isset($settings->$option))
        return $settings->$option;

        return '';
    }
}