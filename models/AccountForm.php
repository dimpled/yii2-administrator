<?php

namespace dimple\administrator\models;

use Yii;
use dimple\administrator\models\SystemLoginform;
use dimple\administrator\models\User;
use yii\base\Model;

class AccountForm extends User {

	public $new_password;
	public $current_password;
	public $confirm_new_password;

	public function Rules{
		return [
			 [['username', 'email'], 'required'],
			 [['username'], 'string', 'min' => 3, 'max' => 50],
			 [['email'], 'string', 'max' => 255],
             [['current_password','new_password', 'confirm_new_password'],'requied'],
             [['new_password','confirm_new_password'],'string','min'=>6],
             [['current_password'],'validateCurrentPassoward'],
             [['confirm_new_password'],'compare','targetAttribute'=>'new_password'],
            
		];
	}

	public function validateCurrentPassoward($attribute, $params)
    {
            if ( !$this->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Current password is not valid.');
            }
    }

    public function save(){
    	if($this->validate()){
    		return true;
    	}else{
    		return false;
    	}
    }

}