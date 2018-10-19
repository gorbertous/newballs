<?php

namespace backend\models;

use Yii;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;

/**
 * This is the model class for table "membership_type".
 *
 * @property int $mem_type_id
 * @property int $c_id
 * @property string $name_EN
 * @property string $name_FR
 * @property int $fee
 *
 * @property Members[] $members
 * @property Clubs $c
 */
class MembershipType extends \yii\db\ActiveRecord
{

    use base\TraitContLang;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'membership_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return self::ContLangRules([
                    [['name_EN'], 'required'],
                    [['c_id', 'fee'], 'integer'],
                    [['name'], 'string', 'max' => 100]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(
            self::ContLangLabels(), [
            'mem_type_id' => Yii::t('modelattr', 'Type'),
            'c_id'        => Yii::t('modelattr', 'Club'),
            'fee'         => Yii::t('modelattr', 'Fee'),
            ]
        );
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
        return 'Membership Type';
    }
    
     /**
     * @return array
     */
    public static function ContLangAttributes()
    {
        return ['name'];
    }
    
     /**
     * getter for attribute, returns the correct UI language value
     * !! does NOT fallback to main language
     *
     * @return string
     */
    public function getName()
    {
        return $this->ContLangFieldValue('name');
    }

    /**
     * @return string
     */
    public function getNameFB()
    {
        return $this->ContLangFieldValueFB('name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Members::className(), ['mem_type_id' => 'mem_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Clubs::className(), ['c_id' => 'c_id']);
    }

    /**
     * {@inheritdoc}
     * @return MembershipTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MembershipTypeQuery(get_called_class());
    }

}
