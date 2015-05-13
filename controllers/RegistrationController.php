<?php

namespace dimple\administrator\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use dimple\administrator\models\SignupForm;
use dimple\administrator\models\User;
use dimple\administrator\models\SocialAccount;

class RegistrationController extends Controller
{
    public $defaultAction = 'signup';
    public $module;

    public function init(){
        parent::init();
        $this->module = Yii::$app->getModule('administrator');
    }

    public function actionSignup() {

        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException(Yii::t('user','User registration is currently not allowed!.'));
        }

        $model = new SignupForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
           return $this->goHome();
        }
        
        return $this->render('signup', ['model' => $model, ]);
    }

    public function actionConfirm($token) {

        if ($this->module->enableConfirmation == false) {
            throw new NotFoundHttpException(Yii::t('user','User confirmation is currently not allowed!.'));
        }

        $user = User::findByAtivateToken($token);

        if ( $user === null ) {  

           Yii::$app->session->setFlash('danger', Yii::t('user', 'Confirm link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/_alert', [
                'title'  => Yii::t('user', 'Invalid or expired link'),
            ]);
            
        }else{

            $user->confirmed_at = time();
            $user->removeActivateToken();
            $user->status = User::STATUS_ACTIVE;

            if ($user->save(false)) {

                if ($this->module->enableConfirmation) {
                    $user->assing();
                }

                Yii::$app->getUser()->login($user);
                Yii::$app->session->setFlash('success', Yii::t('user', 'Thank you, registration is now complete.'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Something went wrong and your account has not been confirmed.'));
            }
        }

        return $this->render('/_alert', [
            'title'  => Yii::t('user', 'Account confirmation')
        ]);
    }
    
    public function actionResend() {

    }

        /**
     * Displays page where user can create new account that will be connected to social account.
     *
     * @param int $account_id
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionConnect($account_id)
    {
        $account = SocialAccount::findOne($account_id);

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException('ไม่มี');
        }

        $user = new User(['scenario'=>'connect']);
        $user->confirmed_at = time();
        $user->setLastUpdate();
        
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            $account->link('user', $user);

             $user->assing();

            Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->goBack();
        }

        return $this->render('connect', [
            'model'   => $user,
            'account' => $account
        ]);
    }

}
