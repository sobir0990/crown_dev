<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'aliases' => [
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'language' => 'ru',
    'modules' => [
        'v1' => 'api\modules\v1\Module',
    ],

    'components' => [
        'request' => [
            'enableCsrfCookie'   => false,
            'parsers'   => [
                'application/json'  => 'yii\web\JsonParser'
            ],
        ],
        'response'   => [
            'class'     => \yii\web\Response::class,
            'format'    => \yii\web\Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'i18n' => [
            'translations' => [
                'report' => \yii\i18n\DbMessageSource::class
            ],
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => \yii\helpers\ArrayHelper::merge([
            ], \api\modules\v1\Module::$urlRules),
        ],

    ],
    'params' => $params,

];
