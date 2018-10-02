<?php

namespace backend\models;

use Yii;

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
            'c_id' => Yii::t('modelattr', 'C ID'),
            'mem_type_id' => Yii::t('modelattr', 'Mem Type ID'),
            'mem_fee' => Yii::t('modelattr', 'Mem Fee'),
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
