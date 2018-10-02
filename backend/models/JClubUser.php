<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j_club_user".
 *
 * @property int $id
 * @property int $c_id
 * @property int $user_id
 *
 * @property Clubs $c
 * @property User $user
 */
class JClubUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'j_club_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'user_id'], 'required'],
            [['c_id', 'user_id'], 'integer'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('modelattr', 'User ID'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return JClubUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JClubUserQuery(get_called_class());
    }
}
