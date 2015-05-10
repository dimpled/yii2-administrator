<?php

namespace dimple\administrator\behaviors;

use yii\web\User;
use yii\base\Behavior;


class LoginTimestampBehavior extends Behavior
{

    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'afterLogin'
        ];
    }

    public function afterLogin($event)
    {
        $user = $event->identity;
        $user->setLastUpdate();
        $user->save(false);
    }
}