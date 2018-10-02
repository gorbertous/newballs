<?php

namespace backend\models\base;

use Yii;

/**
 * This is the base model class for table "Tags".
 *
 * @property integer $tag_id
 * @property string $name_FR
 * @property string $name_EN
 * @property string $name_DE
 *
 * @property \backend\models\JNewsTags[] $news
 */
class Tags extends \yii\db\ActiveRecord
{

    use TraitContLang;

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return self::ContLangRules([
                    [['name'], 'string', 'max' => 256]
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @return array
     */
    public static function ContLangAttributes()
    {
        return ['name'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            self::ContLangLabels(), [
            'tag_id' => Yii::t('modelattr', 'ID'),
            ]
        );
    }

    public function getTitleSuffix()
    {
        return $this->name;
    }

    /**
     * getter for attribute, returns the correct UI language value
     * !! does NOT fallback to main language
     *
     * @return string
     */
    public function getName()
    {
        return $this->ContLangFieldValue('name');
    }

    /**
     * @return string
     */
    public function getNameFB()
    {
        return $this->ContLangFieldValueFB('name');
    }

    public static function findAllByName($name)
    {
        return Tags::find()
                        ->where(['like', Tags::ContLangFieldValueFBsql('name'), $name])->limit(50)->all();
    }

    public static function findOneByName($name)
    {
        return Tags::find()
                        ->where([Tags::ContLangFieldValueFBsql('name') => $name])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(\backend\models\News::class, ['id' => 'news_id'])->viaTable('j_news_tags', ['tag_id' => 'tag_id']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\TagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\TagsQuery(get_called_class());
    }

}
