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
    'bootstrap' => ['log', 'maintenanceMode'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'gridview' => [
            'class' => kartik\grid\Module::class
        ],

        'treemanager' => [
            'class' => kartik\tree\Module::class,
        ],
        'queuemanager' => [
            'class' => \ignatenkovnikita\queuemanager\QueueManager::class
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
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
            'timeout' => 12 * 60 * 60,
            'useCookies' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@frontend/views'],
            ],
        ],
        
        'maintenanceMode' => [
            'class' => brussens\maintenance\MaintenanceMode::class,
            'enabled' => false,
            'urls' => ['site/index', 'login'],
            'roles' => ['developer'],
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
                    'signup'     => 'site/signup' ,
                    
                    'request-password-reset' => 'site/request-password-reset',
                    'reset-password' => 'site/reset-password',
                    'select' => 'site/select',
                    'logout' => 'site/logout',
                    
                    /** Global routes for most controllers */
                    '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
                    '<controller:[\w-]+>/<id:\d+>' => '<controller>/index',
                    '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
                    
                    /** Other routes */
                    'qr/<hashcode:\w+>' => 'qr/view',
                    'treemanager/node/<action:\w+>' => 'treemanager/node/<action>',
                    'datecontrol/parse/<action:\w+>' => 'datecontrol/parse/<action>',
                    'gridview/export/download' => 'gridview/export/download',
                  

                    /** Global routes for most controllers */
//                    '<controller:[\w-]+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                    '<controller:[\w-]+>/<action:\w+>/<id:\d+>/<id2:\d+>' => '<controller>/<action>',
//                    '<controller:[\w-]+>' => '<controller>/index',
//                    '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
                
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
