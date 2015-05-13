<?php

namespace dimple\administrator\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use dimple\administrator\models\User;
use dimple\administrator\models\Profile;
use dimple\administrator\models\SocialAccount;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ProfileController extends Controller{

    public $layout = '@dimple/administrator/views/layouts/profile_user';

    public function behaviors()
    {
        return [
           'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => [],
                        'allow' => true,
                        'roles' => ['User'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	public function actionIndex(){
		$model = $this->findModel(Yii::$app->user->id);
		return $this->render('index',[
			'model'=>$model,
			'profile'=>$model->profile
		]);
	}

    public function actionAccount(){

        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = 'account';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) && $model->validate()){
             $model->password = $model->new_password;
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
                $this->refresh();
            }         
            print_r($model->getErrors());
        }else{
             return $this->render('account',['model'=>$model]);
        }
       
    }

    public function actionProfile(){

        $model = $this->findModel(Yii::$app->user->id);
        $profile = $model->profile;

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Profile details have been updated'));
            return $this->refresh();
        } else {

            return $this->render('profile', [
                'user' => $model,
                'model' => $profile,
            ]);
        }
    }

    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => Yii::$app->user->identity
        ]);
    }

    public function actionDisconnect($id)
    {
        $account = $this->findModelAccount($id);
        if ($account === null) {
            throw new NotFoundHttpException;
        }
        if ($account->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }
        $account->delete();

        return $this->redirect(['networks']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested user does not exist.');
        }
    }

    protected function findModelAccount($id)
    {
        if (($model = SocialAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested account does not exist.');
        }
    }

}

?>