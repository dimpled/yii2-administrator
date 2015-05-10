<?php

namespace dimple\administrator\controllers;

use Yii;
use dimple\administrator\Mailer;
use dimple\administrator\models\User;
use dimple\administrator\models\UserSearch;
use dimple\administrator\models\SystemLoginformSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserManageController implements the CRUD actions for User model.
 */
class UserManageController extends Controller
{
	//public $layout = '@dimple/administrator/views/layouts/profile';

    public $mailer;

    public function init()
    {
        $this->mailer = Yii::$container->get(Mailer::className());
        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','resend-confirm', 'delete','create','update','update-profile','view','user-loginlog'],
                        'allow' => true,
                        'roles' => ['Manager'],
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

    public function actionResendConfirm($id){
        $model = $this->findModel($id);
        if($model!=null){
            $model->status = User::STATUS_DELETED;
            $model->generateActivateToken();
            if($model->save())
            {
                $this->mailer->sendConfirmationMessage($model);
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Send confirmation message success'));
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'Send confirmation message fail'));
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateField();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function updateField()
    {
        if(Yii::$app->request->getIsPjax()){
            
            $id          = Yii::$app->request->get('id');
            $fieldName   = Yii::$app->request->get('field');
            $status      = Yii::$app->request->get('status');
            
            $fields = ['confirmed_at','blocked_at'];
            $model = $this->findModel($id);
            if(in_array($fieldName,$fields)){
                $model->{$fieldName} = $status==1?null:time();
                $model->save();
            } 
        }
        return false;
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    public function actionUserLoginlog($id){

    	$searchModel  = new SystemLoginformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         return $this->render('user-loginlog', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);

    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'create']);
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->enableConfirmation==1){
                $model->status = User::STATUS_DELETED;
                $model->generateActivateToken();
            }else{
                $model->status = User::STATUS_ACTIVE;
                $model->confirmed_at = time();
            }
            if($model->save()){
                if($model->enableConfirmation==1){
                     $this->mailer->sendConfirmationMessage($model);
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been Create'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
             return $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateProfile($id)
    {
        $model   = $this->findModel($id);
        $profile = $model->profile;

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Profile details have been updated'));
            return $this->refresh();
        } else {

            return $this->render('profile', [
                'user' => $model,
                'profile' => $profile,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
    
    public function beforeAction($action)
      {
          if (!parent::beforeAction($action)) {
              return false;
         }

         if(in_array(Yii::$app->controller->action->id, ['view','create','update','user-loginlog','update-profile'])){
            $this->layout = '@dimple/administrator/views/layouts/profile';
            $this->updateField();
         }

         return true; 
      }
}
