<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => 'Balls Tennis Admin',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'brussens\maintenance\Maintenance'],
    'controllerNamespace' => 'backend\controllers',
    
    
    
    'modules' => [
        'gridview' => [
            'class' => kartik\grid\Module::class
        ],

//        'treemanager' => [
//            'class' => kartik\tree\Module::class,
//        ],
        'queuemanager' => [
            'class' => \ignatenkovnikita\queuemanager\QueueManager::class
        ],
    ],
    'homeUrl' => '/admin',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
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
                'name'     => '_backendIdentity',
                'path'     => '/admin',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'class' => yii\web\DbSession::class,
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'timeout' => 12 * 60 * 60,
            'useCookies' => true,
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'view' => [
            'theme' => [
                'pathMap' => ['@backend/views'],
            ],
        ],
        
//       'maintenanceMode' => [
//            'class' => brussens\maintenance\MaintenanceMode::class,
//            'enabled' => false,
//            'urls' => ['site/index', 'login'],
//            'roles' => ['developer'],
//        ],

       
        'urlManager' => array_merge(
            require __DIR__ . '/../../common/config/url-manager.php',
            [
                'rules' => [
                    'admin'          => 'site/index',
                    'index'      => 'site/index',
                    '/' => 'site/login',
                    'login' => 'site/login',
                    'request-password-reset' => 'site/request-password-reset',
                    'reset-password' => 'site/reset-password',
                    'select' => 'site/select',
                    'logout' => 'site/logout',
                    
                    /** Other routes */
                    'qr/<hashcode:\w+>' => 'qr/view',
                    'treemanager/node/<action:\w+>' => 'treemanager/node/<action>',
                    'datecontrol/parse/<action:\w+>' => 'datecontrol/parse/<action>',
                    'gridview/export/download' => 'gridview/export/download',
                  

                    /** Global routes for most controllers */
                    '<controller:[\w-]+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:[\w-]+>/<action:\w+>/<id:\d+>/<id2:\d+>' => '<controller>/<action>',
                    '<controller:[\w-]+>' => '<controller>/index',
                    '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
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
    'container' => [
        'singletons' => [
            'brussens\maintenance\Maintenance' => [
                'class' => 'brussens\maintenance\Maintenance',

                // Route to action
                'route' => 'maintenance/index',

                // Filters. Read Filters for more info.
                'filters' => [
                    [
                        'class' => 'brussens\maintenance\filters\URIFilter',
                        'uri' => [
                            'debug/default/toolbar',
                            'debug/default/view',
                            'site/index',
                            'login',
                            'request-password-reset'
                        ]
                    ],
                    // Allowed roles filter
                    [
                        'class' => 'brussens\maintenance\filters\RoleFilter',
                        'roles' => [
                            'developer',
                        ]
                    ]
                ],

                // HTTP Status Code
                'statusCode' => 503,

                //Retry-After header
                'retryAfter' => 120 // or Wed, 21 Oct 2015 07:28:00 GMT for example
            ],
            'brussens\maintenance\StateInterface' => [
                'class' => 'brussens\maintenance\states\FileState',

                // optional: use different filename for controlling maintenance state:
                // 'fileName' => 'myfile.ext',

                // optional: use different directory for controlling maintenance state:
                // 'directory' => '@mypath',
            ]
        ]
    ]
];
