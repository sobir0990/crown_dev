<?php
return [
    'id' => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN_TEST'),
            'username' => getenv('DB_USERNAME_TEST'),
            'password' => getenv('DB_PASSWORD_TEST'),
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'enableQueryCache' => false,
            'queryCacheDuration' => 3600,
        ],
    ],
];
