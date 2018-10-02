<?php

$source = [
    'class' => yii\i18n\DbMessageSource::class,
    'enableCaching'   => YII_ENV_DEV ? false : true,
    'cachingDuration' => YII_ENV_DEV ? 600 : 60 * 60 * 2 ,
];

return [
    'translations' => [
        'modelattr' => $source,
        'accident'  => $source,
        'app*'      => $source,
        'diag*'     => $source,
        'index'     => $source,
        'email'     => $source,
        'chemicals' => $source,
        'public'    => $source,
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource',
        ],
    ],
];