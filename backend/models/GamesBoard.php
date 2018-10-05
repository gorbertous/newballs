<?php

namespace backend\models;

use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;
use Yii;

/**
 * This is the model class for table "games_board".
 *
 * @property int $id
 * @property int $c_id
 * @property int $termin_id
 * @property int $member_id
 * @property int $court_id
 * @property int $slot_id
 * @property int $status_id
 * @property int $fines
 * @property int $tokens
 * @property int $late
 *
 * @property Clubs $c
 * @property PlayDates $termin
 * @property Members $member
 */
class GamesBoard extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'games_board';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'termin_id'], 'required'],
            [['c_id', 'termin_id', 'member_id', 'court_id', 'slot_id', 'status_id', 'fines', 'tokens', 'late'], 'integer'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['termin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayDates::className(), 'targetAttribute' => ['termin_id' => 'termin_id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('modelattr', 'ID'),
            'c_id'      => Yii::t('modelattr', 'Club'),
            'termin_id' => Yii::t('modelattr', 'Date'),
            'member_id' => Yii::t('modelattr', 'Member'),
            'court_id'  => Yii::t('modelattr', 'Court'),
            'slot_id'   => Yii::t('modelattr', 'Slot'),
            'status_id' => Yii::t('modelattr', 'Status'),
            'fines'     => Yii::t('modelattr', 'Fines'),
            'tokens'    => Yii::t('modelattr', 'Tokens'),
            'late'      => Yii::t('modelattr', 'Late'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'audittrail' => [
                'class' => AuditTrailBehavior::className(),
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
        return 'Rota';
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
    public function getTermin()
    {
        return $this->hasOne(PlayDates::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
    }

    /**
     * {@inheritdoc}
     * @return GamesBoardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GamesBoardQuery(get_called_class());
    }

}
