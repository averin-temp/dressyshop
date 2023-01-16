<?php
/**
 * Created by PhpStorm.
 * User: hust
 * Date: 13.05.2017
 * Time: 15:53
 */

namespace app\modules\settings\models;

use Yii;
use yii\db\ActiveRecord;
use app\classes\Formula;
use yii\helpers\Url;
use yii\web\UploadedFile;

class Settings extends ActiveRecord
{
    public function rules()
    {
        return [
            [ ['phone1', 'phone2', 'payment_description', 'delivery_description', 'banneron','homepage_text', 'facebook_url', 'google_url', 'skype_url', 'vk_url', 'table_image', 'home_banner'] , 'safe'],
            ['formula', 'match', 'pattern' => Formula::PATTERN, 'message' => 'Неправильный формат формулы'],
            ['formula_bel', 'match', 'pattern' => Formula::PATTERN, 'message' => 'Неправильный формат формулы'],
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

    public function UploadImage($attribute)
    {
        if($image = UploadedFile::getInstance($this, $attribute))
        {

            $imageForm = new TableImage(['image' => $image]);

            if($imageForm->validate()){
                $path = Yii::getAlias('@webroot/images/').$image->name;
                $this->table_image = Url::to('@web/images/').$image->name;
                $image->saveAs($path);
            }
            else
                $this->addError('table_image', $imageForm->getErrors('image'));
        }
        else
        {
            $this->table_image = $this->oldAttributes['table_image'];
        }
    }

}