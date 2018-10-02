<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scores".
 *
 * @property int $score_id
 * @property int $termin_id
 * @property int $court_id
 * @property string $set_one
 * @property string $set_two
 * @property string $set_three
 * @property string $set_four
 * @property string $set_five
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PlayDates $termin
 */
class Scores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['termin_id'], 'required'],
            [['termin_id', 'court_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['set_one', 'set_two', 'set_three', 'set_four', 'set_five'], 'string', 'max' => 20],
            [['termin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayDates::className(), 'targetAttribute' => ['termin_id' => 'termin_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'score_id' => Yii::t('modelattr', 'Score ID'),
            'termin_id' => Yii::t('modelattr', 'Termin ID'),
            'court_id' => Yii::t('modelattr', 'Court ID'),
            'set_one' => Yii::t('modelattr', 'Set One'),
            'set_two' => Yii::t('modelattr', 'Set Two'),
            'set_three' => Yii::t('modelattr', 'Set Three'),
            'set_four' => Yii::t('modelattr', 'Set Four'),
            'set_five' => Yii::t('modelattr', 'Set Five'),
            'created_by' => Yii::t('modelattr', 'Created By'),
            'updated_by' => Yii::t('modelattr', 'Updated By'),
            'created_at' => Yii::t('modelattr', 'Created At'),
            'updated_at' => Yii::t('modelattr', 'Updated At'),
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
     * {@inheritdoc}
     * @return ScoresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScoresQuery(get_called_class());
    }
}
