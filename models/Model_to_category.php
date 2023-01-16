<?php
namespace app\models;

use app\classes\Utilities;
use yii\base\Object;
use yii\db\ActiveRecord;
use yii\db\mssql\QueryBuilder;
use yii\db\Query;
use Yii;

class Model_to_category extends ActiveRecord
{

    /**
     * Удаляет все записи с указанным ID модели
     *
     * @param $id
     */
    public static function removeModel($id)
    {
        static::deleteAll(['model_id' => $id]);
    }

    /**
     * Удаляет все записи с указанной моделью и создает новые, с указанными категориями.
     *
     * @param $model_id
     * @param array $categories массив ID категорий
     */
    public static function saveCategories($model_id, $categories = [])
    {
        static::removeModel($model_id);

        Yii::$app->db->createCommand()
            ->batchInsert(
                static::tableName(),
                ['model_id', 'cat_id'],
                Utilities::addCol($model_id, $categories)
            )->execute();
    }

    /**
     * Удаляет все записи с указанным ID категории.
     *
     * @param Object|array $category
     */
    public static function removeCategory($category)
    {
        static::deleteAll(['cat_id' => $category]);
    }

}