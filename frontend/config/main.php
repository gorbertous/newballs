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
    'bootstrap' => ['log', 'brussens\maintenance\Maintenance','assetsAutoCompress'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'gridview' => [
            'class' => kartik\grid\Module::class,
            'downloadAction' => 'gridview/export/download',
        ],

//        'treemanager' => [
//            'class' => kartik\tree\Module::class,
//        ],
        'queuemanager' => [
            'class' => \ignatenkovnikita\queuemanager\QueueManager::class
        ],
        'utility' => [
            'class' => 'c006\utility\migration\Module',
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
        
        'assetsAutoCompress' =>
        [
            'class'                         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled'                       => YII_ENV_DEV ? false : true,
            
            'readFileTimeout'               => 3,           //Time in seconds for reading each asset file
            
            'jsCompress'                    => true,        //Enable minification js in html code
            'jsCompressFlaggedComments'     => true,        //Cut comments during processing js
          
            'cssCompress'                   => true,        //Enable minification css in html code
            'cssFileCompile'                => true,        //Turning association css files
            'cssFileRemouteCompile'         => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress'               => true,        //Enable compression and processing before being stored in the css file
            'cssFileBottom'                 => false,       //Moving down the page css files
            'cssFileBottomLoadOnJs'         => false,       //Transfer css file down the page and uploading them using js
            
            'jsFileCompile'                 => true,        //Turning association js files
            'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress'                => true,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => true,        //Cut comments during processing js
            
            
            'noIncludeJsFilesOnPjax'        => true,        //Do not connect the js files when all pjax requests
            'htmlFormatter' => [
                //Enable compression html
                'class'         => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
//                'extra'         => false,       //use more compact algorithm
//                'noComments'    => true,        //cut all the html comments
//                'maxNumberRows' => 50000,       //The maximum number of rows that the formatter runs on

                //or

                'class' => 'skeeks\yii2\assetsAuto\formatters\html\MrclayHtmlCompressor',

                //or any other your handler implements skeeks\yii2\assetsAuto\IFormatter interface

                //or false
            ],
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
                    'signup'     => 'site/signup',
                    
                    'request-password-reset' => 'site/request-password-reset',
                    'reset-password' => 'site/reset-password',
                    'select' => 'site/select',
                    'logout' => 'site/logout',
                    [
                        'pattern'  => 'about', 
                        'route'    => 'cms/page',
                        'defaults' => ['id' => 1],
                    ],  
                    
                    /** Global routes for most controllers */
                    '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
                    '<controller:[\w-]+>/<id:\d+>' => 'site/index',
                    '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:[\w-]+>/<action:\w+>/<id:\d+>/<id2:\d+>' => '<controller>/<action>',
                    '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
                    
                    /** Other routes */
                    'qr/<hashcode:\w+>' => 'qr/view',
                    'treemanager/node/<action:\w+>' => 'treemanager/node/<action>',
                    'datecontrol/parse/<action:\w+>' => 'datecontrol/parse/<action>',
                    'gridview/export/download' => 'gridview/export/download',

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
    //        'maintenanceMode' => [
//            'class' => brussens\maintenance\MaintenanceMode::class,
//            'title' => 'This site is under maintenance',
//            'message' => 'We should be back shortly!',
//            'enabled' => false,
//            'urls' => ['site/index', 'login','request-password-reset'],
//            'roles' => ['developer'],
//        ],
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
