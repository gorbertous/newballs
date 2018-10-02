<?php

namespace backend\models;

use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;
use common\dictionaries\OutcomeStatus;
use Yii;

/**
 * This is the model class for table "play_dates".
 *
 * @property int $termin_id
 * @property int $c_id
 * @property int $location_id
 * @property string $termin_date
 * @property int $active
 * @property int $season_id
 * @property int $session_id
 * @property int $courts_no
 * @property int $slots_no
 * @property int $is_recurring
 * @property int $recurr_no
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property GamesBoard[] $gamesBoards
 * @property JCourtBooked[] $jCourtBookeds
 * @property Clubs $c
 * @property Location $location
 * @property Reserves[] $reserves
 * @property Scores[] $scores
 */
class PlayDates extends \yii\db\ActiveRecord
{

    use \backend\models\base\TraitBlameableTimestamp;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'play_dates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'location_id', 'season_id', 'termin_date', 'session_id', 'courts_no', 'slots_no',], 'required'],
            [['c_id', 'location_id', 'active', 'season_id', 'session_id', 'courts_no', 'slots_no', 'is_recurring', 'recurr_no', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['termin_date'], 'safe'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {


        return array_merge(
                self::BTLabels(), [
            'termin_id'    => Yii::t('modelattr', 'ID'),
            'c_id'         => Yii::t('modelattr', 'Club'),
            'location_id'  => Yii::t('modelattr', 'Location'),
            'termin_date'  => Yii::t('modelattr', 'Date'),
            'active'       => Yii::t('modelattr', 'Active'),
            'season_id'    => Yii::t('modelattr', 'Season'),
            'session_id'   => Yii::t('modelattr', 'Duration'),
            'courts_no'    => Yii::t('modelattr', 'Courts No'),
            'slots_no'     => Yii::t('modelattr', 'Slots No'),
            'is_recurring' => Yii::t('modelattr', 'Is Recurring'),
            'recurr_no'    => Yii::t('modelattr', 'Recurr No'),
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
        return 'Play Dates';
    }

    /**
     * Generate rota for a given date
     */
    public function generateGamesBoards($termin_id)
    {
        $playdate = PlayDates::findOne(['termin_id' => $termin_id]);
        $rota = GamesBoard::findOne(['termin_id' => $termin_id]);

        if (empty($rota) & !empty($playdate)) {
            for ($i = 1; $i <= $playdate->courts_no; $i++) {
                for ($y = 1; $y <= $playdate->slots_no; $y++) {
                    $newrota = new GamesBoard();
                    $newrota->c_id = $playdate->c_id;
                    $newrota->termin_id = $playdate->termin_id;
                    $newrota->member_id = 1;
                    $newrota->court_id = $i;
                    $newrota->slot_id = $y;
                    $newrota->status_id = OutcomeStatus::PENDING;
                    $newrota->tokens = 0;
                    $newrota->late = 0;
                    $newrota->save(false);
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamesBoards()
    {
        return $this->hasMany(GamesBoard::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJCourtBookeds()
    {
        return $this->hasMany(JCourtBooked::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Clubs::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserves()
    {
        return $this->hasMany(Reserves::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasMany(Scores::className(), ['termin_id' => 'termin_id']);
    }

    /**
     * {@inheritdoc}
     * @return PlayDatesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlayDatesQuery(get_called_class());
    }

}
