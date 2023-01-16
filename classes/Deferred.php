<?php

namespace app\classes;

use app\models\Product;
use app\models\User;
use Yii;
use yii\base\Object;

class Deferred extends Object
{
    public static function getAll()
    {
        $ids = array_keys(static::get());
        $products = Product::findall($ids);
        return $products;
    }

    public static function addID($id)
    {
        $id = intval($id);
        $deferred = static::get();
        $deferred[$id] = 1;
        static::set($deferred);
    }

    /**
     * @param Product $product
     */
    public static function addProduct($product)
    {
        $id = $product->id;
        static::addID($id);
    }

    public static function removeAll(){
        static::set([]);
    }
    public static function removeID($id)
    {
        $deferred = static::get();
        unset($deferred[$id]);
        static::set($deferred);
    }

    public static function get()
    {
//        $deferred = Yii::$app->session->get('deffered');

        $model = User::findOne(Yii::$app->user->identity->id);
        $deferred = json_decode($model->deferred, true);

        $deferred = empty($deferred) ? [] : $deferred;
        return $deferred;
    }

    public static function set($deferred)
    {
//        Yii::$app->session->set('deffered', $deferred);

        $model = User::findOne(Yii::$app->user->identity->id);
        $model->deferred = json_encode($deferred);
        $model->save(false);
    }



}