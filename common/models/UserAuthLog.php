<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class UserAuthLog Model
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $date
 * @property integer $cookieBased
 * @property integer $duration
 * @property string $error
 * @property string $ip
 * @property string $host
 * @property string $url
 * @property string $userAgent
 *
 * @property User $user
 *
 * @package common\models
 */
class UserAuthLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserAuthLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'date', 'cookieBased', 'duration'], 'integer'],
            [['error', 'ip', 'host', 'url', 'userAgent'], 'string', 'max' => 255],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'userId'      => Yii::t('modelattr', 'User ID'),
            'date'        => Yii::t('modelattr', 'Date'),
            'cookieBased' => Yii::t('modelattr', 'Cookie Based'),
            'duration'    => Yii::t('modelattr', 'Duration'),
            'error'       => Yii::t('modelattr', 'Error'),
            'ip'          => Yii::t('modelattr', 'Ip'),
            'host'        => Yii::t('modelattr', 'Host'),
            'url'         => Yii::t('modelattr', 'Url'),
            'userAgent'   => Yii::t('modelattr', 'User Agent'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new UserAuthLogQuery(get_called_class());
    }
}
