<?php

namespace dimple\administrator;

use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;
use yii\console\Application as ConsoleApplication;
use yii\web\User;


class Bootstrap implements BootstrapInterface
{
    private $_modelMap = [
        'User'             => 'dimple\administrator\models\User',
        'Account'          => 'dimple\administrator\models\Account',
        'Profile'          => 'dimple\administrator\models\Profile',
        'Token'            => 'dimple\administrator\models\Token'
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {

        
    }
}