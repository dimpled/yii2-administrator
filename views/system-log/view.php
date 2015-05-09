<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\log\Logger;

/* @var $this yii\web\View */
/* @var $model app\models\SystemLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-view">

    <h1>#<?= Html::encode($this->title.' : '.Logger::getLevelName($model->level)) ?></h1>

    <p>
       
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            ['attribute'=>'level','value'=>Logger::getLevelName($model->level)],
            'category',
            ['attribute'=>'log_time','format'=>'dateTime','value'=>(int)$model->log_time],
            'prefix:ntext',
            'message:ntext',
            //['attribute'=>'message','format'=>'html','value'=>"<pre style\"width:100%\">$model->message</pre>"]
        ],
    ]) ?>

</div>
