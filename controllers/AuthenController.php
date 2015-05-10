<?php

namespace dimple\administrator\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\BaseFileHelper;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\widgets\ActiveForm;
use dimple\administrator\models\LoginForm;
use dimple\administrator\models\User;
use dimple\administrator\models\SocialAccount;


class AuthenController extends Controller
{
 	public $defaultAction = 'signin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['signin', 'auth','success','test']],
                    ['allow' => true, 'actions' => ['auth', 'signout'], 'roles' => ['@']]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'signout' => ['post'],
                ],
            ]
        ];
    }

 /** @inheritdoc */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
            ]
        ];
    }

    /**
     * Tries to authenticate user via social network. If user has alredy used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *  
     * @param  ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
    {
           $account = $this->registerAccount($client);

            if ($account->user === null && $client instanceof ClientInterface) {
                $user = $this->registerUser($client);
                if ($user instanceof User) {
                    $account->link('user', $user);
                }
                $this->action->successUrl = Url::to(['/administrator/registration/connect','account_id'=>$account->id]);
            }else{

                 Yii::$app->user->login($account->user, $this->module->rememberFor);
                 $this->action->successUrl =  Url::home();
            }      
    }

    /**
     * Tries to connect social account to user.
     * 
     * @param ClientInterface $client
     */
    public function connect(ClientInterface $client)
    {
        
        $account = $this->registerAccount($client);
        
        if ($account->user === null) {
            $account->link('user', Yii::$app->user->identity);
            Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account has been connected'));
        } else {
            Yii::$app->session->setFlash('danger', \Yii::t('user', 'This account has already been connected to another user'));
        }

         $this->action->successUrl = Url::to(['/administrator/profile/networks']);
    }

    public function registerAccount($client){

        $account = SocialAccount::findByAccount($client->getId(),$client->getUserAttributes()['id']);

        if ($account === null) {
            $account = Yii::createObject([
                'class'      => SocialAccount::className(),
                'provider'   => $client->getId(),
                'client_id'  => $client->getUserAttributes()['id'],
                'data'       => serialize($client->getUserAttributes()),
            ]);
            $account->save(false);
        }

        return $account;
    }

    public function registerUser($client){

        $user = User::findByEmail($client->getEmail());

        if ($user !== null ) {
            return $user;
        }
        
        $user = Yii::createObject([
            'class'         => User::className(),
            'scenario'      => 'connect',
            'username' => $client->getUsername(),
            'email'    => $client->getEmail(),
            'status'        => User::STATUS_ACTIVE,
            'confirmed_at'  => time()
        ]);

        $user->setPassword(Yii::$app->security->generateRandomKey());

        if($user->save()){
            return $user;
        }else 
        {
            return false;
        }
    }



    public function actionSignin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionSignout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionDisconnect(){
        echo 'disconect';
    }

}
