<?php

$params = require __DIR__ . '/params.local.php';
$db = require __DIR__ . '/db.local.php';

$config = [
    'id' => 'basic',
    'name' => 'MatichonBook',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'th-TH',
    'timezone' => 'Asia/Bangkok',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/uploads/tmp',
            'uploadUrl' => '@web/uploads/tmp',
            'imageAllowExtensions' => ['jpg', 'png'],
            'useAbsoluteUrl' => true,
        ],
        'social' => [
            'class' => 'kartik\social\Module',
            'facebook' => [
                'appId' => '2079078122120222',
                'secret' => 'a1ca9281f64062bb51f68363ba82b16b',
            ],
            //339696118200-83t86d12k77nvavrhohdmt11tb0hvn9i.apps.googleusercontent.com
            //KWaPZerpqHfy4SGgo6ciofPX
            'google' => [
                'clientId' => '621438965247-2v5mva6js2n639876lmklbsqb55h84sv.apps.googleusercontent.com',
            ],
            'googleAnalytics' => [
                'id' => 'UA-120397534-1',
                'domain' => 'demo.sukung.com',
            ],
        ],
    ],
    'components' => [
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6Lcmm04UAAAAAHTz_OolcRFwB_j6bQdJdaTbjItt',
            'secret' => '6Lcmm04UAAAAAJccExkp2kCJCIETXB-agzuosRr0',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '2079078122120222',
                    'clientSecret' => 'a1ca9281f64062bb51f68363ba82b16b',
                    'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
                ],
                'google-plus' => [
                    'class' => 'yii\authclient\clients\Google',
                    'title' => 'Google',
                    'clientId' => '621438965247-2v5mva6js2n639876lmklbsqb55h84sv.apps.googleusercontent.com',
                    'clientSecret' => 'qZy-7Z8KAzou6zQRlyPj1zev',
                     'returnUrl' => 'https://matichonbook.com/site/auth?authclient=google-plus',
                ],
                /*
                'line' => [
                    'class' => 'app\components\Line',
                    'title' => 'Line',
                    'clientId' => '1581961243',
                    'clientSecret' => '1c6a9a30a3c68174d5dc294572eb70a4',
                ],*/
            ],
        ],
        'formatter' => [
            'defaultTimeZone' => 'Asia/Bangkok',
            'nullDisplay' => '',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'kJlcSxuBkfi2X9nFn0DShVNC0LahT5xV',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Member',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        //'exceptionView' => '@app/views/site/exception.php',
        //'errorView' => '@app/views/site/error.php',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/mail.log',
                    'categories' => [
                        'mail',
                    ],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/kbank.log',
                    'logVars' => ['_POST'],
                    'categories' => [
                        'kbank-*',
                    ],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'p/<id:\d+>/<name>',
                    'route' => 'product/view',
                    'suffix' => '.html',
                ],
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
        'allowedIPs' => ['180.183.42.198'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
