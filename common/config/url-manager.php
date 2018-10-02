<?php

use yii\web\UrlNormalizer;

return [
    'class'               => codemix\localeurls\UrlManager::class,
    'languages'           => ['fr', 'en', 'de'],
    'enablePrettyUrl'     => true,
    'showScriptName'      => false,
    
    'enableLanguageDetection' => true,
    'enableDefaultLanguageUrlCode' => false,

    /*
     * If you want ignore some paths just uncomment this
     *
     * 'ignoreLanguageUrlPatterns' => [
     *   '#^static/#' => '#^static/#'
     * ],
     */

    'normalizer' => [
        'class' => yii\web\UrlNormalizer::class,
        // use temporary redirection instead of permanent for debugging
        'action' => YII_ENV_DEV ? UrlNormalizer::ACTION_REDIRECT_TEMPORARY : UrlNormalizer::ACTION_REDIRECT_PERMANENT,
    ],
];