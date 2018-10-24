<?php

$params = require __DIR__ . '/../../common/config/params.php';

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => '',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'  => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error', 'warning'],

                    'message' => [
                        'from'    => $params['logEmail'],
                        'subject' => 'Log message - API',
                        'to'      => $params['logReceivers']
                    ]
                ]
            ]
        ],
    ]
];