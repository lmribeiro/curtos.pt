<?php

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';
$mailer = require __DIR__.'/mailer.php';
$log = require __DIR__.'/log.php';

$config = [
    'id' => 'lribeiro-curtos',
    'name' => 'Curtos.pt',
    'charset' => 'UTF-8',
    'timeZone' => 'Europe/Lisbon',
    'language' => 'pt',
    'sourceLanguage' => 'pt-PT',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Lisbon',
            'defaultTimeZone' => 'Europe/Lisbon',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'dateFormat' => 'yyyy-MM-dd',
            'timeFormat' => 'hh:mm',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '12kFk3PUmlHTJmXwQS3m4lNuel83gNew',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $mailer,
        'log' => $log,
        'assetManager' => [
            'linkAssets' => false,
            'appendTimestamp' => false,
            'forceCopy' => false,
            'bundles' => [
                \yii\authclient\widgets\AuthChoiceStyleAsset::class => false,
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<alias:about|login|logout|signup|forgot|resend|account|delete-account|links|terms|set-password|reset-password>' => 'site/<alias>',
                '/<id:\w+>' => 'site/short',
            ],
        ],
    ],
    'modules' => [
//        'api' => [
//            'class' => 'app\modules\api\Module',
//            'defaultRoute' => 'default/index',
//        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
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
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
