<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j_news_tags".
 *
 * @property int $id
 * @property int $tag_id
 * @property int $news_id
 *
 * @property Tags $tag
 * @property News $news
 */
class JNewsTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'j_news_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'news_id'], 'required'],
            [['tag_id', 'news_id'], 'integer'],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['tag_id' => 'tag_id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modelattr', 'ID'),
            'tag_id' => Yii::t('modelattr', 'Tag ID'),
            'news_id' => Yii::t('modelattr', 'News ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tags::className(), ['tag_id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    /**
     * {@inheritdoc}
     * @return JNewsTagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JNewsTagsQuery(get_called_class());
    }
}
