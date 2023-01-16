<?php
namespace app\classes\socials;

use yii\base\Object;
use Yii;


class Socials extends Object
{
    public function curl_get_contents($url)
    {
        Yii::warning("Посылается запрос curl_get_contents: $url )");
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        Yii::warning("Получен результат curl_get_contents : ".print_r($data, true));
        return $data;
    }

}