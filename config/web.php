<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '34567890',
            'baseUrl' => ''
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 86400,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [			
                'admin' => 'admin',
                'latest' => 'catalog/latest',
                'cart' => 'cart/index',
                'account' => 'account/index',
                'brand/<slug:\w+>' => 'catalog/brand',
                'admin/<controller:\w+>/<action:[\w-]+>' => 'admin/<controller>/<action>',
                'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<module>/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w-]+>' => 'admin/<module>/<controller>/<action>',
				'service/<slug:\w+>' => 'page/index',
                'page/<slug:\w+>' => 'page/redirect',
                //'<slug:\w+>.html' => 'catalog',
                'catalog/<id:\d+>' => 'catalog/redirect_product',
                'catalog/<category:.+$>' => 'catalog/index',
                'ajax/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<slug:\w+>' => 'catalog/product',
            ],
        ],
    ],
    'params' => $params,
];



if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['91.79.151.60', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return array_merge_recursive(require(__DIR__.'/../vendor/noumo/easyii/config/easyii.php'), $config);
