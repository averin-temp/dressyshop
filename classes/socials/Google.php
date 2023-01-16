<?php
namespace app\classes\socials;


class Google extends Socials
{
    const CLIENT_ID = '481737998838141';
    const CLIENT_SECRET = 'e634f1f6df2056ef7f100366d2177c29';

    const AUTHORIZE_URL = 'https://www.facebook.com/v2.9/dialog/oauth';
    const ACCESS_TOKEN_URL = 'https://graph.facebook.com/v2.9/oauth/access_token';

    public $auth_route = 'account/vk_auth';


    public function getAuthQuery()
    {


        return '';
    }

    public function getAccessToken($code)
    {
        return null;
    }

    public function getAddLink()
    {
        return '';

    }
}