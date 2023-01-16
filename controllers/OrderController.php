<?php

namespace app\controllers;

use app\models\Mails;
use app\models\Seo;
use app\models\SignupForm;
use app\modules\settings\models\Settings;
use app\classes\MailTemplateSend;
use Yii;
use app\models\Order;
use yii\easyii\helpers\Mail;
use yii\gii\Module;
use yii\web\Controller;
use yii\helpers\Url;
use app\classes\Cart;
use app\classes\Utilities;
use yii\helpers\ArrayHelper;
use app\models\Promocode;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;


class OrderController extends Controller
{
    function actionOrderthanks()
    {
        $products = json_decode($_GET['products']);

        if (!Seo::SetSeo(3)) $this->title = 'Спасибо за заказ';
        return $this->render('index');
    }

    function actionOrder()
    {
        if (Yii::$app->user->identity->id == null) {
            $user_id = null;
        } else {
            $user_id = Yii::$app->user->identity->id;
        }


        $order = new Order();
        $products = Cart::get()->data;

        $data = ArrayHelper::getColumn($products, 'id');
        $order->products_id = json_encode($data);


        if ($order->load(Yii::$app->request->post())) {


            $summ = 0;
            foreach ($products as $p)
                $summ += $p->model->final_price;


            if ($promo = Promocode::findOne(['code' => $order->promocode])) {
                $order->promocode = $promo->id;
                $summ -= $summ * ($promo->discount * 0.01);
            } else $order->promocode = null;

            $order->cost = $summ;
            $order->status_id = 10;
            if ($order->save()) {
				$order->order_number = $order->id + 1000;
				$order->fullcost = $order->cost + $order->delivery_price;
				$order->save();
                Cart::clear();
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($order);
            }


            $orderid = $order->id + 1000;

            if (!$user_id) {
                $model = new SignupForm();

                $npass = substr(md5(rand(0, mt_getrandmax())), 0, 20);
                if (!$model->load(Yii::$app->request->post(), ''))
                    throw new Exception("Нет данных");
                $model->email = Yii::$app->request->post('Order')['email'];
                $model->password = $npass;
                $model->confirm = $npass;

                $model->lastname = Yii::$app->request->post('Order')['lastname'];
                $order->status_id = 10;
                $model->phone = Yii::$app->request->post('Order')['phone'];
                $model->firstname = Yii::$app->request->post('Order')['firstname'];
                $model->patronymic = Yii::$app->request->post('Order')['patronymic'];
                $model->zip_code = Yii::$app->request->post('Order')['zip_code'];
                $model->region = Yii::$app->request->post('Order')['region'];
                $model->city = Yii::$app->request->post('Order')['city'];
                $model->adress = Yii::$app->request->post('Order')['adress'];

                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {

                        Yii::warning("Отправка письма на " . $user->email);

						$to      = $user->email;	
						$one = array('{email}');
						$two = array($user->email);
						MailTemplateSend::sendMail($to, $one, $two, 'registration');
					
                    }
                }
            }


			$to      = $order->email;	
			$one = array('{order_number}');
			$two = array($order->order_number);
				
			$sub_one = array('{order_number}');
			$sub_two = array($order->order_number);
			MailTemplateSend::sendMail($to, $one, $two, 'new_order', $sub_one, $sub_two);
					
            Utilities::addNotice('20');
			
			
				
            $this->redirect(array(Url::to(['order/orderthanks']), 'order_id' => $orderid, 'order_name' => $order->firstname, 'products' => $order->products_id));

        }

    }


}