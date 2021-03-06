<?php

namespace backend\models;

use Yii;
use voskobovich\linker\LinkerBehavior;
use asinfotrack\yii2\audittrail\behaviors\AuditTrailBehavior;
use common\models\User;
use backend\models\Countries;
use yii\db\Expression;

/**
 * This is the model class for table "members".
 *
 * @property int $member_id
 * @property int $user_id
 * @property int $c_id
 * @property int $mem_type_id
 * @property int $grade_id
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property int $gender
 * @property string $email
 * @property string $photo
 * @property string $orig_photo
 * @property string $phone
 * @property string $phone_office
 * @property string $phone_mobile
 * @property string $address
 * @property string $zip
 * @property string $city
 * @property string $co_code
 * @property int $country_id
 * @property string $nationality
 * @property string $dob
 * @property int $token_stats
 * @property int $scheduled_stats
 * @property int $played_stats
 * @property int $cancelled_stats
 * @property int $coaching_stats
 * @property int $noshow_stats
 * @property int $is_admin
 * @property int $is_organiser
 * @property int $is_active
 * @property int $has_paid
 * @property int $is_visible
 * @property int $ban_scoreupload
 * @property int $coaching
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $username
 * @property string $password
 *
 * @property Clubs[] $clubs
 * @property GamesBoard[] $gamesBoards
 * @property JClubMemRoles[] $jClubMemRoles
 * @property JCourtBooked[] $jCourtBookeds
 * @property User $user
 * @property Clubs $c
 * @property MembershipType $memType
 * @property Reserves[] $reserves
 */
class Members extends \yii\db\ActiveRecord
{

    use \backend\models\base\TraitFileUploads;
    use \backend\models\base\TraitBlameableTimestamp;

    public $token_stats;
    public $scheduled_stats;
    public $played_stats;
    public $cancelled_stats;
    public $coaching_stats;
    public $noshow_stats;
    public $club_role;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'c_id', 'mem_type_id', 'grade_id', 'gender', 'token_stats', 'scheduled_stats', 'played_stats', 'cancelled_stats', 'coaching_stats', 'noshow_stats', 'is_admin', 'is_organiser', 'is_active', 'has_paid', 'is_visible', 'ban_scoreupload', 'coaching', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
//            [['user_id', 'c_id', 'mem_type_id', 'grade_id', 'gender', 'is_admin', 'is_organiser', 'is_active', 'has_paid', 'is_visible', 'ban_scoreupload', 'coaching', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['c_id'], 'required'],
            [['dob', 'clubroles_ids'], 'safe'],
            [['title', 'zip'], 'string', 'max' => 20],
            [['firstname', 'lastname', 'email', 'city'], 'string', 'max' => 50],
            [['photo', 'orig_photo'], 'string', 'max' => 150],
            [['phone', 'phone_office', 'phone_mobile'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['co_code', 'nationality'], 'string', 'max' => 2],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['mem_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['mem_type_id' => 'mem_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id'       => Yii::t('modelattr', 'Member ID'),
            'user_id'         => Yii::t('modelattr', 'User ID'),
            'c_id'            => Yii::t('modelattr', 'Club'),
            'mem_type_id'     => Yii::t('modelattr', 'Membership Type'),
            'grade_id'        => Yii::t('modelattr', 'Grade'),
            'title'           => Yii::t('app', 'Title'),
            'firstname'       => Yii::t('modelattr', 'First Name'),
            'lastname'        => Yii::t('modelattr', 'Last Name'),
            'name'            => Yii::t('modelattr', 'Name'),
            'gender'          => Yii::t('modelattr', 'Gender'),
            'email'           => Yii::t('modelattr', 'Email'),
            'photo'           => Yii::t('modelattr', 'Photo'),
            'orig_photo'      => Yii::t('modelattr', 'Orig Photo'),
            'phone'           => Yii::t('modelattr', 'Phone'),
            'phone_office'    => Yii::t('modelattr', 'Phone Office'),
            'phone_mobile'    => Yii::t('modelattr', 'Phone Mobile'),
            'address'         => Yii::t('modelattr', 'Address'),
            'zip'             => Yii::t('modelattr', 'Zip'),
            'city'            => Yii::t('modelattr', 'City'),
            'co_code'         => Yii::t('modelattr', 'Country'),
            'nationality'     => Yii::t('modelattr', 'Nationality'),
            'dob'             => Yii::t('modelattr', 'Dob'),
            'is_admin'        => Yii::t('modelattr', 'Admin'),
            'is_organiser'    => Yii::t('modelattr', 'Organiser'),
            'is_active'       => Yii::t('modelattr', 'Active'),
            'has_paid'        => Yii::t('modelattr', 'Has Paid'),
            'is_visible'      => Yii::t('modelattr', 'Visible'),
            'ban_scoreupload' => Yii::t('modelattr', 'Ban Score Upload'),
            'coaching'        => Yii::t('modelattr', 'Interested in coaching lessons'),
            'token_stats'     => Yii::t('modelattr', 'Tokens') . ' / ' . Yii::t('modelattr', 'Balls Count'),
            'scheduled_stats' => Yii::t('modelattr', 'Scheduled'),
            'played_stats'    => Yii::t('modelattr', 'Played'),
            'cancelled_stats' => Yii::t('modelattr', 'Cancelled'),
            'coaching_stats'  => Yii::t('modelattr', 'Coached'),
            'noshow_stats'    => Yii::t('modelattr', 'No Show') . ' / ' . Yii::t('modelattr', 'Non Scheduled Play'),
            'clubroles_ids'   => Yii::t('modelattr', 'Club Roles'),
            'created_by'      => Yii::t('modelattr', 'Created By'),
            'updated_by'      => Yii::t('modelattr', 'Updated By'),
            'created_at'      => Yii::t('modelattr', 'Created At'),
            'updated_at'      => Yii::t('modelattr', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(self::BTbehaviors(), [
            'audittrail'     => [
                'class'             => AuditTrailBehavior::className(),
                // some of the optional configurations
                'ignoredAttributes' => ['created_at', 'updated_at', 'created_by', 'updated_by'],
                'consoleUserId'     => 1,
                'attributeOutput'   => [
//                    'ID_Company'       => function ($value) {
//                        $model = Company::findOne($value);
//                        return isset($model) ? $model->Name : null;
//                    },
//                    'contacttypes_ids' => function ($value) {
//                        $model = Contacttypes::findAll(explode(', ', $value));
//                        return  isset($model) ? join(', ', ArrayHelper::getColumn($model, 'descriptionFB')) : null;
//                    },
                    'last_checked' => 'datetime'
                ]
            ],
            // this will be removed if model is readonly
            'linkerBehavior' => [
                'class'     => LinkerBehavior::class,
                'relations' => [
                    'clubroles_ids' => 'memberRoles',
                ]
            ]
        ]);
    }

    public function getAjaxfileinputs()
    {
        return [
            'ajaxfileinputPhoto' => [
                'Storefield'            => 'photo',
                'Origifield'            => 'orig_photo',
                'optionsmultiple'       => false,
                'optionsaccept'         => 'image/*',
                'allowedfileextensions' => $this->FI_IMAGES,
                'maxuploadfilesize'     => 1024 * 1024 * 10,
                'resizeimagestosize'    => $this->IMGRESIZE_S_512,
                'resizeimagestoquality' => $this->IMGQUALITY_H_90
            ],
        ];
    }

    /**
     * Getter for short name
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Getter for short name
     *
     * @return string
     */
    public function getProfileCompletion()
    {
        $fname = empty($this->firstname) ? 30 : 0;
        $lname = empty($this->lastname) ? 30 : 0;
        $grade = empty($this->grade_id) ? 20 : 0;
        $memtype = empty($this->mem_type_id) ? 20 : 0;
        return 100 - $fname - $lname - $grade - $memtype;
    }

    /**
     * Getter for member since
     *
     * @return string
     */
    public function getMemberSince()
    {
        if (!empty($this->created_at)) {
            $converttimestamp = date('M Y', $this->created_at);
            return $converttimestamp;
        } else {
            return '';
        }
    }

    /**
     * mailing list - active members
     *
     * @return array list
     */
    public function getMailingList(array $andWhere = [])
    {
        $membership = Members::find()
                ->select('user.email')
                ->joinWith('user')
                ->where(['c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['is_active' => true])
                ->andWhere(['is_visible' => true])
                ->all();


        if (!empty($andWhere)) {
            $membership->andWhere($andWhere);
        }
        return join(',', \yii\helpers\ArrayHelper::getColumn($membership, 'email'));
    }

    /**
     * Getter for games/tokens stats
     *
     * @return string
     */
    public function getMemberStats(array $andWhere = [])
    {
        $stats_count = GamesBoard::find()
                ->joinWith('termin')
                ->where(['season_id' => $this->club->season_id])
                ->andWhere(['games_board.member_id' => $this->member_id]);


        if (!empty($andWhere)) {
            $stats_count->andWhere($andWhere);
        }
        return $stats_count->count();
    }

    /**
     * Getter for pending games count
     *
     * @return string
     */
    public function getPendingGamesNo()
    {
        $pending_count = GamesBoard::find()
                ->joinWith('termin')
                ->where(['games_board.c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['games_board.member_id' => Yii::$app->session->get('member_id')])
                ->andWhere(['>', 'play_dates.termin_date', new Expression('NOW()')])
                ->count();

        return $pending_count;
    }

//    public function getCoachingCourts()
//    {
//        $courts_with_coach = GamesBoard::find()
//                ->joinWith('termin')
//                ->joinWith('member')
//                ->where(['season_id' => 12])//$this->club->season_id
//                ->andWhere(['members.mem_type_id' => 5])
//                ->all();
//
////        dd($courts_with_coach->count());
//        return $courts_with_coach;
//    }

    public function getCoachingStats($status_id = 1)
    {
        //get all the coaching games
        $subQuery = GamesBoard::find()
                ->joinWith('termin')
                ->joinWith('member')
                ->where(['season_id' => $this->club->season_id])//$this->club->season_id
                ->andWhere(['members.mem_type_id' => 5])
                ->all();

        //extract the list of courts and date ids
        $courts_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'court_id');
        $termins_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'termin_id');

        $coaching_stats = GamesBoard::find()
                ->where(['in', 'termin_id', $termins_list])
                ->andWhere(['member_id' => $this->member_id])
                ->andWhere(['status_id' => $status_id])
                ->andWhere(['in', 'court_id', $courts_list]);

        return $coaching_stats->count();
    }

    /**
     * Getter for formatted full name
     *
     * @return string
     */
    public function getFullName()
    {
        $fullname = Yii::t('modelattr', $this->title) . ' ' . $this->firstname . ' ' . $this->lastname;
        return $fullname;
    }

    public function getTitleSuffix()
    {
        return $this->name;
    }

    /**
     * @param string|null $title
     */
    public function setGender($title = null)
    {
        if ($title === null) {
            $title = $this->title;
        }

        if ($title === 'Mr') {
            $this->gender = 1;
        } elseif ($title === 'Ms') {
            $this->gender = 2;
        }
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
     * @return mixed
     */
    public function getNationalitytranslated(): string
    {
        if (isset($this->nation)) {
            return $this->nation->textFB;
        }
        return '';
    }

    /**
     * Getter for full address
     *
     * @return string
     */
    public function getFullAddress()
    {
        return '<address>' .
                ($this->address ?? '') . '<br>' .
                ($this->zip ?? '') . ' ' . ($this->city ?? '') . '<br>' .
                $this->getCountrytranslated() .
                '</address>';
    }

    public function getGravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = true, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Getter for formatted full address
     *
     * @return string
     */
    public function getShortAddress()
    {
        return $this->address . '<br>' .
                $this->co_code . '-' . $this->zip . ' ' . $this->city;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['code' => 'co_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNation()
    {
        return $this->hasOne(Countries::class, ['code' => 'nationality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamesBoards()
    {
        return $this->hasMany(GamesBoard::className(), ['member_id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJClubMemRoles()
    {
        return $this->hasMany(JClubMemRoles::className(), ['member_id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberRoles()
    {
        return $this->hasMany(ClubRoles::class, ['id' => 'role_id'])
                        ->via('jClubMemRoles');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoaching()
    {
        return $this->hasMany(GamesBoard::className(), ['member_id' => 'member_id'])
                        ->where(['season_id' => 12])//$this->club->season_id
                        ->andWhere(['members.mem_type_id' => 5])
                        ->orderBy('id');
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
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemType()
    {
        return $this->hasOne(MembershipType::className(), ['mem_type_id' => 'mem_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return MembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MembersQuery(get_called_class());
    }

}
