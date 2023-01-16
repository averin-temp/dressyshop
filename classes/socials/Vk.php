<?php
namespace app\classes\socials;

use app\classes\socials\Socials;
use yii\helpers\Url;
use Yii;

class Vk extends Socials
{
    const CLIENT_ID = '6087726';
    const CLIENT_SECRET = 'hgQCc35BgsxUWRA66pNC';

    const AUTHORIZE_URL = 'https://oauth.vk.com/authorize';
    const METHOD_URL = 'https://api.vk.com/method/';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';

    const AUTH_ROUTE = 'account/vk_auth';
    const ADD_ROUTE = 'account/vk_add';



    public function getAddLink()
    {
        return $this->getAuthQuery(static::ADD_ROUTE);
    }

    public function getAuthLink()
    {
        return $this->getAuthQuery(static::AUTH_ROUTE);
    }

    public function getAuthQuery($route)
    {
        $redirect = Url::toRoute($route, true);

        $params = [
            'client_id' => static::CLIENT_ID,      // ID приложения

            'redirect_uri' => $redirect,   // Адрес, на который будет передан code
            // (домен указанного адреса должен соответствовать
            // основному домену в настройках приложения и
            // перечисленным значениям в списке доверенных
            // redirect uri — адреса сравниваются вплоть до path-части).

            'display' => 'page',        // Указывает тип отображения страницы авторизации.
            // Поддерживаются следующие варианты:
            // page — форма авторизации в отдельном окне;
            // popup — всплывающее окно;
            // mobile — авторизация для мобильных устройств (без использования Javascript)
            // Если пользователь авторизуется с мобильного устройства, будет использован тип mobile.

            'scope' => 'status',  // Битовая маска настроек доступа приложения, которые необходимо
            // проверить при авторизации пользователя и запросить отсутствующие.
            //
            // notify (+1)	Пользователь разрешил отправлять ему уведомления (для flash/iframe-приложений).
            // friends  (+2)	Доступ к друзьям.
            // photos (+4)	Доступ к фотографиям.
            // audio  (+8)	Доступ к аудиозаписям.
            // video (+16)	Доступ к видеозаписям.
            // pages (+128)	Доступ к wiki-страницам.
            // +256	 Добавление ссылки на приложение в меню слева.
            //   status (+1024)	Доступ к статусу пользователя.
            // notes (+2048)	Доступ к заметкам пользователя.
            // messages (+4096)	Доступ к расширенным методам работы с сообщениями (только для Standalone-приложений).
            // wall (+8192)	Доступ к обычным и расширенным методам работы со стеной.
            //              Данное право доступа по умолчанию недоступно для сайтов (игнорируется при попытке авторизации для приложений с типом «Веб-сайт» или по схеме Authorization Code Flow).
            // ads (+32768)	Доступ к расширенным методам работы с рекламным API. Доступно для авторизации по схеме Implicit Flow или Authorization Code Flow.
            // offline (+65536)	Доступ к API в любое время (при использовании этой опции параметр expires_in, возвращаемый вместе с access_token, содержит 0 — токен бессрочный). Не применяется в Open API.
            // docs (+131072)	Доступ к документам.
            // groups (+262144)	Доступ к группам пользователя.
            // notifications (+524288)	Доступ к оповещениям об ответах пользователю.
            // stats (+1048576)	Доступ к статистике групп и приложений пользователя, администратором которых он является.
            // email (+4194304)	Доступ к email пользователя.
            // market (+134217728)	Доступ к товарам.


            'response_type' => 'code',    // Тип ответа, который Вы хотите получить. Укажите code.
            'v' => '5.65', // 	Версия API, которую Вы используете. Актуальная версия: 5.65.
            'state' => ''   // Произвольная строка, которая будет возвращена вместе с результатом авторизации.

        ];

        Yii::warning("getAddLink->params:".print_r($params, true));
        return static::AUTHORIZE_URL.'?'.http_build_query($params);;

    }

    public function getAccessToken($code, $route)
    {
        $redirect = Url::toRoute($route, true);

        $params = [
            'client_id' => static::CLIENT_ID,
            'client_secret' => static::CLIENT_SECRET,
            'redirect_uri' => $redirect,
            'code' => $code
        ];

        $url = static::ACCESS_TOKEN_URL.'?'.http_build_query($params);
        $response =  $this->curl_get_contents($url);
        $response = json_decode($response, false);
        Yii::warning("getAccessToken:".print_r($response, true));
        return $response;
    }

    public function getPhoto($user, $access_token)
    {
        $params = [
            'access_token' => $access_token,
            'user_id' => $user->id,
            'fields' =>'photo_100',
            'v' => '5.65'
        ];

        Yii::warning("Vk::getPhoto:   params:".print_r($params, true));

        $data = $this->curl_get_contents(static::METHOD_URL.'users.get'.'?'.http_build_query($params));
        $data = json_decode($data, false);
        $data = $data->response[0];

        Yii::warning("getPhoto response: ".print_r($data, true));

        return $data->photo_100;
    }
}