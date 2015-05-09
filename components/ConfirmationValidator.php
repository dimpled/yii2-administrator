<?php
namespace dimple\administrator\components;

use Yii;
use yii\validators\Validator;
use dimple\administrator\models\User;

class ConfirmationValidator extends Validator
{
	private $_module = null;

    public function init()
    {
        parent::init();
        $this->_module = Yii::$app->getModule('administrator');
    }

    public function validateAttribute($model, $attribute)
    {
            $user = User::findByEmail($model->$attribute);

            $confirmationRequired = $this->_module->enableConfirmation && !$this->_module->enableUnconfirmedLogin;
            if ($confirmationRequired && !$user->getIsConfirmed()) {
                $this->addError($model,$attribute, \Yii::t('user', 'You need to confirm your email address'));
            }

            if ($user->getIsBlocked()) {
                $this->addError($model,$attribute, \Yii::t('user', 'Your account has been blocked'));
            }
    }


}