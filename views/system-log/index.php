<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\log\Logger;
use dimple\administrator\models\SystemLog;
use yii\widgets\Pjax;
//use backend\components\ActionColumn;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SystemLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'System Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'level',
                'format'=>'raw',
                'value'=>function ($model) {
                    return $model->getLevelColor();
                },
                'filter'=>SystemLog::getLevels(),
                'options'=>['style'=>'width:100px;'],
                'contentOptions'=>['class'=>'text-center']
            ],
            'category',
            // 'prefix',
            [
                'attribute'=>'message',
                'value'=>function($model){
                    list($message) = explode("\n", $model->message);
                    return $message;
                }
            ],
             [
                'attribute' => 'log_time',
                'format'=>'html',
                'value' => function ($model) {
                   // return (int) $model->log_time;
                    $date = Yii::$app->formatter->asDateTime((int)$model->log_time,'medium');
                    return date('Y-m-d') == date('Y-m-d',(int)$model->log_time) ? '<span style="color:green;">'.$date.'</span>' : $date;
                }
            ],
            [
                'class' => 'dimple\administrator\components\ActionColumn',
                'template'=>'{view}{delete}',
                'options'=>['style'=>'width:90px;'],
                'contentOptions'=>['class'=>'text-center']
            ]
        ],
    ]); ?>
<?php Pjax::end() ?>
</div>
