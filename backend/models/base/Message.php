<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * @property int $masterts [timestamp]
 * @property int $localts [timestamp]
 *
 */
class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['localts', 'masterts'], 'safe'],
            [['language'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language'    => Yii::t('app', 'Language'),
            'translation' => Yii::t('appMenu', 'Translation')
        ];
    }

    public function getTitleSuffix()
    {
        return $this->translation;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(\backend\models\Sourcemessage::class, ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        return new \backend\models\MessageQuery(get_called_class());
    }
}
