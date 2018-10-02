<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "club_styles".
 *
 * @property int $c_css_id
 * @property string $c_css
 * @property string $c_menu_image
 * @property string $c_top_image
 * @property string $c_top
 * @property string $c_left
 * @property string $c_menu
 * @property string $c_right
 * @property string $c_footer
 * @property string $c_main_colour_EN
 * @property string $c_main_colour_FR
 * @property string $c_colour_sample
 * @property int $is_active
 *
 * @property Clubs[] $clubs
 */
class ClubStyles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'club_styles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_css_id'], 'required'],
            [['c_css_id', 'is_active'], 'integer'],
            [['c_css', 'c_menu_image', 'c_top_image', 'c_top', 'c_left', 'c_menu', 'c_right', 'c_footer', 'c_main_colour_EN', 'c_main_colour_FR', 'c_colour_sample'], 'string', 'max' => 50],
            [['c_css_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'c_css_id' => Yii::t('modelattr', 'C Css ID'),
            'c_css' => Yii::t('modelattr', 'C Css'),
            'c_menu_image' => Yii::t('modelattr', 'C Menu Image'),
            'c_top_image' => Yii::t('modelattr', 'C Top Image'),
            'c_top' => Yii::t('modelattr', 'C Top'),
            'c_left' => Yii::t('modelattr', 'C Left'),
            'c_menu' => Yii::t('modelattr', 'C Menu'),
            'c_right' => Yii::t('modelattr', 'C Right'),
            'c_footer' => Yii::t('modelattr', 'C Footer'),
            'c_main_colour_EN' => Yii::t('modelattr', 'C Main Colour  En'),
            'c_main_colour_FR' => Yii::t('modelattr', 'C Main Colour  Fr'),
            'c_colour_sample' => Yii::t('modelattr', 'C Colour Sample'),
            'is_active' => Yii::t('modelattr', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Clubs::className(), ['css_id' => 'c_css_id']);
    }

    /**
     * {@inheritdoc}
     * @return ClubStylesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClubStylesQuery(get_called_class());
    }
}
