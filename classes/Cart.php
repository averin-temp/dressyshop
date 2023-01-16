<?php

namespace app\classes;

use yii\base\Exception;
use yii\base\Object;
use app\models\Product;
use Yii;
use yii\helpers\ArrayHelper;

class Cart extends Object {

    const ERROR_SESSION_DISABLED = 1;


    private $_products = [];

    private static $single = null;


    public static function get()
    {
        if(self::$single === null)
        {
            $cart = new Cart();
            if(!$cart->avalible)
                throw new Exception("Сессии отключены",self::ERROR_SESSION_DISABLED);

            self::$single = $cart;
        }

        return self::$single;
    }

    public function getData()
    {

        $products = Product::findAll($this->_products);
        $products = ArrayHelper::index($products, 'id');

        $out = [];
        foreach($this->_products as $key => $id) {
            $out[$key] = $products[$id];
        }
        return $out;
    }
    public static function getnormalId($id){
        $model_id = Product::find()->where(['id'=>$id])->one();
        $prod_id = Product::find('id')->select('id')->where(['model_id'=>$model_id])->orderBy('id')->one();
        return $prod_id->id;
    }
    public function add($id)
    {
        end($this->_products);
        $key = key($this->_products) + 1;
        $this->_products[$key] = $id;

        Yii::$app->session->set('cart', $this->_products);

        $normal_id = static::getnormalId($id);
		if(!\Yii::$app->user->isGuest){
        Deferred::removeID($normal_id);
        Deferred::removeID($id);
		}
        return $key;
    }


    public function remove($key)
    {
        unset($this->_products[$key]);
        Yii::$app->session->set('cart', $this->_products);
    }

    public function init()
    {
        $session = Yii::$app->session;
        $session->open();

        if ($session->isActive)
        {
            if(!$session->has('cart'))
                $this->_products = array();
            else
                $this->_products = $session->get('cart');
        }
    }

    public function getProducts()
    {
        $output = [];
        foreach ($this->getData() as $key => $product)
        {
            array_push($output, [
                'product_id' => $product->id,
                'product_key' => $key,
                "image" => $product->image->medium,
                "price" => $product->model->price,
                'size' => $product->size->name,
                'category' => $product->model->category->caption,
                'brand' => $product->model->brand
            ]);
        }

        return $output;
    }

    public function getTotal(){

        $out = new \stdClass();
        $out->count = 0;
        $out->price = 0;
        foreach($this->data as $item){
            $out->count++;
            $out->price += $item->model->price ? $item->model->price : 0;
        }
        return $out;
    }

    public function getAvalible(){
        return $this->_products !== null;
    }

    public static function clear()
    {
        Yii::$app->session->set('cart', []);
    }

    public function getByKey($key)
    {
        return isset($this->_products[$key]) ? $this->_products[$key] : null;
    }

}