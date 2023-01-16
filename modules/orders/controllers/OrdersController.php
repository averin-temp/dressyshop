<?php
namespace app\modules\orders\controllers;

use app\models\Order;
use app\models\Regions;
use app\models\Product;
use app\models\Delivery;
use app\models\Pay;
use app\modules\orders\models\OrdersTableSettings;
use app\classes\MailTemplateSend;
use app\modules\orders\models\Status;
use Yii;
use app\modules\orders\models\StatusModel;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\orders\models\OrdersFilters;
use app\modules\orders\models\AdminComment;
use yii\easyii\components\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\classes\Utilities;
use app\models\User;


class OrdersController extends Controller
{

    public function actionIndex()
    {
		
		
       $settings = OrdersTableSettings::get();

        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();

        if($settings->load($post) ) {
           $settings->save();
        }

		$status = new StatusModel();
		$statuses = Status::find()->all();
        $statuses = ArrayHelper::map($statuses, 'id', 'name');
		
		
		$deliverys = Delivery::find()->all();
        $deliverys = ArrayHelper::map($deliverys, 'id', 'caption');
		
		$pays = Pay::find()->all();
        $pays = ArrayHelper::map($pays, 'id', 'caption');
		
		$regions = Regions::find()->all();
        $regions = ArrayHelper::map($regions, 'id', 'name');
		
        $query = Order::find();

        $query->joinWith(['blocked' => function($query) { $query->from(['blocked' => 'users']); }]);
        $query->joinWith(['delivery' => function($query) { $query->from(['delivery' => 'delivery']); }]);
        $query->joinWith(['pay' => function($query) { $query->from(['pay_methods' => 'pay_methods']); }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $settings->sort,
            'pagination' => []
        ]);

        $filters = new OrdersFilters();
        if($filters->load($get)) {
            $filters->validate();
            $filters->applyFilters($dataProvider->query);
        }

		
		
		
        return $this->render('index', [
            'provider' => $dataProvider,
            'filters' => $filters,
            'settings' => $settings,
			'statusModel' => $status,
			'statuses' => $statuses,
			'deliverys' => $deliverys,
			'pays' => $pays,
			'regions' => $regions,
			
        ]);
    }

    public function actionCreate($slug = null)
    {

    }

    public function actionEdit($id)
    {
		
        $order = Order::findOne($id);
		
		//die(var_dump(User::getUserdisc($order->email)));

        if($order->blocked_by_id != 0 && $order->blocked_by_id != Yii::$app->user->identity->id) {
            $this->flash('error',"раздел заблокирован");
            return $this->redirect(Yii::$app->request->referrer);
        }

        //Выставляет блокировку
        $order->blocked_by_id = Yii::$app->user->identity->id;
        $order->update();

        $products = $order->products;

        $statuses = Status::find()->all();
        $statuses = ArrayHelper::map($statuses, 'id', 'name');

        $totalPrice = '';

        foreach($products as $p)
        {
            $totalPrice += $p->model->purchase_price;
        }


        $comment = new AdminComment();
        $comment->comment = $order->admin_comment;
        if($comment->load(Yii::$app->request->post()) && $comment->validate())
        {
            $order->admin_comment = $comment->comment;
            $order->save();
        }

        $status = new StatusModel();
		
		$oldstatus = $order->status_id;
        $status->status = $order->status_id;
        if($status->load(Yii::$app->request->post()) && $status->validate())
        {
			
            $order->status_id = $status->status;
			if($oldstatus == 10 && $order->status_id != 10){
				Utilities::removeNotice('20');
							}
			if($oldstatus != 10 && $order->status_id == 10){
				Utilities::addNotice('20');		
			}
			if($order->status_id != 10 && $order->status_id != 17){
				$to      = $order->email;	
				$one = array('{order_number}');
				$two = array($order->order_number);
				
				if($order->status_id == 15){
					array_push($one, '{order_track}');
					array_push($two, $order->status_add);
				}
				
				$sub_one = array('{order_number}');
				$sub_two = array($order->order_number);
				MailTemplateSend::sendMail($to, $one, $two, 'status_'.$order->status_id, $sub_one, $sub_two);
			}
				
				
			$order->status_add = $status->status_add;
            $order->save();
			return $this->redirect(['/admin/orders/orders/edit/'.$order->id]);
        }


        return $this->render( 'edit', [
            'order' => $order ,
            'products' => $products,
            'statuses' => $statuses,
            'totalPrice' => $totalPrice,
            'comment' => $comment,
            'statusModel' => $status
        ]);
    }

    public function actionDelete($id)
    {

    }


    function actionDeblockall()
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException("неверный формат запроса");

        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Yii::$app->user->identity;
        $res = Yii::$app->user->identity->isAdmin;
        if(!Yii::$app->user->identity->isAdmin)
            return ['error' => true, 'message' => 'Вы не имеете прав для этого действия.'];

        Order::updateAll(['blocked_by_id' => null]);
        return ['ok' => true];
    }


    function actionDeblock($id)
    {
        $id = intval($id);

        try{

            if(!$order = Order::findOne($id))
                throw new Exception("Такого заказа не существует");

            if(!$order->blocked_by_id)
                throw new Exception("Заказ не заблокирован");

            if($order->blocked_by_id != Yii::$app->user->identity->id)
                throw new Exception("Вы не можете снять блокировку");

            $order->blocked_by_id = null;
            $order->update();

            return $this->redirect(['index']);

        }
        catch(Exception $e)
        {
            $this->error = $e->getMessage();
            return $this->formatResponse();
        }









    }


    function actionCancel()
    {
        $id = Yii::$app->request->post('id');
        $comment = Yii::$app->request->post('comment');

        $message = 'ошибка отмены заказа';

        if($order = Order::findOne($id))
        {
            $order->canceled = 1;
            $order->cancel_comment = $comment;
            if($order->save())
                $message = "заказ отменен";
        }

        return $this->render('cancel', ['id' => $id, 'comment' => $comment, 'message' => $message]);

    }


    function actionDelprod()
    {
        $id = Yii::$app->request->post('id');
    }


}