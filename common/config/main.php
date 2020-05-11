<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@jakharbek/filemanager' => '@common/ext/yii2-filemanager/src',
        '@jakharbek/langs' => '@common/extension/yii2-langs/src',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        \common\bootstrap\SetUp::class,
        \jakharbek\filemanager\bootstrap\SetUp::class,
    ],
    'components' => [
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
//            'db' => 'db', // Компонент подключения к БД или его конфиг
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => getenv('DB_DSN'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => 'utf8',
            ],
            'mutex' => [
                'class' => 'yii\mutex\PgsqlMutex',
            ],
            'mutexTimeout' => 315360000,
            'tableName' => '{{%queue}}', // Имя таблицы
            'channel' => 'default', // Выбранный для очереди канал
//            'mutex' => \yii\mutex\MysqlMutex::class, // Мьютекс для синхронизации запросов
        ],
        'i18n' => [
            'translations' => [
                'main' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true,
                    'enableCaching' => true,
                    'cachingDuration' => 3600,
                    'sourceLanguage' => 'ru-RU',
                    'sourceMessageTable' => '_system_message',
                    'messageTable' => '_system_message_translation',
                    'on missingTranslation' => [
                        'common\components\EventHandlers',
                        'handleMissingTranslation',
                    ],
                ],
                'filemanager' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true,
                    'enableCaching' => true,
                    'cachingDuration' => 3600,
                    'sourceLanguage' => 'ru-RU',
                    'sourceMessageTable' => '_system_message',
                    'messageTable' => '_system_message_translation',
                    'on missingTranslation' => [
                        'common\components\EventHandlers',
                        'handleMissingTranslation',
                    ],
                ],
                'react' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true,
                    'enableCaching' => true,
                    'cachingDuration' => 3600,
                    'sourceLanguage' => 'ru-RU',
                    'sourceMessageTable' => '_system_message',
                    'messageTable' => '_system_message_translation',
                    'on missingTranslation' => [
                        'common\components\EventHandlers',
                        'handleMissingTranslation',
                    ],
                ],
                'backend' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '_system_message',
                    'messageTable' => '_system_message_translation',
                ]
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'defaultRoles' => ['client'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'playmobile' => [
            'class' => \common\modules\playmobile\components\Connection::class,
            'username' => getenv('PLAYMOBILE_USERNAME'),
            'password' => getenv('PLAYMOBILE_PASSWORD'),
        ],
    ],
    'modules' => [
        'playmobile' => [
            'class' => 'common\modules\playmobile\Module',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '*'],
            'generators' => [
                'swagger' => [
                    'class' => '\common\generators\model\Generator',
                    'templates' => [
                        'swagger' => '@common/generators/model/default',
                    ]
                ],
                'api-crud' => [
                    'class' => '\common\generators\crud\Generator',
                    'templates' => [
                        'swagger' => '@common/generators/crud/default',
                    ]
                ],
//                'fixture' => [
//                    'class' => 'elisdn\gii\fixture\Generator',
//                ],
            ],
        ],
    ],
];
