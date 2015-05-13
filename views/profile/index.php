<?php

use yii\helpers\Html;


$this->title = empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name);
$this->params['breadcrumbs'][] = $this->title;
$this->params['User'] =  $model;
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="row">
            <div class="col-md-12">
                <h4><?= $this->title ?></h4>
                 <?php if (!empty($profile->bio)): ?>
                    <p><?= Html::encode($profile->bio) ?></p>
                <?php endif; ?>
                <ul style="padding: 0; list-style: none outside none;">
                   
                    <?php if (!empty($profile->website)): ?>
                        <li><i class="glyphicon glyphicon-globe text-muted"></i> <?= Html::a(Html::encode($profile->website), Html::encode($profile->website)) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($profile->public_email)): ?>
                        <li><i class="glyphicon glyphicon-envelope text-muted"></i> <?= Html::a(Html::encode($profile->public_email), 'mailto:' . Html::encode($profile->public_email)) ?></li>
                    <?php endif; ?>
                     <?php if (!empty($profile->location)): ?>
                    <li><i class="glyphicon glyphicon-map-marker text-muted"></i> <?= Html::encode($profile->location) ?></li>
                    <?php endif; ?>
                    <li><i class="glyphicon glyphicon-time text-muted"></i> <?= Yii::t('user', 'Joined on {0, date}', $profile->user->created_at) ?></li>

                <?php if ($model->last_login_time !== null): ?>
                <li><i class="glyphicon glyphicon-time text-muted"></i> Last Login 
                <?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->last_login_time]) ?>
                </li>
                 <?php endif ?>
   
                </ul>
               
            </div>
        </div>
    </div>
</div>
