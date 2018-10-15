<?php

$config = [
    'bootstrap' => ['debugger'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5C-IYkoQ-AV3JXP0uFxcTwYmlICxGvdc',
        ],
        'debugger' => [
            'class' => 'common\components\Debugger',
        ],
    ],
  
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = ['class' => 'yii\debug\Module', 'allowedIPs' => ['127.0.0.1', '::1', '192.168.*']];

    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = ['class' => 'yii\gii\Module', 'allowedIPs' => ['127.0.0.1', '::1', '192.168.*']];
    
//    $config['modules']['gii'] = [
//        'class' => 'yii\gii\Module',      
//        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*'],  
//        'generators' => [ //here
//            'crud' => [
//                'class' => 'yii\gii\generators\crud\Generator',
//                'templates' => [ // setting materializecss templates
//                    'custom' => '@vendor/ceyhunism/yii2-gentellela-crud-template',
//                ]
//            ]
//        ],
//    ];
    
     $config['modules']['gii'] = ['class' => 'yii\gii\Module', 'allowedIPs' => ['127.0.0.1', '::1', '192.168.*'],  
        'generators' => [ //here
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [ // setting materializecss templates
                    'custom' => '@vendor/macgyer/yii2-materializecss/src/gii-templates/generators/crud/materializecss',
                ],
            ],
            'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
            
        ],
    ];
}

return $config;
