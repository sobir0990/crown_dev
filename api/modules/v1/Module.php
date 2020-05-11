<?php

namespace api\modules\v1;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;

/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package minfin
 */
class Module extends \yii\base\Module
{

    public $controllerNamespace = 'api\modules\v1\controllers';
    public $defaultRoute = 'main/index';

    public function behaviors()
    {
        $except = array(
            'admin/user/sign-in-admin',
            'user/sign-in',
            'user/sign-in-client',
            'user/approve-phone',
            'main/*',
            'admin/main/*',
            'models/*',
            'pages/*',
            'categories/index',
            'post/*',
            'user/market',
            'settings/*',
            'reference/*',
            '*/options',
        );
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::class,
//                'only' => ['*'],
                'except' => $except,
                'authMethods' => [
                    HttpBearerAuth::class,
                ],
            ],
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['GET,HEAD,POST,PUT,PATCH,OPTIONS,DELETE'],
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Expose-Headers' => ['*'],
//                    'Access-Control-Allow-Credentials' => true,
//                    'Access-Control-Allow-Origin' => ['*'],
                    'Access-Control-Allow-Methods' => ['GET,HEAD,POST,PUT,PATCH,OPTIONS,DELETE'],
                ],
            ],
        ]);
    }

    public static $urlRules = array(
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/main',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD translations/<lang:\w+>/<category:\w+>' => 'translations',
                'POST translations/<lang:\w+>/<category:\w+>' => 'add-translation',
                'OPTIONS translations/<lang:\w+>/<category:\w+>' => 'options',

                'GET,HEAD list/<category:\w+>' => 'get-translations',
                'POST list/<category:\w+>' => 'message-translate',
                'OPTIONS list/<category:\w+>' => 'options',
            ),
        ),

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/filemanager',
            'pluralize' => false,
            'extraPatterns' => [
                'POST uploads' => 'uploads',
                'OPTIONS uploads' => 'options'
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/balans',
            'pluralize' => false,
            'extraPatterns' => [
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/balans',
            'pluralize' => false,
            'extraPatterns' => [
                'GET market' => 'market',
                'OPTIONS market' => 'options',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/order',
            'pluralize' => false,
            'extraPatterns' => [
                'PUT status/<id:\d+>' => 'status',
                'OPTIONS status/<id:\d+>' => 'options',

                'POST export-excel' => 'export-excel',
                'OPTIONS export-excel' => 'options',
            ]
        ],
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/user',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET index' => 'index',
                'OPTIONS index' => 'options',

                'GET company' => 'company',
                'OPTIONS company' => 'options',

                'GET diller' => 'diller',
                'OPTIONS diller' => 'options',

                'GET clients' => 'clients',
                'OPTIONS clients' => 'options',

                'GET market' => 'market',
                'OPTIONS market' => 'options',

                //admin
                'GET get-me-admin' => 'get-me-admin',
                'OPTIONS get-me-admin' => 'options',

                'POST sign-in-admin' => 'sign-in-admin',
                'OPTIONS sign-in-admin' => 'options',
//                'OPTIONS <action>' => 'options',

                'GET report/<action:>' => 'report',
                'OPTIONS report/<action:>' => 'options',

                //mobile
                'GET get-me' => 'get-me',
                'OPTIONS get-me' => 'options',

                'POST sign-in' => 'sign-in',
                'OPTIONS sign-in' => 'options',

                'POST sign-up' => 'sign-up',
                'OPTIONS sign-up' => 'options',

                'PUT approve-phone/<phone:>' => 'approve-phone',
                'OPTIONS approve-phone/<phone:>' => 'options',

                'PUT role/<user_id:\d+>' => 'role',
                'OPTIONS role/<user_id:\d+>' => 'options',

                'POST resend-approve-code/<phone:>' => 'resend-approve-code',
                'OPTIONS resend-approve-code/<phone:>' => 'options',

                'PUT change-phone' => 'change-phone',
                'OPTIONS change-phone' => 'options',

                'PUT change-password' => 'change-password',
                'OPTIONS change-password' => 'options',

                'PUT reset-password' => 'reset-password',
                'OPTIONS reset-password' => 'options',

                'DELETE approve-delete-account/<user_id:>/<code:>' => 'approve-delete-account',
                'OPTIONS approve-delete-account/<user_id:>/<code:>' => 'options',

                'PUT profile' => 'update',
                'OPTIONS profile' => 'options',

                'OPTIONS logout' => 'options',

            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/models',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/store',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET view/<id:\d+>' => 'view',
                'OPTIONS view/<id:\d+>' => 'options',

                'POST product-store' => 'product-store',
                'OPTIONS product-store' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/district',
            'pluralize' => false,
            'extraPatterns' => array(
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/district',
            'pluralize' => false,
            'extraPatterns' => array(
            ),
        ),


        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/sms-message',
            'pluralize' => false,
            'extraPatterns' => array(
                'POST send-message' => 'send-sms',
                'OPTIONS send-message' => 'options',

                'POST resend' => 'resend-sms',
                'OPTIONS resend' => 'options',

                'GET progress' => 'progress-bar',
                'OPTIONS progress' => 'options',

                'PUT resend-sms/<id:\d+>' => 'resend-sms-message',
                'OPTIONS resend-sms/<id:\d+>' => 'options',
            )
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/product',
            'pluralize' => false,
            'extraPatterns' => array(
                'POST implementation' => 'implementation',
                'OPTIONS implementation' => 'options',

                'GET user' => 'user',
                'OPTIONS user' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/reference',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/settings',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD statistic' => 'statistic',
                'OPTIONS statistic' => 'options',

                'PUT course' => 'course',
                'OPTIONS course' => 'options',

                'GET,HEAD dashboard' => 'dashboard',
                'OPTIONS dashboard' => 'options',

                'GET,HEAD chart' => 'chart',
                'OPTIONS chart' => 'options',
            ),
        ),
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/notification',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/post',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD <slug:\S+>' => 'by-slug',
                'OPTIONS <slug:\S+>' => 'options',
            ),
        ),
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/pages',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD <slug:\S+>' => 'by-slug',
                'OPTIONS <slug:\S+>' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/categories',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/admin/region',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        //frontend
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/models',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET market' => 'market',
                'OPTIONS market' => 'options',
            ),
        ),


        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/region',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/filemanager',
            'pluralize' => false,
            'extraPatterns' => [
                'POST uploads' => 'uploads',
                'OPTIONS uploads' => 'options'
            ]
        ],

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/product',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/order',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET user' => 'user',
                'OPTIONS user' => 'options',

                'GET market' => 'market',
                'OPTIONS market' => 'options',

                'POST client' => 'client',
                'OPTIONS client' => 'options',

                'POST validate' => 'validate',
                'OPTIONS validate' => 'options',

                'PUT status/<id:\d+>' => 'status',
                'OPTIONS status/<id:\d+>' => 'options',

                'POST store' => 'store',
                'OPTIONS store' => 'options',

                'POST export-excel' => 'export-excel',
                'OPTIONS export-excel' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/pages',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD <slug:\S+>' => 'by-slug',
                'OPTIONS <slug:\S+>' => 'options',
            ),
        ),
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/post',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD <slug:\S+>' => 'by-slug',
                'OPTIONS <slug:\S+>' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/categories',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD user' => 'user',
                'OPTIONS user' => 'options',

                'GET,HEAD store' => 'store',
                'OPTIONS store' => 'options',
            ),
        ),


        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/reference',
            'pluralize' => false,
            'extraPatterns' => array(),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/settings',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD dashboard' => 'dashboard',
                'OPTIONS dashboard' => 'options',

                'GET,HEAD contact' => 'contact',
                'OPTIONS contact' => 'options',
            ),
        ),

        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/main',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET,HEAD translations/<lang:\w+>/<category:\w+>' => 'translations',
                'POST translations/<lang:\w+>/<category:\w+>' => 'add-translation',
                'OPTIONS translations/<lang:\w+>/<category:\w+>' => 'options',
            ),
        ),
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/user',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET get-me' => 'get-me',
                'OPTIONS get-me' => 'options',

                'GET market' => 'market',
                'OPTIONS market' => 'options',

                'GET my-profile' => 'my-profile',
                'OPTIONS my-profile' => 'options',

                'GET my-products' => 'my-products',
                'OPTIONS my-products' => 'options',

                'POST sign-in' => 'sign-in',
                'OPTIONS sign-in' => 'options',

                'GET get-me-client' => 'get-me-client',
                'OPTIONS get-me-client' => 'options',

                'POST sign-in-client' => 'sign-in-client',
                'OPTIONS sign-in-client' => 'options',

                'PUT approve-phone/<phone:>' => 'approve-phone',
                'OPTIONS approve-phone/<phone:>' => 'options',

                'POST resend-approve-code/<phone:>' => 'resend-approve-code',
                'OPTIONS resend-approve-code/<phone:>' => 'options',

                'OPTIONS logout' => 'options',

            ),
        ),

    );

    public static function allowedDomains()
    {
        return [
            '*',
        ];
    }

}
