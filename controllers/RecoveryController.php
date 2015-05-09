<?php

namespace dimple\administrator\controllers;

use Yii;

use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use dimple\administrator\models\PasswordResetRequestForm;
use dimple\administrator\models\ResetPasswordForm;
use dimple\administrator\models\User;


class RecoveryController extends Controller{

    public $defaultAction = 'request';

    public function init(){
        parent::init();
        $this->module = Yii::$app->getModule('administrator');
    }

    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException(Yii::t('user','Sorry, Password reset is not allowed!'));
        }
        
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionReset($token)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException(Yii::t('user','Sorry, Password reset is not allowed!'));
        }

        $user = $this->findPasswordResetToken($token);

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword($user)) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    protected function findPasswordResetToken($token)
    {
        if (($model = User::findByPasswordResetToken($token)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
        }
    }
}