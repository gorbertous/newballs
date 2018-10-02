<?php

namespace backend\models\base;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * Trait TraitBlameableTimestamp
 * @package backend\models\base
 *
 * @gorbertous
 */
trait TraitBlameableTimestamp
{
    /**
     * @return array
     */
    public function BTbehaviors()
    {
        return [
            'Bbehavior' => [
                'class' => BlameableBehavior::class
            ],
            'Tbehavior' => [
                'class' => TimestampBehavior::class
            ]
        ];
    }

    public static function BTLabels()
    {
        return [
            'createUserName' => Yii::t('modelattr', 'Created by'),
            'updateUserName' => Yii::t('modelattr', 'Updated by'),
            'created_at'     => Yii::t('modelattr', 'Created at'),
            'updated_at'     => Yii::t('modelattr', 'Updated at')
        ];
    }

    /**
     * Blameable behaviour helper methods
     * @return mixed
     */
    public function getCreateUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @getCreateUserName
     * @return string
     */
    public function getCreateUserName()
    {
        /* @var $this ActiveRecord */
        return $this->createUser ? $this->createUser->member->firstname . ' ' . $this->createUser->member->lastname : '- no user -';
    }

    /**
     * @getUpdateUser
     * @return mixed
     */
    public function getUpdateUser()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @getUpdateUserName
     * @return string
     */
    public function getUpdateUserName()
    {
        /* @var $this ActiveRecord */
        return $this->updateUser ? $this->updateUser->member->firstname . ' ' . $this->updateUser->member->lastname : '- no user -';
    }

    /**
     * @getLastUser
     * @return mixed
     */
    public function getLastUser()
    {
        /* @var $this ActiveRecord */
        if (empty($this->updateUser)) {
            /* @var $this ActiveRecord */
            return $this->createUser;
        } else {
            return $this->updateUser;
        }
    }

    /**
     * @getLastUserName
     * @return string
     */
    public function getLastUserName()
    {
        /* @var $this ActiveRecord */
        return $this->lastUser ? $this->lastUser->member->firstname . ' ' . $this->lastUser->member->lastname : '- no user -';
    }

}
