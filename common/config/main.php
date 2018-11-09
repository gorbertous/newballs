<?php

use kartik\datecontrol\Module;

return [
    'language'   => 'en',
    'timezone'   => 'Europe/Luxembourg',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'version'    => '1.0.0',
    
    'aliases' => [
        '@bower'      => '@vendor/bower-asset',
        '@npm'        => '@vendor/npm-asset',
    ],
   
    'modules' => [
        'datecontrol' => [
            'class' => kartik\datecontrol\Module::class,

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE     => 'dd/MM/yyyy',
                Module::FORMAT_TIME     => 'HH:mm',
                Module::FORMAT_DATETIME => 'dd/MM/yyyy HH:mm'
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE     => 'php:Y-m-d',
                Module::FORMAT_TIME     => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s'
            ],

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => [
                    'type'          => 2,
                    'pluginOptions' => ['autoclose' => true, 'todayHighlight' => true]
                ],
                Module::FORMAT_TIME => [
                    'pluginOptions' => ['minuteStep' => 10, 'defaultTime' => false]
                ],
                Module::FORMAT_DATETIME => []
            ]
        ]
    ],
    'components' => [
        'cache' => [
            'class' => yii\caching\DbCache::class,
            'cacheTable' => '{{%cache}}',
            /*'class' => \yii\redis\Cache::class,
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ]*/
        ],
        
        'assetManager' => [
            'appendTimestamp' => true,
        ],
      
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
        ],
        'contLang' => [
            'class' => common\components\ContLang::class,

            // languages for which we need separate fields
            'languages' => ['EN','FR','DE'],

            // if nothing is defined at mandant level,
            // the mandant falls back to these languages
            // in dev mode, we enable 2 languages for testing purpose
//            'defaultClubLanguages' => array_merge(['FR'], (YII_DEBUG ? ['DE'] : [])),
            'defaultClubLanguages' => ['FR', 'DE', 'EN']
        ],
       
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
            'as log' => \yii\queue\LogBehavior::class,
            'as quuemanager' => \ignatenkovnikita\queuemanager\behaviors\QueueManagerBehavior::class
            // Other driver options
        ],
            
        'reCaptcha' => [
            'name'    => 'reCaptcha',
            'class'   => himiklab\yii2\recaptcha\ReCaptcha::class,
            'siteKey' => '6Lf9Bm8UAAAAAHFpfEi-gpRzsNSYsWwE9pY_F7bE',
            'secret'  => '6Lf9Bm8UAAAAAPODEso7tnwJBiT2kLCF822um5Is',
        ],
         'formatter' => [
            'class' => yii\i18n\Formatter::class,
            'dateFormat' => 'php:D d M Y',
            'datetimeFormat' => 'php:d D M Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'nullDisplay' => '-',
            'defaultTimeZone' => 'Europe/Luxembourg',
            'locale' => 'en-GB',
            'thousandSeparator' => ' ',
            'decimalSeparator' => '.',
            'currencyCode' => 'EUR',
            // must enable php intl extension for \NumberFormatter
            /*'numberFormatterOptions' => [
                \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                \NumberFormatter::MAX_FRACTION_DIGITS => 0,
            ]*/
        ],
        'i18n' => array_merge(
            require __DIR__ . '/../../common/config/translations.php'
        ),
      
    ],
];
