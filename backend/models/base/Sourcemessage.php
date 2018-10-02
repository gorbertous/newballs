<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * Model Class Sourcemessage
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 * @property string $TranslationEN
 * @property string $TranslationFR
 * @property string $TranslationDE
 * @property int $localts [timestamp]
 * @property int $masterts [timestamp]
 *
 * @package backend\models\base
 */
class Sourcemessage extends ActiveRecord
{
    public $TranslationEN;
    public $TranslationFR;
    public $TranslationDE;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['TranslationEN', 'TranslationFR', 'TranslationDE', 'localts', 'masterts'], 'safe'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => Yii::t('modelattr', 'Category'),
            'message'  => Yii::t('modelattr', 'Message')
        ];
    }

    /**
     * @inheritdoc
     * @return \backend\models\SourcemessageQuery
     */
    public static function find()
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        return new \backend\models\SourcemessageQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageScan()
    {
        return $this->hasOne(\backend\models\Sourcemessagescan::class, ['source_message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Message::class, ['id' => 'id'])
            ->select('translation');
    }

    /**
     * @return false|null|string
     */
    public function getTranslationEN()
    {
        return $this->hasOne(Message::class, ['id' => 'id'])
            ->select('translation')
            ->andWhere(['language' => 'en'])
            ->scalar();
    }

    /**
     * @return false|null|string
     */
    public function getTranslationFR()
    {
        return $this->hasOne(Message::class, ['id' => 'id'])
            ->select('translation')
            ->andWhere(['language' => 'fr'])
            ->scalar();
    }

    /**
     * @return false|null|string
     */
    public function getTranslationDE()
    {
        return $this->hasOne(Message::class, ['id' => 'id'])
            ->select('translation')
            ->andWhere(['language' => 'de'])
            ->scalar();
    }
}
