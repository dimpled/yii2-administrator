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

    // public function sendEmails()
    // {
        

    //     if ($user!=null) {
            
    //         $mailer = Yii::$app->mailer;
    //         $mailer->viewPath = '@dimple/administrator/views/mail';
    //         $mailer->getView()->theme = Yii::$app->view->theme;

    //         return Yii::$app->mailer->compose([
    //                 'html' => 'passwordReset-html', 
    //                 'text' => 'passwordReset-text'], 
    //                 ['user' => $user,'token'=>$token])
    //                 ->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
    //                 ->setTo($this->email)
    //                 ->setSubject('Password reset for ' . \Yii::$app->name)
    //                 ->send();
    //     }

    //     return false;
    // }
}
