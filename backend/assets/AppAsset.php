<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'css/style.css',  //custom user styles
    ];
    public $js = [
        'js/script.js',  //custom user styles
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',        
        //'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    
    
}