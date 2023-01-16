<?php

namespace app\controllers;
use app\models\ApplyCode;
use app\models\Group;
use app\models\Order;
use app\models\Product;
use app\models\Regions;
use app\models\User;
use Codeception\Exception\ExtensionException;
use yii\base\Exception;
use yii\web\Controller;
use app\classes\Cart;
use yii\helpers\ArrayHelper;
use app\models\Delivery;
use app\models\Pay;
use app\models\Promocode;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CartController extends Controller
{
    public function actionAjax_get()
    {
        if(Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $totalCount = Cart::get()->total->count;
            $totalPrice = Cart::get()->total->price;

            $content = $this->renderPartial('cart_items', ['products' => Cart::get()->data]);

            $code = Yii::$app->request->post('promo');

            if(!empty($code)){
                $code = trim($code);
                $promo = Promocode::findOne(['code' => $code]);

                if($promo !== null){
                    $totalPrice -= $totalPrice * ($promo->discount * 0.01);
                }
            }

            return [
                'error' => false,
                'content' => $content,
                'totalCount' => $totalCount,
                'totalPrice' => $totalPrice
            ];
        }

        return 'wrong request format';
    }


	public function actionAjax_get_page()
    {
        if(Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $totalCount = Cart::get()->total->count;
            $totalPrice = Cart::get()->total->price;

            $content = $this->renderPartial('cart_items_page', ['products' => Cart::get()->data]);

            $code = Yii::$app->request->post('promo');

            if(!empty($code)){
                $code = trim($code);
                $promo = Promocode::findOne(['code' => $code]);

                if($promo !== null){
                    $totalPrice -= $totalPrice * ($promo->discount * 0.01);
                }
            }

            return [
                'error' => false,
                'content' => $content,
                'totalCount' => $totalCount,
                'totalPrice' => $totalPrice
            ];
        }

        return 'wrong request format';
    }
	
	
    public function actionAjax_put()
    {
		
        try{
            $id = intval(Yii::$app->request->post('id'));
            $size_id = intval(Yii::$app->request->post('size'));

            $product = Product::findOne($id);

            if($product === null)
                throw new Exception("такого продукта нет");

            if($size_id){
                $product = Product::findOne([
                    'model_id' => $product->model_id,
                    'size_id' => $size_id,
                    'color_id' => $product->color_id
                ]);

                if($product === null)
                    throw new Exception("такого продукта нет");
            }


            $cart = Cart::get();
            $cart->add($product->id, 1);

            echo json_encode([
                'message' => 'товар добавлен',
                'totalCount' => Cart::get()->total->count,
                'totalPrice' => Cart::get()->total->price
            ]);
        }
        catch (Exception $ex)
        {
            echo json_encode(['error'=> false, 'error' => $ex->getCode(),'message' => $ex->getMessage()]);
        }

        return;
    }

    public function actionAjax_remove()
    {
        try{
            $cart = Cart::get();
            $id = intval(Yii::$app->request->post('key'));

            $cart->remove($id);

            return $this->actionAjax_get();
        }
        catch (Exception $ex)
        {
            return ['error' => $ex->getCode(),'content' => $ex->getMessage()];
        }
    }

    public function actionAjax_replace()
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('неверный формат запроса');

        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            $cart = Cart::get();
            $key = intval(Yii::$app->request->post('key'));
            $inputColor = intval(Yii::$app->request->post('color'));

            if(!$id = $cart->getByKey($key))
                throw new Exception("Такого ключа нет в корзине: $key");

            $product = Product::findOne($id);

            if($product === null)
                throw new Exception("Такого цвета нет");

            $cart->remove($key);

            $old = $product;
            $new = Product::find()->where([
                'model_id' => $old->model_id,
                'color_id' => $inputColor,
                'size_id' => $old->size_id,
            ])->One();

            if($new === null)
                throw new Exception("Такого цвета нет для этого размера");

            $key = $cart->add($new->id);

            return [
                'message' => 'товар заменен',
                'totalCount' => Cart::get()->total->count,
                'totalPrice' => Cart::get()->total->price,
                'id' => $new->id,
                'url' => $new->link,
                'key' => $key,
                'image' => $new->image->small,
                'vendorCode' => $new->model->vendorcode
            ];
        }
        catch (Exception $ex)
        {
            return [
                'error'=> false,
                'error' => $ex->getCode(),
                'message' => $ex->getMessage()
            ];
        }
    }


    function actionIndex()
    {
        $usergroup = Yii::$app->user->identity->group_id;
        $userdiscount = Group::getDiscount($usergroup);
        $order = new Order();
        $delivery_methods = Delivery::find()->all();
        $pay_methods = Pay::find()->all();

        $regions = Regions::find()->asArray()->orderBy('order')->all();

        $promoform = new ApplyCode();
        if($promoform->load(Yii::$app->request->post()))
            $promoform->Apply($order);

        $cartContent = $this->renderPartial('cart_items', ['products' => Cart::get()->data]);

        $totalCount = Cart::get()->total->count;
        $totalPrice = Cart::get()->total->price;
        $resultPrice = $totalPrice;

        if($order->promo) $resultPrice -= $resultPrice * ($order->promo->discount * 0.01);

        return $this->render('index', [
            'order' => $order,
            'delivery_methods' => $delivery_methods,
            'pay_methods' => $pay_methods,
            'promo' => $promoform,
            'regions' => $regions,
            'cartContent' => $cartContent,
            'totalCount' => $totalCount,
            'totalPrice' => $totalPrice,
            'resultPrice' => $resultPrice,
            'userdiscount' =>$userdiscount
        ]);
    }

}