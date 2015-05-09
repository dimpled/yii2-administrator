<?php
namespace dimple\administrator\models;

use Yii;
use dimple\administrator\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;


/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword($user)
    {
        $user->scenario = 'recovery';
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save();
    }
}
