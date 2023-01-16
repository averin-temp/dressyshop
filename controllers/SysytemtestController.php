<?php

namespace app\controllers;
use app\classes\MailTemplateSend;

use app\classes\QueryFilter;
use app\models\Answers;
use app\models\Filters;
use app\models\Model;
use app\models\Property;
use app\models\AutoBadge;
use app\models\PropertyType;
use app\modules\settings\models\Settings;
use app\models\Reviews;
use Yii;
use app\models\Mails;
use app\classes\CatalogUrl;
use app\models\Image;
use app\models\Product;
use app\classes\LastViewed;
use app\models\Size;
use app\models\User;
use app\models\Category;
use app\models\Color;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Questions;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SysytemtestController extends Controller
{
	
	public function actionIndex(){
		echo 'Hello World';
	}
	public function actionPost(){
        	@mail('ssdim4ik@mail.ru', 'Тестируем', 'Сообщение тестовое');
					
	}
	
	public function actionSender(){
		$api_key = '6it4xmprdzmobef8peezzfr443dsu9fewdnkyh5o';
		$list_id = '11770513';
		$email = 'ssdim4ik@mail.ru';
		
		
		
		
		// Данные о новом подписчике
		$user_email = $email;
		$user_lists = $list_id;

		// Создаём POST-запрос
		$POST = array (
		  'api_key' => $api_key,
		  'list_ids' => $user_lists,
		  'fields[email]' => $user_email,
		  'double_optin' => 1
		);

		// Устанавливаем соединение
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, 'https://api.unisender.com/ru/api/subscribe?format=json');
		$result = curl_exec($ch);

		if ($result) {
		  // Раскодируем ответ API-сервера
		  $jsonObj = json_decode($result);

		  if(null===$jsonObj) {
			// Ошибка в полученном ответе
			echo "Invalid JSON";

		  }
		  elseif(!empty($jsonObj->error)) {
			// Ошибка добавления пользователя
			echo "An error occured: " . $jsonObj->error . "(code: " . $jsonObj->code . ")";

		  } else {
			// Новый пользователь успешно добавлен
			echo "Added. ID is " . $jsonObj->result->person_id;

		  }
		} else {
		  // Ошибка соединения с API-сервером
		  echo "API access error";
		}
	}	
	
	
}

?>