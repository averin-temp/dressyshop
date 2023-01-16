<?php

namespace app\controllers;

use app\classes\socials\Vk;
use app\classes\socials\Facebook;
use app\classes\socials\Google;
use app\classes\socials\Odnoklassniki;
use app\models\Delivery;
use app\models\Color;
use app\models\Group;
use app\models\Pay;
use app\models\Regions;
use Yii;
use yii\web\Response;
use app\classes\Deferred;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Order;


class AccountController extends Controller
{
    public function actionIndex()
    {
        $usergroup = Yii::$app->user->identity->group_id;
        $userdiscount = Group::getDiscount($usergroup);
        $regions = Regions::find()->asArray()->orderBy('order')->all();
        if(Yii::$app->user->isGuest)
            return $this->goHome();


        $user = Yii::$app->user->identity;
        $user->scenario = User::SCENARIO_ACCOUNT_SETTINGS;

        $products = Deferred::getAll();
        $delivery = Delivery::find()->all();
        $payment = Pay::find()->all();

        $orders = Order::find()->where(['email' => $user->email])->andWhere(['!=', 'cost', '0'])->all();

        $delivery = ArrayHelper::map($delivery, 'id', 'caption');
        $payment = ArrayHelper::map($payment, 'id', 'caption');

        $colors = Color::getAll();
        return $this->render('index', [
            'clrs' => $colors,
            'user' => $user,
            'products' => $products,
            'delivery' => $delivery,
            'regions' => $regions,
            'payment' => $payment,
            'orders' => $orders,
            'userdiscount' => $userdiscount
        ]);
    }


    public function actionSave()
    {
        $request = Yii::$app->request;
        $id = intval($request->post('id'));

        if(!$id) throw new NotFoundHttpException("отсутствует идентификатор");

        $user = User::findOne($id);
        if($user === null)
            throw new NotFoundHttpException("Такой пользователь не найден");

        $user->scenario = User::SCENARIO_ACCOUNT_SETTINGS;
        if($user->load($request->post()) ) {
            $user->save();
        }



        $products = Deferred::getAll();
        $delivery = Delivery::find()->all();
        $payment = Pay::find()->all();
        $colors = Color::getAll();
        $orders = Order::findAll(['owner_id' => $user->id]);


        $delivery = ArrayHelper::map($delivery, 'id', 'caption');
        $payment = ArrayHelper::map($payment, 'id', 'caption');

        return $this->redirect(Url::to('/account'));
//        return $this->render('index', [
//			'clrs' => $colors,
//            'user' => $user,
//            'products' => $products,
//            'delivery' => $delivery,
//            'payment' => $payment,
//            'orders' => $orders
//        ]);

    }

    public function actionDeferred()
    {
        return $this->actionIndex();
    }

    public function actionHistory()
    {
        return $this->actionIndex();
    }

    public function actionPostpound($id)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->get('id');

            Deferred::addID($id);

            return [ 'error' => false, 'message' => 'Товар добавлен' ];
        }

    }

    public function actionSettings()
    {
        return $this->actionIndex();
    }


    public function actionUpload()
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException("неверный формат запроса");

        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Yii::$app->user->identity;

        if($image = UploadedFile::getInstanceByName('user-photo')) {

            $user->scenario = User::SCENARIO_IMAGE_UPLOAD;
            $user->photo = $image;

            if($user->validate())
            {
                $filename = Yii::getAlias('@app') . '/web/images/users/' . $image->name;
                $image->saveAs($filename);
                $url = Url::to('@web/images/users/' . $image->name);
                $user->photo = $url;
                $user->update(false);
                return ['ok' => true, 'url' => $url];
            }
            else
            {
                return ['ok' => false, 'message' => $user->getErrors('photo')];
            }
        }
        return ['ok' => false, 'message' => 'Файл не загружен'];
    }


    // Vk

    /**
     * Принимает ответ на запрос авторизации
     *
     * @param string $code
     * @param string $error
     * @param string $error_description
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionVk_auth($code = '', $error = '', $error_description = '')
    {
        if(!empty($error)) {
            Yii::warning("actionOnvkauth: $error : $error_description");
            throw new NotFoundHttpException("actionOnvkauth: $error : $error_description");
        }

        $vk = new Vk();

        $data = $vk->getAccessToken($code, Vk::AUTH_ROUTE);

        if(!$user = User::findByVkID($data->user_id))
        {
            $user = new User(['vk_id' => $data->user_id]);
        }

        $user->scenario = User::SCENARIO_SOCIALS;

        if($user->photo == '' && $photo = $vk->getPhoto($user, $data->access_token)){
            $user->photo = $photo;
        }

        $user->save();


        Yii::$app->user->login($user);
        Yii::$app->session->set('access_token', $data->access_token);
        Yii::$app->session->set('expires_in', $data->expires_in);

        return $this->redirect(Url::to('/account/settings'));
    }


    /**
     * Принимает ответ на запрос авторизации, для добавления сети к аккаунту
     *
     * @param string $code
     * @param string $error
     * @param string $error_description
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionVk_add($code = '', $error = '', $error_description = '')
    {

        // только для зарегистрированных пользователей
        if(Yii::$app->user->isGuest)
            return $this->goHome();

        // Если пользователь уже имеет аккаунт vk
        $user = Yii::$app->user->identity;
        if(!empty($user->vk_id))
            return $this->goBack();

        if(empty($code)) {
            Yii::warning("actionVk_add: $error : $error_description");
            throw new NotFoundHttpException("actionVk_add: $error : $error_description");
        }

        $vk = new Vk();

        $data = $vk->getAccessToken($code, Vk::ADD_ROUTE);

        Yii::warning("actionVk_add: Добавляется аккаунт с ID:".$data->user_id);
        if($user_vk = User::findByVkID($data->user_id)) {
            throw new NotFoundHttpException("Аккаунт, прикрепленный к аккаунту VK этого пользователя уже существует.");
        }

        $user->scenario = User::SCENARIO_SOCIALS;
        $user->vk_id = $data->user_id;
        $user->save();

        return $this->redirect(Url::to('/account/settings'));
    }




    // Facebook


    /**
     * Принимает ответ на запрос авторизации
     *
     * @param string $code
     * @param string $error_reason
     * @param string $error
     * @param string $error_description
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionFb_auth($code = '', $error_reason = '', $error = '', $error_description = '')
    {
        Yii::warning("Ответ от Facebook (actionFb_auth) : ".print_r($_GET, true));

        if(empty($code))
            throw new NotFoundHttpException("Ответ от Facebook auth: $error_reason . $error : $error_description");

        $facebook = new Facebook();
        $data = $facebook->getAccessToken($code, Facebook::AUTH_ROUTE);

        /*
        stdClass Object
        (
            [access_token] => хххххх
            [token_type] => bearer
            [expires_in] => секунд
        )
        */

        $user_info = $facebook->getUserInfo($data->access_token);

        if(isset($user_info->error))
            throw new NotFoundHttpException("Facebook auth: $error_reason . $error : $error_description");

        Yii::warning(print_r($user_info, true));

        if(!$user = User::findByFacebookID($user_info->id))
        {
            Yii::warning("Не найден пользователь с Facebook ID[".$user_info->id."]      user:");
            $user = new User(['facebook_id' => $user_info->id]);
        }

        $user->scenario = User::SCENARIO_SOCIALS;

        if($user->photo == '' && $photo = $user_info->picture->data->url){
            $user->photo = $photo;
        }

        if($user->username == '' && $name = $user_info->name){
            $user->username = $name;
        }

        // Есди ничего не поменялось сохраняться все равно не будет
        $user->save();

        Yii::$app->user->login($user);
        Yii::$app->session->set('access_token', $data->access_token);
        Yii::$app->session->set('expires_in', $data->expires_in);

        return $this->redirect(Url::to('/account/settings'));
    }


    /**
     * Принимает ответ на запрос авторизации при добавлении сети
     */
    public function actionFb_add($code = '', $error_reason = '', $error = '', $error_description = '')
    {
        // только для зарегистрированных пользователей
        if(Yii::$app->user->isGuest)
            return $this->goHome();

        // Если пользователь уже имеет аккаунт vk
        $user = Yii::$app->user->identity;
        $user->scenario = User::SCENARIO_SOCIALS;
        if(!empty($user->facebook_id))
            return $this->goBack();

        if(empty($code)) {
            Yii::warning("actionVk_add: $error : $error_description");
            throw new NotFoundHttpException("actionVk_add: $error : $error_description");
        }

        $facebook = new Facebook();

        $data = $facebook->getAccessToken($code, Facebook::ADD_ROUTE);

        $user_info = $facebook->getUserInfo($data->access_token);

        if(!isset($user_info->id))
        {
            Yii::warning('user_info:'.print_r($user_info, true));
            throw new NotFoundHttpException("something wrong!");
        }



        Yii::warning("actionFb_add: Добавляется аккаунт с ID:".$user_info->id);
        if($exist = User::findByFacebookID($user_info->id)) {
            throw new NotFoundHttpException("Аккаунт, прикрепленный к аккаунту Facebook этого пользователя уже существует.");
        }


        $user->facebook_id = $user_info->id;

        if($user->photo == '' && $photo = $user_info->picture->data->url){
            $user->photo = $photo;
        }

        if($user->username == '' && $name = $user_info->name){
            $user->username = $name;
        }

        $user->save();

        return $this->redirect(Url::to('/account/settings'));
    }






    // Odnoklassniki
    /**
     * Принимает ответ на запрос авторизации
     */
    public function actionOd_auth()
    {

    }


    /**
     * Принимает ответ на запрос авторизации при добавлении сети
     */
    public function actionOd_add()
    {

    }


    //  google
    /**
     * Принимает ответ на запрос авторизации
     */
    public function actionGo_auth()
    {

    }


    /**
     * Принимает ответ на запрос авторизации при добавлении сети
     */
    public function actionGo_add()
    {

    }





}