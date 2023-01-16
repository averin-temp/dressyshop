<?php
/**
 * Created by PhpStorm.
 * User: hust
 * Date: 13.05.2017
 * Time: 14:33
 */

namespace app\classes;

use app\models\Product;
use Yii;

class LastViewed
{
    public static function add($product)
    {
        $lastViewed = self::get();

        $lastViewed[] = $product->id;
        $lastViewed = array_slice($lastViewed, -5);

        Yii::$app->session->set('last_viewed', $lastViewed);
    }

    public static function get()
    {
        $session = Yii::$app->session;
        $session->open();

        if(!$lastViewed = $session->get('last_viewed'))
            $lastViewed = [];

        return $lastViewed;
    }

    public static function getProducts()
    {
        return Product::findAll( self::get() );
    }
}