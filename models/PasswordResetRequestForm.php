<?php
namespace dimple\administrator\models;

use Yii;
use dimple\administrator\models\User;
use dimple\administrator\Mailer;
use yii\base\Model;
use dimple\administrator\components\ConfirmationValidator;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $mailer;

    public function init()
    {
        $this->mailer = Yii::$container->get(Mailer::className());
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist','targetClass' => 'dimple\administrator\models\User','message'=>Yii::t('user','There is no user with such email.')],
            ['email', 'dimple\administrator\components\ConfirmationValidator'],
        ];
    }

    public function sendEmail(){

        $user = User::findByEmail($this->email);
        if($user != null){
            $user->generatePasswordResetToken();
            if($user->save()){
                $this->mailer->sendRecoveryMessage($user);
                return true;
            }
        }

        return false;
    }

}
