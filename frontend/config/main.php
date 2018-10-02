<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => 'Balls Tennis',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'gridview' => [
            'class' => kartik\grid\Module::class
        ],

        'treemanager' => [
            'class' => kartik\tree\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@frontend/views'],
            ],
        ],
        'user' => [
            'class' => common\components\User::class,
            'identityClass' => common\models\User::class,
            'loginUrl' => ['site/login'],
            'enableAutoLogin' => true,

            'as authLog' => [
                'class' => yii2tech\authlog\AuthLogWebUserBehavior::class
            ],
            'identityCookie' => [
                'name'     => '_frontendIdentity',
                'path'     => '/',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'class' => yii\web\DbSession::class,
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => array_merge(
            require __DIR__ . '/../../common/config/url-manager.php',
            [
                'rules' => [
                    '/'          => 'site/index',
                    'index'      => 'site/index',
                    'login'      => 'site/login',
                    'contact'    => 'site/contact' ,
                    'about'      => 'site/about' ,
                
                ],
            ]
        ),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
