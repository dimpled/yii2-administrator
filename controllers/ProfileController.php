<?php

namespace dimple\administrator\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use dimple\administrator\models\User;
use dimple\administrator\models\Profile;


class ProfileController extends Controller{

    public $layout = '@dimple/administrator/views/layouts/profile_user';

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

        if($model->load(Yii::$app->request->post()) && $model->save()){
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
            $this->refresh();
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

    public function actionNetowrks(){

    }

	    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

?>