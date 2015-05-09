<?php 

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;


$this->title = Yii::t('user','User Login Log');
$this->params['User'] = $user = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

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
           // [
           //      'attribute' => 'time',
           //      'format'=>'html',
           //      'value' => function ($model) {
           //         // return (int) $model->log_time;
           //          $date = Yii::$app->formatter->asDateTime((int)$model->time,'medium');
           //          return date('Y-m-d') == date('Y-m-d',(int)$model->time) ? '<span style="color:green;">'.$date.'</span>' : $date;
           //      }
           //  ],
            [
                'attribute' => 'time',
                'value' => function ($model) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->time]);
                },
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'time',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ]),
            ],
            'ip',
            //'user_agent',
             [
                'attribute'=>'user_agent',
                'value'=>function($model){
                   
                    return $model->getUaBrowser().', '.$model->getUaOs().', '.$model->getUaDevice();
                }
             ],
        ],
    ]); ?>