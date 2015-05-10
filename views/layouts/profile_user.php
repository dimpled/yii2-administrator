<?php

use dimple\administrator\models\User;
use yii\bootstrap\Nav;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$user = $this->params['User'];

?>

<?php $this->beginContent('@app/views/layouts/main.php') ?>
<?= $this->render('_alert') ?>
<?php Pjax::begin() ?>
<h2>Profile </h2>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
            <div style="margin-bottom:25px; text-align:center;">
                <img src="http://gravatar.com/avatar/<?= $user->profile->gravatar_id ?>?s=200"  class="img-thumbnail img-circle img-responsive" alt="<?= $user->username ?>"/>
            </div>
            <?= Nav::widget([
                    'options' => [
                        'class' => 'nav-pills nav-stacked'
                    ],
                    'encodeLabels'=>false,
                    'items' => [
                        ['label' => '<i class="glyphicon glyphicon-user"></i> '.Yii::t('user', 'Profile'),  'url' => ['/administrator/profile/index']],
                        ['label' => '<i class="glyphicon glyphicon-user"></i> '.Yii::t('user', 'Edit Profile'),  'url' => ['/administrator/profile/profile']],
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> '.Yii::t('user', 'Edit Account'),  'url' => ['/administrator/profile/account']],
                        ['label' => '<i class="glyphicon glyphicon-globe"></i> '. Yii::t('user', 'Networks'), 'url' => ['/administrator/profile/networks']],
                    ]
                ]) ?>
              
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end() ?>
<?= $this->render('/user-manage/_pjaxMsg') ?>
<?php $this->endContent() ?>