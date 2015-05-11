<?php

namespace dimple\administrator\rbac;

use dimple\administrator\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $userManager = $auth->createPermission('user-manager');
        $userManager->description = 'user manager';
        $auth->add($userManager);

        $systemLog = $auth->createPermission('system-log');
        $systemLog->description = 'system log';
        $auth->add($systemLog);

        $userLog = $auth->createPermission('user-log');
        $userLog->description = 'user log';
        $auth->add($userLog);

        $userProfile = $auth->createPermission('user-profile');
        $userProfile->description = 'user profile';
        $auth->add($userProfile);

        $user = $auth->createRole('User');
        $auth->add($user);
        $auth->addChild($user, $userProfile);

        $manager = $auth->createRole('Manager');
        $auth->add($manager);
        $auth->addChild($manager, $user);
        $auth->addChild($manager, $userManager);

        $admin = $auth->createRole('Admin');
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $systemLog);
        $auth->addChild($admin, $userLog);

        $auth->assign($admin, 1);
        $auth->assign($manager, 4);
        $auth->assign($user, 5);

        Console::output('Success! RBAC roles has been added.');
    }
}