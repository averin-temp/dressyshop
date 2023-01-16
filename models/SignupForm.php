<?php
namespace app\models;

use yii\base\Model as Submodel;
use Yii;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Submodel
{
    public $email;
    public $lastname;
    public $phone;
    public $patronymic;
    public $firstname;
    public $zip_code;
    public $region;
    public $adress;
    public $city;
    public $password;
    public $confirm;

    public function rules()
    {
        return [
            [['email','lastname', 'phone', 'firstname', 'patronymic', 'zip_code', 'region', 'city', 'adress'], 'trim'],
            [['email', 'password', 'confirm'], 'required'],
            [['password'], 'string', 'length' => [4, 24]],
            ['password', 'compare', 'compareAttribute' => 'confirm'],
            ['email', 'email'],
            [['email'], function($attribute, $params){
                if($founded = User::find()->where([$attribute => $this->$attribute])->One())
                    $this->addError($attribute, "Такой $attribute уже зарегистрирован");
            }]
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->email = $this->email;
        $user->password = $this->password;

        $user->lastname = $this->lastname;
        $user->phone = $this->phone;
        $user->firstname = $this->firstname;
        $user->patronymic = $this->patronymic;
        $user->zip_code = $this->zip_code;
        $user->city = $this->city;
        $user->adress = $this->adress;
        $user->region = $this->region;


        return $user->save(false) ? $user : null;
    }
}
