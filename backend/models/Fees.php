<?php

namespace backend\models;

use Yii;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;

/**
 * This is the model class for table "fees".
 *
 * @property int $id
 * @property int $c_id
 * @property int $mem_type_id
 * @property string $mem_fee
 *
 * @property Clubs $c
 * @property MembershipType $memType
 */
class Fees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'mem_type_id','mem_fee'], 'required'],
            [['c_id', 'mem_type_id'], 'integer'],
            [['mem_fee'], 'number'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['mem_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['mem_type_id' => 'mem_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modelattr', 'ID'),
            'c_id' => Yii::t('modelattr', 'Club'),
            'mem_type_id' => Yii::t('modelattr', 'Membership Type'),
            'mem_fee' => Yii::t('modelattr', 'Fee'),
        ];
    }
    
     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'audittrail' => [
                'class'         => AuditTrailBehavior::className(),
                // some of the optional configurations
//    		'ignoredAttributes'=>['created_at','updated_at'],
                'consoleUserId' => 1,
//			'attributeOutput'=>[
//				'desktop_id'=>function ($value) {
//					$model = Desktop::findOne($value);
//					return sprintf('%s %s', $model->manufacturer, $model->device_name);
//				},
//				'last_checked'=>'datetime',
//			],
            ],
        ];
    }
    
    public function getTitleSuffix()
    {
        return 'Fees';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Clubs::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemType()
    {
        return $this->hasOne(MembershipType::className(), ['mem_type_id' => 'mem_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return FeesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeesQuery(get_called_class());
    }
}
