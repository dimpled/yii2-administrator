<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\SystemLoginform */

$this->title = $model->log_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Loginforms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-loginform-view">

    <h1>#<?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->log_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'log_id',
            'username',
            'password',
            'ip',
            'user_agent',
            'time:datetime',
            ['attribute'=>'success','value'=>$model->success==1?'True':'False']
        ],
    ]) ?>

</div>
