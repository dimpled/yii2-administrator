<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use dimple\administrator\widgets\Connect;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/_alert'); ?>

<?php //$this->render('/layouts/_alert'); ?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form', 
                'enableAjaxValidation' => true,
            ]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('user', 'Password') . (Yii::$app->getModule('administrator')->enablePasswordRecovery ? ' ( ' . Html::a(Yii::t('user', 'Forgot password?'), ['/administrator/recovery/request'], ['tabindex' => '5']) . ' )' : '')) ;?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-success  btn-block', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
 if(isset(Yii::$app->authClientCollection)){
             echo Connect::widget([
            'baseAuthUrl' => ['/user/security/auth']
             ]);
}
?>




