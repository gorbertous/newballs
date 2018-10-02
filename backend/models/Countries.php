<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $code
 * @property string $text_EN
 * @property string $text_FR
 * @property string $text_DE
 * @property string $continent
 *
 * @property Company[] $companies
 * @property Contacts[] $contacts
 * @property Contacts[] $contacts0
 */
class Countries extends ActiveRecord
{
    use base\TraitContLang;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return self::ContLangRules([
            [['code'], 'string', 'max' => 2],
            [['text'], 'string', 'max' => 255],
            [['continent'], 'string', 'max' => 20],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            self::ContLangLabels(), [
                'code'      => Yii::t('app', 'Code'),
                'continent' => Yii::t('app', 'Continent'),
            ]
        );
    }

    /**
     * @return array
     */
    public static function ContLangAttributes()
    {
        return ['text'];
    }

    /**
     * getter for attribute, returns the correct UI language value
     * !! does NOT fallback to main language
     *
     * @return string
     */
    public function getText()
    {
        return $this->ContLangFieldValue('text');
    }

    /**
     * @return string
     */
    public function getTextFB()
    {
        return $this->ContLangFieldValueFB('text');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::class, ['Co_Code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersNat()
    {
        return $this->hasMany(Members::class, ['nationality' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersAdd()
    {
        return $this->hasMany(Members::class, ['co_code' => 'code']);
    }

   
     /**
     * {@inheritdoc}
     * @return CountriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountriesQuery(get_called_class());
    }

}
