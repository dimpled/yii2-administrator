<?php

namespace dimple\administrator\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Controller;


/**
 * Class GlobalAccessBehavior
 * @package common\behaviors
 * @see https://raw.githubusercontent.com/trntv/yii2-starter-kit/master/common/behaviors/GlobalAccessBehavior.php
 */
class GlobalAccessBehavior extends Behavior
{

    /**
     * @var array
     * @see \yii\filters\AccessControl::rules
     */
    public $rules = [];

    /**
     * @var string
     */
    public $accessControlFilter = 'yii\filters\AccessControl';

    /**
     * @var callable a callback that will be called if the access should be denied
     * to the current user. If not set, [[denyAccess()]] will be called.
     *
     * The signature of the callback should be as follows:
     *
     * ~~~
     * function ($rule, $action)
     * ~~~
     *
     * where `$rule` is the rule that denies the user, and `$action` is the current [[Action|action]] object.
     * `$rule` can be `null` if access is denied because none of the rules matched.
     */
    public $denyCallback;

    /**
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    public function beforeAction()
    {
        parent::init();
        echo 'TEST';
    }
}