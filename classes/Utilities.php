<?php
namespace app\classes;

use app\models\Category;
use app\models\CategoryTree;
use app\models\Filters;
use app\models\Model;
use app\models\Model_to_category;
use app\models\Property;
use app\models\PropertyType;
use app\models\PropertyValue;
use app\models\User;
use app\modules\products\models\ImageUpload;
use Yii;
use yii\base\Object;
use app\models\Image;
use yii\helpers\ArrayHelper;

/**
 * Class Utilities
 *
 * Вспомогательный класс для подчистки ресурсов,
 * ... и для всего остального.
 *
 * @package app\classes
 */
class Utilities extends Object
{

    /**
     * Удаляет картинки МОДЕЛЕЙ из базы вместе с файлом на диске
     *
     * @param array $images
     */
    public static function removeImages($images)
    {
        if(empty($images)) return;

        $settings = Image::picturesParams();
        $images_path = Image::uploadPath();

        $ids = [];
        foreach($images as $image) {
            /** @var Image $image */
            $ids[] = $image->id;
            $image->deletePictures();
        }
        Image::deleteAll(['id' => $ids]);
    }

    /**
     * Функция безопасного удаления пользователя.
     * Удаляет все ресурсы связанные с пользователем.
     *
     * @param User $user
     */
    public static function removeUser($user)
    {
        $photo = $user->photo;
        if(!empty($photo)) {
            $path = Yii::getAlias('@app/web').$photo;
            if(file_exists($path))
                unlink($path);
        }
        $user->delete();

    }


    /**
     * Генерирует уникальное имя в папке
     *
     * @param string $path Путь к директории где искать уникальное имя
     * @param string $ext Расширение файла
     * @param string $prefix
     * @param bool $fullpath Если true, вернет полный путь к файлу, если false - только имя
     * @return string
     */
    public static function uniqueFileName($path, $ext = '', $prefix = '', $fullpath = false)
    {
        $ext = $ext ? '.'.$ext : '';
        $tries = 1000;
        while($tries--) {
            $name = uniqid($prefix).$ext;
            $fullname = $path.$name;
            if(!file_exists($fullname))
                return $fullpath ? $fullname :  $name;
        }
    }


    /**
     * Безопасно удаляет тип свойства
     * - из настроек фильтров
     * - все значения типа
     * - все свойства типа
     *
     * @param PropertyType $propertyType
     */
    public static function removePropertyType($propertyType)
    {
        Property::deleteAll(['type_id' => $propertyType->id]);
        PropertyValue::deleteAll(['type_id' => $propertyType->id]);

        $filters = Filters::find()->all();
        foreach($filters as $filter)
        {
            $types = json_decode($filter->property_types);
            unset($types[$propertyType->id]);
            $filter->property_types = json_encode($types);
            $filter->update();
        }

        $propertyType->delete();
    }

	public static function removeNotice($id){
		$modnocice = \yii\easyii\models\Module::findOne($id);
		$modnocice->notice = $modnocice->notice - 1;
		if($modnocice->notice < 0)
			$modnocice->notice = 0;
		$modnocice->save();
	}
    public static function addNotice($id){
		$modnocice = \yii\easyii\models\Module::findOne($id);
        $modnocice->notice = $modnocice->notice + 1;
        $modnocice->save();
	}
	
    public static function addCol($value, $array)
    {
        if (!is_array($array) || !sizeof($array)) return []; #avtorkoda 16-08-2017
        $result = [];
        foreach($array as $item)
            $result[] = [ $value, $item ] ;
        return $result;
    }


    /**
     * Корректно удаляет категорию:
     *  - удаляет все дочерние категории
     *
     * @param $category
     * @return bool
     */
    public static function removeCategory($category)
    {

        $category = CategoryTree::getCategoryTree($category->id);

        $toDelete = [$category['id']];

        if(isset($category['childrens']))
            foreach($category['childrens'] as $sub_category) {

                $toDelete[] = $sub_category['id'];

                if(isset($sub_category['childrens']))
                    foreach($sub_category['childrens'] as $sub_category2)
                        $toDelete[] = $sub_category2['id'];
            }

        Category::deleteAll(['id' => $toDelete]);
        Model_to_category::removeCategory($toDelete);
    }
	
	public static function debug($var, $die = true){
		if(Yii::$app->user->identity->id == 153){
			echo '<pre>';
			var_dump($var);
			echo '<pre>';
			if($die){
				die();
			}
		}
		else{
			return false;
		}
	}
}