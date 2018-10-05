<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class ClientAsset
 *
 * Asset manager for the main public page
 *
 * @package frontend\assets
 */
class ClientAsset extends AssetBundle
{
    public $css = [
        'https://fonts.googleapis.com/css?family=Lato:100,300,400,700',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        'css/client.css',
    ];

    public $js = [
        'js/client.js',
        'js/vendor.js',
    ];
}