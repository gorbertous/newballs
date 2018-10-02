<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * Model Class Sourcemessagescanlocation
 *
 * @property integer $id
 * @property integer $source_message_scan_id
 * @property string $location
 *
 * @property \backend\models\SourceMessageScan $sourceMessageScan
 *
 * @package backend\models\base
 */
class Sourcemessagescanlocation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_message_scan_id'], 'integer'],
            [['location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message_scanlocation';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'location' => Yii::t('modelattr', 'Location')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageScan()
    {
        return $this->hasOne(\backend\models\SourceMessageScan::class, ['id' => 'source_message_scan_id']);
    }
}
