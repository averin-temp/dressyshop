<?php

namespace app\classes;
use yii\base\Object;
use Yii;


class Subscribeclass extends Object {
    public static function addSubscriber($email){
		// Ваш ключ доступа к API (из Личного Кабинета)
		$api_key = '6it4xmprdzmobef8peezzfr443dsu9fewdnkyh5o';
		$list_id = '11770513';
		
		
		
		
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
		
		//return ['ok' => true];			
	}

}