<?php

namespace dimple\administrator\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $user_id
 * @property string $name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'public_email', 'gravatar_email', 'location', 'website'], 'string', 'max' => 255],
            [['public_email','gravatar_email'],'email'],
            [['website'],'url'],
            [['bio'], 'string'],         
            [['gravatar_id'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'public_email' => Yii::t('app', 'Public Email'),
            'gravatar_email' => Yii::t('app', 'Gravatar Email'),
            'gravatar_id' => Yii::t('app', 'Gravatar ID'),
            'location' => Yii::t('app', 'Location'),
            'website' => Yii::t('app', 'Website'),
            'bio' => Yii::t('app', 'Bio'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('gravatar_email')) {
                $this->setAttribute('gravatar_id', md5(strtolower($this->getAttribute('gravatar_email'))));
            }
            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}