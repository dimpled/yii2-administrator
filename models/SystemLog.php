<?php

namespace dimple\administrator\models;

use Yii;
use \yii\log\Logger;
use yii\helpers\Html;
/**
 * This is the model class for table "system_log".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class SystemLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => Yii::t('app', 'Level'),
            'category' => Yii::t('app', 'Category'),
            'log_time' => Yii::t('app', 'Log Time'),
            'prefix' => Yii::t('app', 'Prefix'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

    public static function getLevels()
    {
        $_level = [
                Logger::LEVEL_ERROR => 'error',
                Logger::LEVEL_WARNING => 'warning',
                Logger::LEVEL_INFO => 'info',
                Logger::LEVEL_TRACE => 'trace',
                Logger::LEVEL_PROFILE_BEGIN => 'profile begin',
                Logger::LEVEL_PROFILE_END => 'profile end'
        ];

        return  $_level;
    }

    public function getLevelColor()
    {
        switch (Logger::getLevelName($this->level)) {
            case 'error':
                $color = 'danger';
                break;
            case 'warning':
                $color = 'warning';
                break;
            case 'info':
                $color = 'info';
                break;
            default:
                $color = 'default';
                break;
        }

        return Html::tag('span',Yii::t('user', ucfirst(Logger::getLevelName($this->level))), [
                        'class' => 'btn btn-xs btn-'.$color.' btn-block'
        ]);
    }
}
