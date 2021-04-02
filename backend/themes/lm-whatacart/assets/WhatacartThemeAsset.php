<?php

namespace approot\themes\whatacart\assets;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;


class WhatacartThemeAsset extends AssetBundle
{
    public $sourcePath = '@approot/themes/whatacart/assets';
    //public $baseUrl = '@web';
    
    public $css = [
        'css/wta.css',
    ];
    
    public $depends = [
        AppAsset::class        
    ];
}