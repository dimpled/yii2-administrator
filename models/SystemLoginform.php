<?php

namespace dimple\administrator\models;

use Yii;
use UAParser\UAParser;

/**
 * This is the model class for table "system_loginform".
 *
 * @property integer $log_id
 * @property string $username
 * @property string $password
 * @property string $ip
 * @property string $user_agent
 * @property integer $time
 * @property integer $success
 */
class SystemLoginform extends \yii\db\ActiveRecord
{

    private $_uaParser=null;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_loginform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'ip', 'user_agent'], 'required'],
            [['time', 'success'], 'integer'],
            [['username', 'password'], 'string', 'max' => 128],
            [['ip'], 'string', 'max' => 16],
            [['user_agent'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'ip' => Yii::t('app', 'Ip'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'time' => Yii::t('app', 'Time'),
            'success' => Yii::t('app', 'Success'),
        ];
    }

    public function getUA(){
        if($this->_uaParser===null){
            $this->_uaParser = new UAParser();         
        }
        return $this->_uaParser;
    }

    public function getUaBrowser()
    {
        $this->getUA();
        $result =  $this->_uaParser->parse($this->user_agent);
        return $result->getBrowser();
    }

    public function getUaOs()
    {
        $this->getUA();
        $result =  $this->_uaParser->parse($this->user_agent);
        return $result->getOperatingSystem()->getFamily();
    }

    public function getUaDevice(){
        $this->getUA();
        $result =  $this->_uaParser->parse($this->user_agent);
        return $result->getDevice()->getModel();
    }
}
