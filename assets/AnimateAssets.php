<?php

namespace dimple\administrator\assets;

use yii\web\AssetBundle;

class AnimateAssets extends AssetBundle
{
    public $sourcePath = '@dimple/administrator/assets/bootstrap-notify'; 
    public $css = [
        'animate.css',
    	'bootstrap-notify.css'
    ];
}