<?php

namespace dimple\administrator\models;

use dimple\administrator\models\User;
use dimple\administrator\models\Token;
use yii\base\Model;
use Yii;
use yii\log\Logger;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $module;

    public function init()
    {
        $this->module = Yii::$app->getModule('administrator');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\dimple\administrator\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\dimple\administrator\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {

            $user               = new User();
            $user->username     = $this->username;
            $user->email        = $this->email;
            $user->setPassword($this->password);
            
            if ($this->module->enableConfirmation == false) {
                $user ->confirmed_at = time();
                $user->status = User::STATUS_ACTIVE;
            }else{
                $user->generateActivateToken();
                $user->status = User::STATUS_DELETED;
            }

            if ($user->save()) {

                if ($this->module->enableConfirmation) {
                    $this->sendEmailConfirmation($user);
                } else {
                    Yii::$app->getUser()->login($user);
                }

                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'
                ));

             return true;
            }
        }
        Yii::getLogger()->log('An error occurred while registering user account', Logger::LEVEL_ERROR);
        return false;
    }

    public function sendEmailConfirmation($user)
    {                  
            $mailer = Yii::$app->mailer;
            $mailer->viewPath = '@dimple/administrator/views/mail';
            $mailer->getView()->theme = Yii::$app->view->theme;

            return Yii::$app->mailer->compose([
                    'html' => 'confirmation-html', 
                    'text' => 'confirmation-text'], 
                    ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject(Yii::t('user', 'Confirm account on {0}', \Yii::$app->name))
                    ->send();

    }
}
