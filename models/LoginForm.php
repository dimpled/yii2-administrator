<?php

namespace dimple\administrator\models;

use Yii;
use dimple\administrator\models\SystemLoginform;
use dimple\administrator\models\User;
/**
 * Login form
 */
class LoginForm extends SystemLoginform
{
    public $rememberMe = true;

    private $_user = false;

    private $_module = false;

    public function init()
    {
        parent::init();
        $this->_module = Yii::$app->getModule('administrator');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['password', 'validateConfirmation'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->saveLoginLog(0);
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateConfirmation($attribute, $params){

        if (!$this->hasErrors()) {

            $user = $this->getUser();
            
            $confirmationRequired = ($this->_module->enableConfirmation && !$this->_module->enableUnconfirmedLogin);
            if ($confirmationRequired && !$user->getIsConfirmed()) {
                $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
            }

            if ($user->getIsBlocked()) {
                $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
            }
        }
    }



    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            $this->saveLoginLog(1);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            $this->saveLoginLog(0);
            return false;
        }
    }

    public function saveLoginLog($success)
    {
        $this->ip           = Yii::$app->request->getUserIP();
        $this->user_agent   = Yii::$app->request->getUserAgent();
        $this->time         = time();
        $this->success      = $success;
        $this->username     = $this->username;
        $this->password     = $success==1 ? '******' : $this->password;
        $this->save(false);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findUserByUsernameOrEmail($this->username);
        }
        return $this->_user;
    }
}
