<?php
namespace app\classes\socials;

class Odnoklassniki extends Socials
{
    const CLIENT_ID = '1251588096';
    const PUBLIC_KEY = 'CBAJLKJLEBABABABA';
    const SECRET_KEY = 'AECE39BD0B9A640F3F909E24';

    const AUTHORIZE_URL = 'https://www.facebook.com/v2.9/dialog/oauth';
    const ACCESS_TOKEN_URL = 'https://graph.facebook.com/v2.9/oauth/access_token';

    public $auth_route = 'account/vk_auth';


    public function getAuthLink()
    {


        return '';
    }

    public function getAccessToken($code)
    {

    }

    public function getAddLink()
    {
        return '';

    }
}