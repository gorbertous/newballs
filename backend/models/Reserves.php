<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "reserves".
 *
 * @property int $id
 * @property int $member_id
 * @property int $termin_id
 * @property int $c_id
 *
 * @property Clubs $c
 * @property Members $member
 * @property PlayDates $termin
 */
class Reserves extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reserves';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'termin_id', 'c_id'], 'integer'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
            [['termin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayDates::className(), 'targetAttribute' => ['termin_id' => 'termin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modelattr', 'ID'),
            'member_id' => Yii::t('modelattr', 'Member ID'),
            'termin_id' => Yii::t('modelattr', 'Termin ID'),
            'c_id' => Yii::t('modelattr', 'C ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(Clubs::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermin()
    {
        return $this->hasOne(PlayDates::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * {@inheritdoc}
     * @return ReservesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReservesQuery(get_called_class());
    }
}
