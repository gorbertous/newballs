<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AdmAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $sourcePath = '@bower/';
    public $css = [
        'css/site.css',
        'css/vendor.css',
//        'admin-lte/css/AdminLTE.css'
    ];
    public $js = [
        'js/app.js',
        'js/client.js',
        'js/vendor.js',
//        'admin-lte/js/AdminLTE/app.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'yiister\gentelella\assets\Asset',
    ];
}
