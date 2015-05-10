<?php

namespace dimple\administrator\assets;

use yii\web\AssetBundle;

class BootstrapNotifyAssets extends AssetBundle
{
    public $sourcePath = '@bower/remarkable-bootstrap-notify';

    public $js = [
        'bootstrap-notify.min.js'
    ];
    
    public $depends = [
        'dimple\notify\AnimateAssets'
    ];
}