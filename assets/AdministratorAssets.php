<?php

namespace dimple\administrator\assets;

use yii\web\AssetBundle;

class AdministratorAssets extends AssetBundle
{
    public $sourcePath = '@dimple/administrator/assets/bootstrap-notify'; 
    public $css = [
        'animate.css',
    	'bootstrap-notify.css'
    ];

    public $depends = [
        'dimple\administrator\assets\BootstrapNotifyAssets',
        'dimple\administrator\assets\Flot'
    ];
}