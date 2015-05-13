<?php

use dimple\administrator\models\User;
use yii\bootstrap\Nav;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$user = $this->params['User'];

?>
<?php $this->beginContent('@dimple/administrator/views/layouts/main.php') ?>
<?= $this->render('_alert') ?>
<?php Pjax::begin() ?>
<h2><?= $this->title ?> </h2>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
            <div style="margin-bottom:25px; text-align:center;">
                <img src="http://gravatar.com/avatar/<?= $user->profile->gravatar_id ?>?s=200"  class="img-thumbnail img-circle img-responsive" alt="<?= $user->username ?>"/>
            </div>
            <?php if($user==null):?>
            <?= Nav::widget([
                    'options' => [
                        'class' => 'nav-pills nav-stacked'
                    ],
                    'encodeLabels'=>false,
                    'items'=>[
                     ['label' => '<i class="glyphicon glyphicon-chevron-left"></i> '. Yii::t('user', 'Manage Users'), 'url' => ['/administrator/user-manage/index'],'linkOptions'=>['class'=>'btn btn-default btn-block']],
                    ]
             ]) ?>
         <?php else: ?>
            <?= $this->render('_navUpdate',['user'=>$user]); ?>
         <?php endif; ?>
              
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