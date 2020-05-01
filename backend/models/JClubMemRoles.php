<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j_club_mem_roles".
 *
 * @property int $id
 * @property int $member_id
 * @property int $role_id
 *
 * @property Clubs $c
 * @property Members $member
 * @property ClubRoles $role
 */
class JClubMemRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'j_club_mem_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'role_id'], 'required'],
            [['member_id', 'role_id'], 'integer'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClubRoles::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'role_id' => Yii::t('modelattr', 'Role ID'),
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
    public function getRole()
    {
        return $this->hasOne(ClubRoles::className(), ['id' => 'role_id']);
    }

    /**
     * {@inheritdoc}
     * @return JClubMemRolesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JClubMemRolesQuery(get_called_class());
    }
}
