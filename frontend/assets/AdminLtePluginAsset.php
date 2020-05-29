<?php
namespace frontend\assets;

use yii\web\AssetBundle;
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        
        // more plugin JS here
    ];
    public $css = [
       
        // more plugin CSS here
    ];
    public $depends = [
        'dmstr\adminlte\web\AdminLteAsset',
        'dmstr\adminlte\web\FontAwesomeAsset',
    ];
}
