<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'        => 'app-api',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],

    'modules' => [
        'v1' => [
            'class'               => 'api\modules\v1\Module',
            'basePath'            => '@api/modules/v1',
            'controllerNamespace' => 'api\modules\v1\controllers'
        ]
    ],

    'components' => [
        'user' => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => false,
            'loginUrl'        => null,
        ],

        'request' => [
            'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules'               => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/master'
                ],
                'POST v1/master/makesqldump'    => 'v1/master/makesqldump',
                'POST v1/master/getsqldumplist' => 'v1/master/getsqldumplist',
                'POST v1/master/getfileslist'   => 'v1/master/getfileslist',

                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/message'
                ],
                'POST v1/message/sync'  => 'v1/message/sync',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/country' ,  // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/user'
                ],
            ],
        ],

        'i18n' => array_merge(
            require __DIR__ . '/../../common/config/translations.php'
        )
    ],

    'params' => $params,
];
