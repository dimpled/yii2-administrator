<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use kartik\growl\Growl;
use yii\bootstrap\Alert;
use dimple\administrator\grid\ToggleColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<p>
    <?= Html::a(Yii::t('app', '<i class="glyphicon glyphicon-plus"></i> Create User'), ['create'], ['class' => 'btn  btn-success']) ?>
</p>
<?php Pjax::begin() ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => [
        [
            'attribute'=>'username',
            'format'=>'html',
            'value'=>function($model){
                return Html::a($model->username,['view','id'=>$model->id]);
            }
        ],
        'email:email',
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                    return $model->registration_ip == null
                        ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                        : $model->registration_ip;
                },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
            },
            'filter' => DatePicker::widget([
                'model'      => $searchModel,
                'attribute'  => 'created_at',
                'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control'
                ]
            ]),
        ],
        [
            'class'=>ToggleColumn::className(),
            'action'=>'toggle-confirm',
            'attribute' => 'confirmed_at',
            'value'=>function($model, $key, $index, $column){
               $label   = $model->isConfirmed?' <i class="glyphicon glyphicon-check"></i>   Confirmed':' Confirm';
               $btn     = $model->isConfirmed?'btn-success':'btn-default';
               $linkOptions = ArrayHelper::merge($column->linkOptions,['class'=>'toggle-column btn btn-sm btn-block '. $btn]);
               return Html::a($label, $column->url, $linkOptions);
            }
        ],[
            'class'=>ToggleColumn::className(),
            'action'=>'toggle-block',
            'attribute' => 'blocked_at',
            'value'=>function($model, $key, $index, $column){
                return $model->isBlocked;
            }
        ],
        // [
        // 'header' => Yii::t('user', 'Confirmation'),
        // 'attribute'=>'confirmed_at','format'=>'raw','value'=>function($model, $key, $index, $column){
        //         return Html::a($model->isConfirmed?'<i class="glyphicon glyphicon-check"></i>   Confirmed':' Confirm',
        //                Url::current(),
        //                ['class'=>'btn btn-sm toggle-column btn-block '.($model->isConfirmed?'btn-success':'btn-default')
        //         ]);
        //     },
        //     'options'=>['style'=>'width:50px;'],
        //     'visible' => Yii::$app->getModule('administrator')->enableConfirmation
        // ],
        // [
        // 'header' => Yii::t('user', 'Block status'),
        // 'attribute'=>'blocked_at','format'=>'raw','value'=>function($model, $key, $index, $column){
        //         return Html::a($model->isBlocked?'<i class="glyphicon glyphicon-ban-circle"></i>  Unblock':' Block',['/administrator/user-manage/index','id'=>$model->id,'field'=>'blocked_at','status'=>$model->isBlocked?1:0],[
        //                 'class'=>'btn btn-sm btn-update-field btn-block '.($model->isBlocked?'btn-danger':'btn-default')
        //         ]);
        //     },
        //     'options'=>['style'=>'width:50px;']
        // ],
        [
                'class' => 'dimple\administrator\components\ActionColumn',
                //'template'=>'{view}{delete}',
                'options'=>['style'=>'width:120px;'],
                'contentOptions'=>['class'=>'text-center'],
                'header'=>'Actions'
        ]
    ],
]); ?>

<?php Pjax::end() ?>
</div>

<?php //$this->render('_pjaxMsg') ?>
