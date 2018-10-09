<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email_form".
 *
 * @property int $id
 * @property int $user_id
 * @property string $setToEmail
 * @property string $setToName
 * @property string $setFromEmail
 * @property string $setFromName
 * @property string $subject
 * @property string $textBody
 * @property int $status
 * @property string $created_at
 * @property string $status_text
 * @property string $send_at
 */
class EmailForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['textBody'], 'required'],
            [['textBody', 'status_text'], 'string'],
            [['created_at', 'send_at'], 'safe'],
            [['setToEmail', 'setToName', 'setFromEmail', 'setFromName'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'setToEmail' => Yii::t('app', 'Set To Email'),
            'setToName' => Yii::t('app', 'Set To Name'),
            'setFromEmail' => Yii::t('app', 'Set From Email'),
            'setFromName' => Yii::t('app', 'Set From Name'),
            'subject' => Yii::t('app', 'Subject'),
            'textBody' => Yii::t('app', 'Text Body'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'status_text' => Yii::t('app', 'Status Text'),
            'send_at' => Yii::t('app', 'Send At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return EmailFormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmailFormQuery(get_called_class());
    }
}