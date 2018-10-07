<?php

namespace common\models;

use backend\models\Members;
use backend\models\JClubUser;
use backend\models\Clubs;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii2tech\authlog\AuthLogIdentityBehavior;
use kartik\password\StrengthValidator;
use common\rbac\models\Role;

/**
 * Class User Model class extending UserIdentity.
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property integer $status
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $account_activation_token
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Members[] $members
 * @property JClubUser[] $jClubUsers
 *
 * @package common\models
 */
class User extends UserIdentity implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    const STATUS_SUSPENDED = 20;

    /**
     * @var string
     */
    public $password;

    /**
     * @var \common\rbac\models\Role
     */
    public $item_name;

    /**
     * @var int
     */
    public $profileID;

    /**
     * @var string
     */
    public $profilePhoto;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'authLog' => [
                'class'              => AuthLogIdentityBehavior::class,
                'authLogRelation'    => 'authLogs',
                'defaultAuthLogData' => function ($model) {
                    return [
                        'ip'        => Yii::$app->request->getUserIP(),
                        'host'      => @gethostbyaddr(Yii::$app->request->getUserIP()),
                        'url'       => Yii::$app->request->getAbsoluteUrl(),
                        'userAgent' => Yii::$app->request->getUserAgent()
                    ];
                }
            ],
            'timestamp' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthLogs()
    {
        return $this->hasMany(UserAuthLog::class, ['userId' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email', 'status'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match',  'not' => true,
                // we do not want to allow users to pick one of spam/bad usernames 
                'pattern' => '/\b('.Yii::$app->params['user.spamNames'].')\b/i',
                'message' => Yii::t('app', 'It\'s impossible to have that username.')],
            // password field is required on 'create' scenario
            ['password', 'required', 'on' => 'create'],
            // use Kartik presets to determine password strength
            [['password'], StrengthValidator::class, 'preset' => 'normal'],
            ['username', 'unique', 'message' => Yii::t('modelattr', 'This username has already been taken!')],
            ['email', 'unique', 'message' => Yii::t('modelattr', 'This email address has already been taken!')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'       => Yii::t('app', 'Username'),
            'password'       => Yii::t('app', 'Password'),
            'email'          => Yii::t('app', 'Email'),
            'status'         => Yii::t('app', 'Status'),
            'item_name'      => Yii::t('app', 'Role'),
            'fullName'       => Yii::t('app', 'Full Name'),
            'profileID'      => Yii::t('app', 'ID Member'),
            'profilePhoto'   => Yii::t('app', 'Photo'),
            'createUserName' => Yii::t('modelattr', 'Created by'),
            'updateUserName' => Yii::t('modelattr', 'Updated by'),
            'created_at'     => Yii::t('modelattr', 'Created at'),
            'updated_at'     => Yii::t('modelattr', 'Updated at')
        ];
    }

    /**
     * @return mixed
     */
    public function getTitleSuffix()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return !empty($this->member) ? $this->member->firstname . ' ' . $this->member->lastname : '';
    }

    /**
     * Relation with Role model.
     * User has_one Role via Role.user_id -> id
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getMembers()
    {
        return $this->hasMany(Members::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getMember()
    {
        return $this->hasOne(Members::class, ['user_id' => 'id']);
    }

   
    /**
     * get list of clubs belonging to logged in user
     */
    public function getJClubUsers()
    {
        return $this->hasMany(JClubUsers::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Clubs::class, ['c_id' => 'c_id'])
                        ->viaTable('j_Club_User', ['id' => 'user_id']);
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     *
     * @return static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email.
     *
     * @param string $email
     *
     * @return static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token
     *
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status'               => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by account activation token.
     *
     * @param string $token
     *
     * @return static
     */
    public static function findByAccountActivationToken($token)
    {
        return static::findOne([
                    'account_activation_token' => $token,
                    'status'                   => User::STATUS_NOT_ACTIVE,
        ]);
    }

    /**
     * Finds user by access token.
     *
     * @param mixed $token
     * @param null $type
     *
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Checks to see if the given user exists in our database.
     * If LoginForm scenario is set to lwe (login with email), we need to check
     * user's email and password combo, otherwise we check username/password.
     * NOTE: used in LoginForm model.
     *
     * @param string $username
     * @param string $password
     * @param string $scenario
     *
     * @return bool|static
     */
    public static function userExists($username, $password, $scenario)
    {
        if ($user = static::findOne(['username' => $username])) {
            if ($user->validatePassword($password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Returns the user status in nice format.
     *
     * @param null $statusp
     *
     * @return string
     */
    public function getStatusName($statusp = null)
    {
        $status = (empty($statusp)) ? $this->status : $statusp;

        if ($status === self::STATUS_ACTIVE) {
            return Yii::t('modelattr', 'Active');
        } elseif ($status === self::STATUS_NOT_ACTIVE) {
            return Yii::t('modelattr', 'Inactive');
        } elseif ($status === self::STATUS_SUSPENDED) {
            return Yii::t('modelattr', 'Suspended');
        } else {
            return Yii::t('modelattr', 'Deleted');
        }
    }

    /**
     * Returns the array of possible user status values.
     *
     * @return array
     */
    public function getStatusList()
    {
        $statusArray = [
            self::STATUS_ACTIVE     => Yii::t('modelattr', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('modelattr', 'Inactive'),
            self::STATUS_DELETED    => Yii::t('modelattr', 'Deleted'),
            self::STATUS_SUSPENDED  => Yii::t('modelattr', 'Suspended')
        ];

        return $statusArray;
    }

    /**
     * Returns the role name ( item_name )
     *
     * @return string
     */
    public function getRoleName()
    {
        return !empty($this->role->item_name) ? $this->role->item_name : '';
    }


    public static function getUserUsername()
    {
        return Yii::$app->user->identity->username;
    }

    /*     * ****************************
     * Reset Password methos
     * **************************** */

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        /* $timestamp = (int)substr($token, strrpos($token, '_') + 1);
          $expire = Yii::$app->params['user.passwordResetTokenExpire'];
          return $timestamp + $expire >= time(); */

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }

    /*     * ****************************
     * Account activation methods
     * **************************** */

    /**
     * Generates new account activation token.
     */
    public function generateAccountActivationToken()
    {
        $this->account_activation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes account activation token.
     */
    public function removeAccountActivationToken()
    {
        $this->account_activation_token = null;
    }

}
