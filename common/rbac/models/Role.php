<?php

namespace common\rbac\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * Class Role
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property \common\rbac\models\Authitem $itemName
 * @package common\rbac\models
 */
class Role extends ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64]
        ]; 
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Role'),
        ];
    }

    /**
     * Relation with User model.
     * Role has_many User via User.id -> user_id
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasMany(User::class, ['id' => 'user_id']);
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getItemName() 
    { 
        return $this->hasOne(Authitem::class, ['name' => 'item_name']);
    } 
    

    /** 
     * @inheritdoc 
     * @return \common\rbac\models\RoleQuery the active query used by this AR class. 
     */ 
    public static function find() 
    { 
        return new RoleQuery(get_called_class());
    } 
}
