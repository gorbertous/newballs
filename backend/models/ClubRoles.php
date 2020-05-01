<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "club_roles".
 *
 * @property int $id
 * @property string $role
 *
 * @property JClubMemRoles[] $jClubMemRoles
 */
class ClubRoles extends \yii\db\ActiveRecord
{
    /** Colors constants */
    const COLOR_WHITE = 'badge bg-white';
    const COLOR_BLACK = 'badge bg-black';
    const COLOR_RED = 'badge bg-red';
    const COLOR_GREEN = 'badge bg-green';
    const COLOR_YELLOW = 'badge bg-yellow';
    const COLOR_ORANGE = 'badge bg-orange';
    const COLOR_BLUE = 'badge bg-blue';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'club_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role'], 'required'],
            [['id'], 'integer'],
            [['role'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modelattr', 'ID'),
            'role' => Yii::t('modelattr', 'Role'),
        ];
    }
    
    public static function getRoleColor(int $role_id = null): string
    {
        switch (true) {
            case ($role_id == 1):
                return self::COLOR_GREEN;
                break;
            case ($role_id == 2):
                return self::COLOR_RED;
                break;
            case ($role_id == 3):
                return self::COLOR_ORANGE;
                break;
            case ($role_id == 4):
                return self::COLOR_BLUE;
                break;
            case ($role_id == 5):
                return self::COLOR_BLACK;
                break;
            default:
                return self::COLOR_WHITE;
                break;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJClubMemRoles()
    {
        return $this->hasMany(JClubMemRoles::className(), ['role_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ClubRolesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClubRolesQuery(get_called_class());
    }
}
