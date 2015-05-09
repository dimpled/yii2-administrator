<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\SystemLoginformSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'System Logins');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-loginform-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'success',
                'format'=>'html',
                'value'=>function($model){
                    $icon = $model->success==1 ? 'ok' : 'warning-sign';
                    $color = $model->success==1 ? 'green' : '#E06262';
                    return '<i style="font-size:20px;color:'.$color.';" class="glyphicon glyphicon-'.$icon.'"></i>';
                 },
                'filter'=>['1'=>'Login Success','0'=>'Login failed'],
                'contentOptions'=>['class'=>'text-center']
            ],
            'username',
            'password',
            'ip',
            'user_agent',
             [
                'attribute' => 'time',
                'format'=>'html',
                'value' => function ($model) {
                   // return (int) $model->log_time;
                    $date = Yii::$app->formatter->asDateTime((int)$model->time,'medium');
                    return date('Y-m-d') == date('Y-m-d',(int)$model->time) ? '<span style="color:green;">'.$date.'</span>' : $date;
                }
            ],
            // 'time:datetime',
            // 'success',
            [
                'class' => 'backend\components\ActionColumn',
                'template'=>'{view}{delete}',
                'options'=>['style'=>'width:90px;'],
                'contentOptions'=>['class'=>'text-center']
            ]
        ],
    ]); ?>

</div>
