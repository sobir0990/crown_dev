<?php

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../../../../common/config/main.php',
    require __DIR__ . '/../../../../../common/config/main-local.php',
    require __DIR__ . '/../../../../../common/config/test.php',
    [
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'xeiACUSp-a3o5-jtDoWraMVQ41nGjcCa',
            ],
        ],
    ],
);
