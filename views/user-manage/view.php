<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = Yii::t('user','Information');
$this->params['User'] = $user = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
<!--     <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p> -->
  <table class="table">
        <?php if ($user->login_time !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Last Login') ?>:</strong></td>
            <td><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->login_time]) ?></td>
        </tr>
         <?php endif ?>
         <?php if ($user->login_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Last Login ip') ?>:</strong></td>
            <td><?= $user->login_ip; ?></td>
        </tr>
        <?php endif ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Registration time') ?>:</strong></td>
            <td><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->created_at]) ?></td>
        </tr>
        <?php if ($user->registration_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Registration IP') ?>:</strong></td>
            <td><?= $user->registration_ip ?></td>
        </tr>
        <?php endif ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Confirmation status') ?>:</strong></td>
            <?php if ($user->isConfirmed): ?>
            <td class="text-success"><?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$user->created_at]) ?></td>
            <?php else: ?>
            <td class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></td>
            <?php endif ?>
        </tr>
        <tr>
            <td><strong><?= Yii::t('user', 'Block status') ?>:</strong></td>
            <?php if ($user->isBlocked): ?>
            <td class="text-danger"><?= Yii::t('user', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$user->blocked_at]) ?></td>
            <?php else: ?>
            <td class="text-success"><?= Yii::t('user', 'Not blocked') ?></td>
            <?php endif ?>
        </tr>
    </table>



</div>
