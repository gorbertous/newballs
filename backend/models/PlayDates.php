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
            [['c_id', 'location_id', 'season_id', 'termin_date', 'session_id', 'courts_no', 'slots_no', 'recurr_no'], 'required'],
            [['c_id', 'location_id', 'active', 'season_id', 'session_id', 'courts_no', 'slots_no', 'recurr_no', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
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
            'termin_id'   => Yii::t('modelattr', 'ID'),
            'c_id'        => Yii::t('modelattr', 'Club'),
            'location_id' => Yii::t('modelattr', 'Location'),
            'termin_date' => Yii::t('modelattr', 'Date'),
            'active'      => Yii::t('modelattr', 'Active'),
            'season_id'   => Yii::t('modelattr', 'Season'),
            'session_id'  => Yii::t('modelattr', 'Duration'),
            'courts_no'   => Yii::t('modelattr', 'Courts No'),
            'slots_no'    => Yii::t('modelattr', 'Slots No'),
            'recurr_no'   => Yii::t('modelattr', 'Recurr No'),
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
    public function generateGamesBoard($termin_id)
    {
        $playdate = PlayDates::findOne(['termin_id' => $termin_id]);
        $rota = GamesBoard::findOne(['termin_id' => $termin_id]);

        if (empty($rota) && !empty($playdate)) {
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
            if ($playdate->recurr_no > 1) {
                for ($a = 1; $a <= $playdate->recurr_no; $a++) {
                    $lastdate = PlayDates::find()->orderBy(['termin_id' => SORT_DESC])->one();

                    $date = new \DateTime($lastdate->termin_date);
                    $date->add(new \DateInterval('P7D'));

                    $recurrdate = new PlayDates();
                    $recurrdate->c_id = $lastdate->c_id;
                    $recurrdate->location_id = $lastdate->location_id;
                    $recurrdate->termin_date = $date->format('Y-m-d H:i:s');
                    $recurrdate->season_id = $lastdate->season_id;
                    $recurrdate->session_id = $lastdate->session_id;
                    $recurrdate->courts_no = $lastdate->courts_no;
                    $recurrdate->slots_no = $lastdate->slots_no;
                    $recurrdate->save(false);

                    for ($i = 1; $i <= $recurrdate->courts_no; $i++) {
                        for ($y = 1; $y <= $recurrdate->slots_no; $y++) {
                            $newrecrota = new GamesBoard();
                            $newrecrota->c_id = $recurrdate->c_id;
                            $newrecrota->termin_id = $recurrdate->termin_id;
                            $newrecrota->member_id = 1;
                            $newrecrota->court_id = $i;
                            $newrecrota->slot_id = $y;
                            $newrecrota->status_id = OutcomeStatus::PENDING;
                            $newrecrota->tokens = 0;
                            $newrecrota->late = 0;
                            $newrecrota->save(false);
                        }
                    }
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
