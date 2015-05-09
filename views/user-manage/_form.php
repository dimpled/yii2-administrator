<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
  <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        //'enableAjaxValidation'   => true,
       // 'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => 60]) ?>
    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'enableConfirmation')->inline()->radioList([1=>'Yes',0=>'No']) ?>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
         <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
