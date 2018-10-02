<?php

namespace common\rbac\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class Authitemchild extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['childtype'], 'integer'],
            [['parent', 'child'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => Yii::t('modelattr', 'Parent'),
            'child'  => Yii::t('modelattr', 'Child'),
        ];
    }

    /**
     * @return string
     */
    public function getTitleSuffix()
    {
        return $this->parent . '/' . $this->child;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Authitem::class, ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(Authitem::class, ['name' => 'child']);
    }


    /**
     * @inheritdoc
     * @return AuthitemchildQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthitemchildQuery(get_called_class());
    }

}
