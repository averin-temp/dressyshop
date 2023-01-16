<?php
namespace app\controllers;

use app\classes\Subscribeclass;
use app\classes\Search;
use app\classes\MailTemplateSend;
use app\models\AutoBadge;
use app\models\Model;
use app\models\Subscribe;
use Yii;
use app\models\Mails;
use app\modules\settings\models\Settings;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\User;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use app\models\Product;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionIndex()
    {

        return $this->render('home', [
            "news" => ''
        ]);
    }


    public function actionLogin()
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Неверный формат запроса');
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!\Yii::$app->user->isGuest) {
            return ['error' => true, 'content' => 'Вы уже залогинены'];
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(),'') && $model->login()) {
            return ['ok' => true];
        } else {
            return ['error' => true, 'content' => 'Неверный email или пароль'];
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	
	 public function actionSuccess(){
		return $this->render('success');
	 }

	 
	 
	 public function actionRemember(){
		
		if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Неверный формат запроса');
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
			$em = Yii::$app->request->post('email');
			
			if($res = User::find()->where(['email'=>$em])->one()){				
		
				
				$res = User::find()->where(['email'=>$em])->one();

			
				$res->scenario = User::SCENARIO_ADMIN_EDIT;
				
				$npass=substr(md5(rand(0,mt_getrandmax())),0,20);
				
				$res->password = $npass;
				$res->save(false);
				
				
				/* to ***************************/
				$to      = $em;	
				$one = array('{email}','{password}');
				$two = array($em,$npass);
				
				MailTemplateSend::sendMail($to, $one, $two, 'remember');

				return 'ok';
		
            return ['ok' => true, 'email'=>Yii::$app->request->post('email')];
			}
        }
        catch(Exception $e)
        {
            return ['error' => true, 'message' => $e->getMessage()];
        }

        return ['error' => true, 'content' => $model->errors];
	 }

    /**
     * Ajax регистрация пользователей
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionSignup()
    {


        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Неверный формат запроса');
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{

            $model = new SignupForm();

            if(!$model->load(Yii::$app->request->post(),''))
                throw new Exception("Нет данных");

            if($user = $model->signup())
            {
                if(Yii::$app->getUser()->login($user)) {

                    Yii::warning("Отправка письма на ".$user->email);
						
					/* to ***************************/
					$to      = $user->email;	
					$one = array('{email}');
					$two = array($user->email);
					MailTemplateSend::sendMail($to, $one, $two, 'registration');


                    return ['ok' => true, 'email'=>$user->email];
                }
            }
        }
        catch(Exception $e)
        {
            return ['error' => true, 'message' => $e->getMessage()];
        }

        return ['error' => true, 'content' => $model->errors];
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionCatalog()
    {

    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionTest(){

        $model = new Test();

        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            return $this->goHome();
        }

        return $this->render('test', ['model' => $model]);

    }


    /**
     * Обрабатывает ajax запрос от форм поиска, возвращает готовый HTML.
     */
    public function actionSearch()
    {
        $search = Yii::$app->request->post('search');
        $result = Search::All($search);
        $content = $this->renderPartial('search_results', ['data' => $result]);
        echo json_encode(['error' => false, "content" => $content]);
        return;

    }


    function actionAjaxLatest()
    {
        $latest = AutoBadge::findOne(1);
        $days = $latest->days;

        $interval = date_interval_create_from_date_string("$days days");
        $current = date_create();

        $lowerDate = date_sub( $current, $interval);
        $mysqlDate = $lowerDate->format('Y-m-d H:i:s');



        // рабочий запрос того что нужно
        // "select category.id , (select count(model.id) from model where id=category.id) from category"




        $subquery = new Query();
        $subquery->from('model')->where(['category.id'=> 'model.id'])->count();

        $query = new Query();
        $categories = $query->select(['category.id', $subquery ])->from('category')->all();

/*
        foreach($models as $model)
        {
            if($parent = $model->category->parent_id)
            {

            }
            $categories['']
        }
*/


        return [];
    }



    function actionAuth()
    {

        return $this->redirect('https://oauth.vk.com/authorize');
    }




    function actionSubscribe()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $subscribe = new Subscribe();
            if($subscribe->load(Yii::$app->request->post()))
            {
                if(!Subscribe::findOne(['email' => $subscribe->email])) {
                    $subscribe->save();
					Subscribeclass::addSubscriber($subscribe->email);
					return ['ok' => true];					
                }
				else{
					return ['nook' => true];
				}

                
            }

            //return ActiveForm::validate($subscribe);

        }

        return $this->goHome();
    }

}
