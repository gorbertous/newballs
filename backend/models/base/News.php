<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property int $c_id
 * @property string $category
 * @property string $title_FR
 * @property string $title_EN
 * @property string $title_DE
 * @property string $content_FR
 * @property string $content_EN
 * @property string $content_DE
 * @property string $featured_img
 * @property string $featured_img_orig
 * @property string $content_imgs
 * @property string $content_imgs_orig
 * @property string $source_url
 * @property int $is_public
 * @property int $is_valid
 * @property int $to_newsletter
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property JNewsTags[] $jNewsTags
 * @property Clubs $c
 */
class News extends ActiveRecord
{
    use TraitBlameableTimestamp;
    use TraitFileUploads;
    use TraitContLang;
    
    public $tags_ids = [];
//    public $leg_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return self::ContLangRules([
            [['category', 'title', 'content'], 'required'],
            [['c_id', 'is_public', 'is_valid', 'to_newsletter', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['category'], 'string', 'max' => 1],
            [['tags_ids'], 'safe'],
            [['title', 'featured_img', 'featured_img_orig', 'source_url'], 'string', 'max' => 256],
            [['content_imgs', 'content_imgs_orig'], 'string', 'max' => 512],
            ['source_url', 'url', 'defaultScheme' => 'http'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            self::BTLabels(), self::ContLangLabels(), [
                'c_id'      => Yii::t('modelattr', 'Club'),
                'category'      => Yii::t('modelattr', 'Category'),
                'featured_img'  => Yii::t('modelattr', 'Featured image'),
                'content_imgs'  => Yii::t('modelattr', 'Images') . ' / '. Yii::t('modelattr', 'Files'),
                'is_public'     => Yii::t('modelattr', 'Is Public'),
                'is_valid'      => Yii::t('modelattr', 'Is Valid'),
                'tags_ids'      => Yii::t('modelattr', 'Tags'),
                'to_newsletter' => Yii::t('modelattr', 'Send with next newsletter'),
                'source_url'    => Yii::t('modelattr', 'Source Url'),
                'tags_ids'      => Yii::t('modelattr', 'Tags'),
            ]
        );
    }

    public function getAjaxfileinputs()
    {
        return [
            'ajaxfilefeatured' => [
                'Storefield'            => 'featured_img',
                'Origifield'            => 'featured_img_orig',
                'optionsmultiple'       => false,
                'allowedfileextensions' => $this->FI_IMAGES,
                'maxuploadfilesize'     => 1024 * 1024 * 10,
                'resizeimagestosize'    => $this->IMGRESIZE_M_1024,
                'resizeimagestoquality' => $this->IMGQUALITY_H_90
            ],
            'ajaxfilecontent' => [
                'Storefield'            => 'content_imgs',
                'Origifield'            => 'content_imgs_orig',
                'optionsmultiple'       => true,
                'allowedfileextensions' => $this->FI_IMAGES_DOCUMETS,
                'maxuploadfilesize'     => 1024 * 1024 * 10,
                'resizeimagestosize'    => $this->IMGRESIZE_M_1024,
                'resizeimagestoquality' => $this->IMGQUALITY_H_90
            ]
        ];
    }

    /**
     * @return array
     */
    public static function ContLangAttributes()
    {
        return ['title', 'content'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(self::BTbehaviors(), [
            'audittrail'     => [
                'class' => AuditTrailBehavior::className(),
                // some of the optional configurations
                'ignoredAttributes' => ['id', 'c_id', 'created_at', 'updated_at', 'created_by', 'updated_by'],
                'consoleUserId'     => 1,
//                'manyToManyBehaviourExtensions' => AuditTrailBehavior::LINK_MANY_NONE,
                'attributeOutput'   => [
                    'last_checked'  => 'datetime',
                ]
            ]
        ]);
    }


    public function getTitleSuffix()
    {
        return $this->title;
    }

    /**
     * getter for attribute, returns the correct UI language value
     * !! does NOT fallback to main language
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->ContLangFieldValue('title');
    }

    /**
     * @return string
     */
    public function getTitleFB()
    {
        return $this->ContLangFieldValueFB('title');
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->ContLangFieldValue('content');
    }

    /**
     * @return string
     */
    public function getContentFB()
    {
        return $this->ContLangFieldValueFB('content');
    }

    /**
     * Getter for isnewLabel
     *
     * @return string
     */

    public function getIsnewLabel()
    {
        if (!empty($this->created_at)) {
            $today = new \DateTime();
            $today->format('Y-m-d H:i:s');
            $converttimestamp = date('Y-m-d H:i:s', $this->created_at);
            $days_passed = $today->diff(new \DateTime($converttimestamp))->days;
        } else {
            $days_passed = 30;
        }

        if ($days_passed < 30) {
            return '&nbsp; &nbsp;<span class="badge label-info"><span class="fa fa-clock-o"></span>'.Yii::t('app', 'New').'</span>';
        } else {
            return '';
        }
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getJNewsTags()
    {
        return $this->hasMany(JNewsTags::className(), ['news_id' => 'id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(\backend\models\Tags::class, ['tag_id' => 'tag_id'])->viaTable('j_news_tags', ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(\backend\models\Clubs::class, ['c_id' => 'c_id']);
    }


    /**
     * @inheritdoc
     * @return \backend\models\NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\NewsQuery(get_called_class());
    }
}
