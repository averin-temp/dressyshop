<?php

namespace app\models;

use Yii;
use app\modules\settings\models\Settings;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class User extends \yii\easyii\components\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_ADMIN_LOGIN = 'admin-login';
    const SCENARIO_LOGIN = 'subscriber-login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_SOCIALS = 'socials';
    const SCENARIO_ACCOUNT_SETTINGS = 'account-settings';
    const SCENARIO_ADMIN_EDIT = 'scenario-admin-edit';
    const SCENARIO_IMAGE_UPLOAD = 'scenario-image-upload';

    const IS_ADMIN = 1;
    const IS_MANAGER = 2;
    const IS_SUBSCRIBER = 3;

    public $confirm;

    public function scenarios()
    {
        return [
            static::SCENARIO_ADMIN_LOGIN => ['username', 'password'],
            static::SCENARIO_LOGIN => ['email', 'password'],
            static::SCENARIO_REGISTER => ['email', 'password', 'confirm'],
            static::SCENARIO_SOCIALS => ['vk_id', 'facebook_id', 'odnoklassniki_id', 'google_id', 'skype_id'],
            static::SCENARIO_ACCOUNT_SETTINGS => [
                'zip_code', 'email', 'firstname', 'password', 'confirm',
                'patronymic', 'adress', 'lastname', 'preferred_delivery', 'preferred_pay_method',
                'city', 'region', 'phone', 'color', 'deferred'
            ],
            static::SCENARIO_ADMIN_EDIT => [
                'email', 'password', 'confirm', 'firstname', 'phone', 'group_id',
                'patronymic', 'photo', 'lastname', 'preferred_delivery', 'preferred_pay_method', 'city', 'region', 'role'
            ],
            static::SCENARIO_IMAGE_UPLOAD => [
                'photo'
            ]

        ];
    }


    public static function getRoles()
    {
        return [
            User::IS_ADMIN => 'Администратор',
            User::IS_MANAGER => 'Модератор',
            User::IS_SUBSCRIBER => 'Посетитель'
        ];
    }

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [
                'role',
                'required',
                'message' => 'Выберите роль',
                'on' => [static::SCENARIO_ADMIN_EDIT]
            ],
            [
                'email',
                'email',
                'skipOnEmpty' => true,
                'message' => 'Неверный адрес email',
                'on' => [
                    static::SCENARIO_REGISTER,
                    static::SCENARIO_ACCOUNT_SETTINGS,
                    static::SCENARIO_ADMIN_EDIT
                ]
            ],
            [
                'photo',
                'file',
                'extensions' => ['png', 'jpg', 'gif', 'jpeg', 'bmp'],
                'maxSize' => 2 * 1024 * 1024,
                'message' => 'Возможна загрузка только изображений png, jpg, gif, jpeg,bmp. Размером не более 2мб.',
                'tooBig' => 'Файл "{file}" слишком большой. Размер не может превышать {formattedLimit}.',
                'tooSmall' => 'Файл {file} слишком маленький. Размер не может быть меньше {formattedLimit}.',
                'wrongExtension' => 'Допустимы файлы только с расширениями: {extensions}.',
                'wrongMimeType' => 'Допустимые MIME типы файлов: {mimeTypes}.',
                'tooMany' => "Вы можете загрузить не больше {limit, number} {limit, plural, one{file} other{files}}.",
                'on' => [
                    static::SCENARIO_IMAGE_UPLOAD,
                    static::SCENARIO_ADMIN_EDIT
                ],
            ],
            [
                'email',
                'unique',
                'message' => 'Такой email уже зарегистрирован',
                'on' => [
                    static::SCENARIO_REGISTER,
                    static::SCENARIO_ACCOUNT_SETTINGS,
                    static::SCENARIO_ADMIN_EDIT
                ]
            ],
            [
                'username',
                'unique',
                'message' => 'Такой логин уже зарегистрирован',
                'on' => [
                    static::SCENARIO_REGISTER,
                    static::SCENARIO_ACCOUNT_SETTINGS,
                    static::SCENARIO_ADMIN_EDIT
                ]
            ],
            [
                ['username', 'email', 'password', 'confirm'],
                'required',
                'message' => 'Это обязательное поле',
                'on' => static::SCENARIO_REGISTER
            ],
            [
                'password',
                'compare',
                'compareAttribute' => 'confirm',
                'message' => 'Пароль и подтверждение не идентичны',
                'on' => [
                    static::SCENARIO_REGISTER,
                    static::SCENARIO_ACCOUNT_SETTINGS,
                    static::SCENARIO_ADMIN_EDIT
                ]
            ],

            [
                ['username', 'password'],
                'string',
                'length' => [4, 24],
                'message' => 'не менее 4 символов',
                'on' => [
                    static::SCENARIO_REGISTER,
                    static::SCENARIO_ACCOUNT_SETTINGS,
                    static::SCENARIO_ADMIN_EDIT
                ]
            ],
            [
                ['username', 'password'],
                'required',
                'message' => 'Заполните поле',
                'on' => static::SCENARIO_ADMIN_LOGIN
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин:',
            'email' => 'Email:',
            'password' => 'Пароль:',
            'confirm' => 'Подтверждение пароля:',
            'facebook_id' => 'Идентификатор Facebook:',
            'firstname' => 'Имя:',
            'patronymic' => 'Отчество:',
            'photo' => 'Фотография:',
            'phone' => 'Телефон:',
            'zip_code' => 'Индекс:',
            'adress' => 'Адрес:',
            'lastname' => 'Фамилия:',
            'preferred_delivery' => 'Предпочтительный способ доставки:',
            'preferred_pay_method' => 'Предпочтительный способ оплаты:',
            'odnoklassniki_id' => ' Идентификатор Одноклассники:',
            'google_id' => 'Идентификатор Google+:',
            'skype_id' => 'Идентификатор Skype:',
            'group_id' => 'Группа:',
            'role' => 'Роль:',
            'region' => 'Регион:',
            'registered' => 'Дата регистрации:',
            'city' => 'Город:',
            'last_visit' => 'Последнее посещение:'
        ];
    }

    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    public function getIsAdmin()
    {
        return $this->role == static::IS_ADMIN;
    }

    public function getIsManager()
    {
        return $this->role === static::IS_MANAGER;
    }

    public function getIsSubscriber()
    {
        return $this->role == static::IS_SUBSCRIBER;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->password = $this->hashPassword($this->password);
            } else {
                //  если пароль пуст, не меняем его,
                //  если не изменился, не шифруем повторно
                if ($this->password != '') {
                    if ($this->password != $this->oldAttributes['password'])
                        $this->password = $this->hashPassword($this->password);
                } else {
                    $this->password = $this->oldAttributes['password'];
                }

            }
            return true;
        } else {
            return false;
        }
    }


	public function getUserdisc($email){
		//return Static::findOne('3');
		//return $email;
	}
    public function getId()
    {
        return $this->id;
    }

    public function showemail()
    {
        return $this->id;
    }

    public function getAvatar()
    {
        $photo = $this->photo;
        return empty($photo) ? Url::to('@web/img/no-image.png') : $photo;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);

    }

    private function hashPassword($password)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }


    public static function findByVkID($id)
    {
        return static::findOne(['vk_id' => $id]);
    }

    public static function findBySkypeID($id)
    {
        return static::findOne(['skype_id' => $id]);
    }

    public static function findByOdnoklassnikiID($id)
    {
        return static::findOne(['odnoklassniki_id' => $id]);
    }

    public static function findByGoogleID($id)
    {
        return static::findOne(['google_id' => $id]);
    }

    public static function findByFacebookID($id)
    {
        return static::findOne(['facebook_id' => $id]);
    }
}
