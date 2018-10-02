<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
//use yii\helpers\ArrayHelper;
use voskobovich\linker\LinkerBehavior;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;

/**
 * This is the model class for table "clubs".
 *
 * @property int $c_id
 * @property int $css_id
 * @property int $sport_id
 * @property int $season_id
 * @property int $session_id
 * @property int $type_id
 * @property string $name
 * @property string $logo
 * @property string $logo_orig
 * @property string $home_page
 * @property string $rules_page
 * @property string $members_page
 * @property string $rota_page
 * @property string $tournament_page
 * @property string $subscription_page
 * @property string $school_page
 * @property int $coach_stats
 * @property int $token_stats
 * @property int $play_stats
 * @property int $scores
 * @property int $match_instigation
 * @property int $court_booking
 * @property int $money_stats
 * @property int $admin_id
 * @property int $chair_id
 * @property int $location_id
 * @property int $is_active
 * @property int $payment
 * @property int $rota_removal
 * @property int $rota_block
 * @property string $photo_one
 * @property string $photo_two
 * @property string $photo_three
 * @property string $photo_four
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ClubStyles $css
 * @property Members $chair
 * @property Fees[] $fees
 * @property GamesBoard[] $gamesBoards
 * @property JClubCourts[] $jClubCourts
 * @property JClubUser[] $jClubUsers
 * @property JClubsLocation[] $jClubsLocations
 * @property Members[] $members
 * @property MembershipType[] $membershipTypes
 * @property PlayDates[] $playDates
 * @property Reserves[] $reserves
 */
class Clubs extends ActiveRecord
{

    use \backend\models\base\TraitFileUploads;
    use \backend\models\base\TraitBlameableTimestamp;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clubs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['css_id', 'sport_id', 'season_id', 'session_id', 'type_id', 'coach_stats', 'token_stats', 'play_stats', 'scores', 'match_instigation', 'court_booking', 'money_stats', 'admin_id', 'chair_id', 'location_id', 'is_active', 'payment', 'rota_removal', 'rota_block', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['home_page', 'rules_page', 'members_page', 'rota_page', 'tournament_page', 'subscription_page', 'school_page'], 'string'],
            [['admin_ids'], 'safe'],
            [['name', 'logo', 'logo_orig'], 'string', 'max' => 150],
            [['photo_one', 'photo_two', 'photo_three', 'photo_four'], 'string', 'max' => 100],
            [['css_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClubStyles::className(), 'targetAttribute' => ['css_id' => 'c_css_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(
            self::BTLabels(), [
            'c_id'              => Yii::t('modelattr', 'ID'),
            'season_id'         => Yii::t('modelattr', 'Season'),
            'session_id'        => Yii::t('modelattr', 'Your games average duration'),
            'css_id'            => Yii::t('modelattr', 'Site CSS Style'),
            'type_id'           => Yii::t('modelattr', 'Is non-profit'),
            'sport_id'          => Yii::t('modelattr', 'What Sport'),
            'name'              => Yii::t('modelattr', 'Name'),
            'logo'              => Yii::t('modelattr', 'Logo'),
            'logo_orig'         => Yii::t('modelattr', 'Logo'),
            'home_page'         => Yii::t('modelattr', 'Home Page'),
            'rules_page'        => Yii::t('modelattr', 'Rules Page'),
            'members_page'      => Yii::t('modelattr', 'Members Page'),
            'rota_page'         => Yii::t('modelattr', 'Rota Page'),
            'tournament_page'   => Yii::t('modelattr', 'Tournament Page'),
            'subscription_page' => Yii::t('modelattr', 'Subscription Page'),
            'school_page'       => Yii::t('modelattr', 'School Page'),
            'coach_stats'       => Yii::t('modelattr', 'Record Coaching Sessions'),
            'token_stats'       => Yii::t('modelattr', 'Balls/Tokens Responsibility count'),
            'play_stats'        => Yii::t('modelattr', 'Record Player Games'),
            'scores'            => Yii::t('modelattr', 'Score Uploading Facility'),
            'match_instigation' => Yii::t('modelattr', 'Allow members to schedule games'),
            'court_booking'     => Yii::t('modelattr', 'Do you need to book courts'),
            'money_stats'       => Yii::t('modelattr', 'Money Stats'),
            'admin_ids'         => Yii::t('modelattr', 'Club Admins'),
            'chair_id'          => Yii::t('modelattr', 'Club Chairman'),
            'location_id'       => Yii::t('modelattr', 'Main Location'),
            'is_active'         => Yii::t('modelattr', 'Is Active'),
            'payment'           => Yii::t('modelattr', 'Payment'),
            'rota_removal'      => Yii::t('modelattr', 'Allow members to cancel games'),
            'photo_one'         => Yii::t('modelattr', 'Photo One'),
            'photo_two'         => Yii::t('modelattr', 'Photo Two'),
            'photo_three'       => Yii::t('modelattr', 'Photo Three'),
            'photo_four'        => Yii::t('modelattr', 'Photo Four'),
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
            'audittrail'     => [
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
            'linkerBehavior' => [
                'class'     => LinkerBehavior::class,
                'relations' => [
                    'admin_ids' => 'adminUsers'
                ]
            ]
        ]);
    }

    public function getAjaxfileinputs()
    {
        return [
            'ajaxfileinputLogo' => [
                'Storefield'            => 'logo',
                'Origifield'            => 'logo_orig',
                'optionsmultiple'       => false,
                'optionsaccept'         => 'image/*',
                'allowedfileextensions' => $this->FI_IMAGES,
                'theme'                 => 'explorer',
                'maxuploadfilesize'     => 1024 * 10,
                'resizeimagestosize'    => $this->IMGRESIZE_S_512,
                'resizeimagestoquality' => $this->IMGQUALITY_H_90
            ],
        ];
    }

    public function getTitleSuffix()
    {
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCss()
    {
        return $this->hasOne(ClubStyles::className(), ['c_css_id' => 'css_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChair()
    {
        return $this->hasOne(Members::className(), ['member_id' => 'chair_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFees()
    {
        return $this->hasMany(Fees::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamesBoards()
    {
        return $this->hasMany(GamesBoard::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJClubCourts()
    {
        return $this->hasMany(JClubCourts::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJClubUsers()
    {
        return $this->hasMany(JClubUser::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUsers()
    {
        return $this->hasMany(\common\models\User::class, ['id' => 'user_id'])
                        ->via('jClubUsers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJClubsLocations()
    {
        return $this->hasMany(JClubsLocation::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Members::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembershipTypes()
    {
        return $this->hasMany(MembershipType::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayDates()
    {
        return $this->hasMany(PlayDates::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserves()
    {
        return $this->hasMany(Reserves::className(), ['c_id' => 'c_id']);
    }

    /**
     * {@inheritdoc}
     * @return ClubsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClubsQuery(get_called_class());
    }

}
