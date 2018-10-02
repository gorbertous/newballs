<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * Model Class Sourcemessagescan
 *
 * @property integer $id
 * @property integer $source_message_id
 * @property string $category
 * @property string $message
 * @property integer $valid
 * @property integer $loccount
 * @property integer $new
 * @property integer $blacklisted
 *
 * @property \backend\models\Sourcemessage $sourceMessage
 * @property \backend\models\Sourcemessagescanlocation[] $sourceMessageScanlocations
 *
 * @package backend\models\base
 */
class Sourcemessagescan extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_message_id', 'valid', 'loccount', 'new', 'blacklisted'], 'integer'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message_scan';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => Yii::t('modelattr', 'Category'),
            'message'  => Yii::t('modelattr', 'Message'),
            'valid'    => Yii::t('modelattr', 'Valid'),
            'loccount' => Yii::t('modelattr', 'Usages'),
            'new'      => Yii::t('modelattr', 'New'),
            'unused'   => Yii::t('modelattr', 'Unused'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(\backend\models\Sourcemessage::class, ['id' => 'source_message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageScanlocations()
    {
        return $this->hasMany(\backend\models\Sourcemessagescanlocation::class, ['source_message_scan_id' => 'id']);
    }
}
