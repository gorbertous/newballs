<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j_club_courts".
 *
 * @property int $id
 * @property int $court_id
 * @property int $c_id
 *
 * @property Clubs $c
 */
class JClubCourts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'j_club_courts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['court_id', 'c_id'], 'required'],
            [['court_id', 'c_id'], 'integer'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
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
     * {@inheritdoc}
     * @return JClubCourtsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JClubCourtsQuery(get_called_class());
    }
}
