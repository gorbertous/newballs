<?php

namespace backend\models;

use Yii;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;

/**
 * This is the model class for table "location".
 *
 * @property int $location_id
 * @property int $c_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $co_code
 * @property double $google_par_one
 * @property double $google_par_two
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Countries $coCode
 * @property Clubs $club
 */
class Location extends \yii\db\ActiveRecord
{

    use \backend\models\base\TraitBlameableTimestamp;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['google_par_one', 'google_par_two'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 200],
            [['phone','zip'], 'string', 'max' => 20],
            [['city'], 'string', 'max' => 50],
            [['co_code'], 'string', 'max' => 2],
            [['co_code'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['co_code' => 'code']],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        
        return array_merge(
            self::BTLabels(),
            [
            'location_id'    => Yii::t('modelattr', 'ID'),
            'c_id'           => Yii::t('modelattr', 'Club'),
            'name'           => Yii::t('modelattr', 'Name'),
            'address'        => Yii::t('modelattr', 'Address'),
            'zip'            => Yii::t('modelattr', 'Zip'),
            'city'           => Yii::t('modelattr', 'City'),
            'phone'          => Yii::t('modelattr', 'Phone'),
            'co_code'        => Yii::t('modelattr', 'Co Code'),
            'google_par_one' => Yii::t('modelattr', 'Latitude'),
            'google_par_two' => Yii::t('modelattr', 'Longtitude'),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
                self::BTbehaviors(), [
            'audittrail' => [
                'class'             => AuditTrailBehavior::className(),
                // some of the optional configurations
                'ignoredAttributes' => ['c_id', 'created_at', 'updated_at', 'created_by', 'updated_by'],
                'consoleUserId'     => 1,
//                'manyToManyBehaviourExtensions' => AuditTrailBehavior::LINK_MANY_YII2TECH_AR_LINKMANY,
                'attributeOutput'   => [
//                    'trainings_ids' => function ($value) {
//                        $model = Trainings::findAll(explode(', ', $value));
//                        return join(', ', ArrayHelper::getColumn($model, 'nameFB'));
//                    },
                    'last_checked' => 'datetime'
                ]
            ],
        ]);
    }

    public function getTitleSuffix()
    {
        return $this->name;
    }
    
     /**
     * @return mixed
     */
    public function getCountrytranslated(): string
    {
        if (isset($this->country)) {
            return $this->country->textFB;
        }
        return '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['code' => 'co_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Clubs::className(), ['c_id' => 'c_id']);
    }

    /**
     * {@inheritdoc}
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }

}
