<?php
use yii\bootstrap\Nav;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Nav::widget([
                    'options' => [
                        'class' => 'nav-pills nav-stacked'
                    ],
                    'encodeLabels'=>false,
                    'items' => [
                        ['label' => '<i class="glyphicon glyphicon-chevron-left"></i> '. Yii::t('user', 'Manage Users'), 'url' => ['/administrator/user-manage/index'],'linkOptions'=>['class'=>'btn btn-default btn-block']],
                        '<hr>',
                        ['label' => '<i class="glyphicon glyphicon-info-sign"></i> '.Yii::t('user', 'Information'), 'url' => ['/administrator/user-manage/view', 'id' => $user->id]],
                        ['label' => '<i class="glyphicon glyphicon-edit"></i> '.Yii::t('user', 'Update Account'), 'url' => ['/administrator/user-manage/update', 'id' => $user->id]],
                        ['label' => '<i class="glyphicon glyphicon-user"></i> '.Yii::t('user', 'Update Profile'), 'url' => ['/administrator/user-manage/update-profile', 'id' => $user->id]],
                        ['label' => '<i class="glyphicon glyphicon-eye-open"></i> '.Yii::t('user', 'Profile details'), 'url' => ['/administrator/profile/index', 'id' => $user->id]],
                        ['label' => '<i class="glyphicon glyphicon-info-sign"></i> '.Yii::t('user', 'User Login Log'), 'url' => ['/administrator/user-manage/user-loginlog', 'id' => $user->id]],
                       
                        [
                            'label' => Yii::t('user', 'Assignments'),
                            'url' => ['/user/admin/assignments', 'id' => $user->id],
                            'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                        ],
                        '<hr>',
                        // [
                        //     'label' => Yii::t('user', 'Confirm'),
                        //     'url'   => ['/user/admin/confirm', 'id' => $user->id],
                        //     'visible' => !$user->isConfirmed,
                        //     'linkOptions' => [
                        //         'class' => 'btn btn-success',
                        //         'data-method' => 'post',
                        //         'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?')
                        //     ],
                        // ],
                        // [
                        //     'label' => Yii::t('user', 'Block'),
                        //     'url'   => ['/user/admin/block', 'id' => $user->id],
                        //     'visible' => !$user->isBlocked,
                        //     'linkOptions' => [
                        //         'class' => 'text-danger',
                        //         'data-method' => 'post',
                        //         'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?')
                        //     ],
                        // ],
                        // [
                        //     'label' => Yii::t('user', 'Unblock'),
                        //     'url'   => ['/user/admin/block', 'id' => $user->id],
                        //     'visible' => $user->isBlocked,
                        //     'linkOptions' => [
                        //         'class' => 'text-success',
                        //         'data-method' => 'post',
                        //         'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?')
                        //     ],
                        // ],
                        // [
                        //     'label' => Yii::t('user', 'Delete'),
                        //     'url'   => ['/user/admin/delete', 'id' => $user->id],
                        //     'linkOptions' => [
                        //         'class' => 'text-danger',
                        //         'data-method' => 'post',
                        //         'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?')
                        //     ],
                        // ],
                    ]
                ]) ?>

<?=  Html::a('<i class="glyphicon glyphicon-check"></i>  '.($user->isConfirmed?' Confirmed':' Confirm'),
    Url::current(['id'=>$user->id,'field'=>'confirmed_at','status'=>$user->isConfirmed?1:0]),
    ['class'=>'btn btn-sm btn-block '.($user->isConfirmed?'btn-success':'btn-default')
]); ?>

<?= Html::a(
    '<i class="glyphicon glyphicon-ban-circle"></i> '.($user->isBlocked?' Unblock':' Block'),
    Url::current(['id'=>$user->id,'field'=>'blocked_at','status'=>$user->isBlocked?1:0]),
    ['class'=>'btn btn-sm btn-block '.($user->isBlocked?'btn-danger':'btn-default')]
); ?>

<?= Html::a('<i class="glyphicon glyphicon-trash"></i> '.
    Yii::t('user', 'Delete'),
    ['/administrator/user-manage/delete','id'=>$user->id],
    [
        'class'=>'btn btn-sm btn-block btn-default',
        'data-method' => 'post',
        'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?')
]); ?>

<?= Html::a(
    '<i class="glyphicon glyphicon-envelope"></i> Resend Email Confirm',
    ['resend-confirm','id'=>$user->id],
    ['class'=>'btn btn-sm btn-block '.($user->isConfirmed?'btn-success':'btn-default')]
); ?>
