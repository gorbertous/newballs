<?php

$params = require __DIR__ . '/../../common/config/params.php';

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'QyE0Gngcx6d3QVE3GOYT',
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
                        'from'    => $params['supportEmail'],
                        'subject' => 'Log message - API',
                        'to'      => $params['logReceivers']
                    ]
                ]
            ]
        ],
    ]
];