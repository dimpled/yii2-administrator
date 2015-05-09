<?php
namespace dimple\administrator\models;

use Yii;
use yii\helpers\Url;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use dimple\administrator\models\Profile;
use dimple\administrator\models\Token;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED    = 0;
    const STATUS_ACTIVE     = 10;

    const TYPE_CONFIRMATION = 'confirm';
    const TYPE_RECOVERY     = 'recover';

    public $password =  null;
    public $enableConfirmation;
    public $new_password;
    public $current_password;

    public function scenarios()
    {
        return [
            'default'   => ['*'],
            'login'     => ['username', 'password'],
            'connect'   => ['username', 'email','password','confirmed_at'],
            'create'    => ['username', 'email', 'password','enableConfirmation'],
            'update'    => ['username', 'email', 'password'],
            'request'   => ['email'],
            'recovery'  => ['password','password_reset_token'],
            'account'   => ['username','email','new_password','current_password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password'], 'required','on'=>['create']],
            [['username'], 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            [['username'], 'string', 'min' => 3, 'max' => 50],
            [['password'], 'string', 'min' => 6],
            [['username','email'],'trim'],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'login_time', 'last_login_time'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['email','password_reset_token'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip', 'login_ip', 'last_login_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['enableConfirmation'],'required','on'=>['create']],
            [['enableConfirmation'], 'default', 'value' => '1'],
            [['new_password'],'string','min'=>6],
            [['new_password', 'current_password'], 'required','on'=>['account']],
            [['current_password'],'validateCurrentPassoward']
        ];
    }

    public function validateCurrentPassoward($attribute, $params)
    {
            if ( !$this->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Current password is not valid.');
            }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getPasswordResetToken()
    {
        return $this->password_reset_token;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function findByAtivateToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'activate_token' => $token,
            'status' => self::STATUS_DELETED,
        ]);
    }

    public function getActivateToken()
    {
        return $this->activate_token;
    }

    public function generateActivateToken()
    {
        $this->activate_token = Yii::$app->security->generateRandomString(64) . '_' . time();
    }

    /**
     * Removes activate_token reset token
     */
    public function removeActivateToken()
    {
        $this->activate_token = null;
    }

    /**
     * Finds user by Email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

   /**
     * Finds user by Username or Email  
     *
     * @param string $email or $username
     * @return static|null
     */
    public static function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return self::findByEmail($usernameOrEmail);
        }
        return self::findByUsername($usernameOrEmail);
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return ($this->status == self::STATUS_ACTIVE && $this->confirmed_at != null);
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    public function getRegistrationIp()
    {
        return $this->registration_ip = Yii::$app->request->getUserIP();
    }

    public function getConfirmUrl($type='confirm'){

        if($type==self::TYPE_CONFIRMATION){
            $route = '/administrator/registration/confirm';
            $token = $this->getActivateToken();
        }
        elseif($type==self::TYPE_RECOVERY){
             $route = '/administrator/recovery/reset';
             $token = $this->getPasswordResetToken();
        }
        else  throw new NotSupportedException('"'.$type.'" is not define.');;

        return Url::to([$route, 'token' => $token], true);
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();            
            $this->getRegistrationIp();
        }
        
        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $profile = new Profile;
            $profile->user_id = $this->id;
            $profile->gravatar_email = $this->email;
            $profile->save(false);
        }
        parent::afterSave($insert, $changedAttributes);
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    public function getUsernameFromEmail()
    {
        $this->username = explode('@', $this->email)[0];
        if ($this->validate(['username'])) {
            return;
        }
    }

    public function setLastUpdate(){
            if($this->login_time!=null){
                $this->last_login_time = $this->login_time;
            }else{
                $this->last_login_time = time();
            }

            $this->login_time = time();

            if($this->login_ip!=null){
                $this->last_login_ip = $this->login_ip;
            }else{
                $this->last_login_ip = Yii::$app->request->getUserIP();
            }
            $this->login_ip = Yii::$app->request->getUserIP();
    }

}
