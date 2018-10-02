<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j_court_booked".
 *
 * @property int $id
 * @property int $court_id
 * @property int $termin_id
 * @property int $booked_by
 *
 * @property PlayDates $termin
 * @property Members $bookedBy
 */
class JCourtBooked extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'j_court_booked';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['court_id', 'termin_id', 'booked_by'], 'required'],
            [['court_id', 'termin_id', 'booked_by'], 'integer'],
            [['termin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayDates::className(), 'targetAttribute' => ['termin_id' => 'termin_id']],
            [['booked_by'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['booked_by' => 'member_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modelattr', 'ID'),
            'court_id' => Yii::t('modelattr', 'Court ID'),
            'termin_id' => Yii::t('modelattr', 'Termin ID'),
            'booked_by' => Yii::t('modelattr', 'Booked By'),
        ];
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
    public function getBookedBy()
    {
        return $this->hasOne(Members::className(), ['member_id' => 'booked_by']);
    }

    /**
     * {@inheritdoc}
     * @return JCourtBookedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JCourtBookedQuery(get_called_class());
    }
}
