<?php
namespace app\classes\socials;

use app\classes\socials\Socials;
use yii\helpers\Url;
use Yii;

class Facebook extends Socials
{
    const CLIENT_ID = '481737998838141';
    const CLIENT_SECRET = 'e634f1f6df2056ef7f100366d2177c29';

    const AUTHORIZE_URL = 'https://www.facebook.com/v2.9/dialog/oauth';
    const ACCESS_TOKEN_URL = 'https://graph.facebook.com/v2.9/oauth/access_token';
    const GRAPH = 'https://graph.facebook.com/';

    const AUTH_ROUTE = 'account/fb_auth';
    const ADD_ROUTE = 'account/fb_add';


    public function getAuthQuery($route)
    {
        $redirect = Url::toRoute($route, true);

        $params = [
            'client_id' => static::CLIENT_ID,
            'redirect_uri' => $redirect,
            'default_graph_version' => 'v2.2',
        ];

        Yii::warning("Fb getAuthQuery->params:".print_r($params, true));
        return static::AUTHORIZE_URL.'?'.http_build_query($params);
    }

    public function getAddLink()
    {
        return $this->getAuthQuery(static::ADD_ROUTE);
    }

    public function getAuthLink()
    {
        return $this->getAuthQuery(static::AUTH_ROUTE);
    }

    public function getAccessToken($code, $route)
    {
        Yii::warning("Отправка запроса access_token к Facebook (getAccessToken)");
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
        Yii::warning("Ответ от Facebook на запрос access_token: ".print_r($response, true));
        return $response;
    }


    public function getUserInfo($access_token)
    {
        $params = [
            'access_token' => $access_token,
            'fields' => 'id,name,picture'
        ];

        Yii::warning("Отправка запроса getUserInfo() к Facebook. параметры: ".print_r($params, true));

        $url = static::GRAPH.'me?'.http_build_query($params);
        $response =  $this->curl_get_contents($url);
        $response = json_decode($response, false);

        Yii::warning("Ответ запроса getUserInfo() к Facebook.".print_r($response, true));
        return $response;
    }

}