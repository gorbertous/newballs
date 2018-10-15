<?php

namespace common\models;

use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;
use yii2tech\authlog\AuthLogLoginFormBehavior;

/**
 * Class LoginForm
 *
 * @package common\models
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @var string
     */
//    public $reCaptcha;

    /**
     * @var bool
     */
    private $user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
//            [['reCaptcha', ReCaptchaValidator::class], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'authLog' => [
                'class'        => AuthLogLoginFormBehavior::class,
                'findIdentity' => 'findIdentity',

                'deactivateIdentity' => function ($identity) {
                    return $this->updateAttributes(['status' => User::STATUS_SUSPENDED], $identity->id);
                },

//                'verifyRobotAttribute'           => 'reCaptcha',
//                'verifyRobotRule'                => ['required'],
//                'verifyRobotFailedLoginSequence' => 2
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'   => Yii::t('app', 'Username'),
            'password'   => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember me')
        ];
    }

    /**
     * Finds the user identity for the auth manager
     *
     * @return LoginForm|User
     */
    public function findIdentity()
    {
        return User::findByUsername($this->username);
    }
    
    public function userHasnoclub()
    {
        $hasclub = \backend\models\Members::findOne(['user_id' => $this->getUser()]);
        return empty($hasclub->c_id) ? $hasclub->member_id : null;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username|email and password.
     *
     * @return bool Whether the user is logged in successfully.
     */
    public function login()
    { 
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::findByUsername($this->username);
        }

        return $this->user;
    }

    /**
     * Check if account is activated
     *
     * @return bool
     */
    public function accountNotActivated()
    {
        if ($user = User::userExists('username', $this->password, $this->scenario)) {
            if ($user->status === User::STATUS_NOT_ACTIVE) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if account is suspended
     *
     * @return bool
     */
    public function accountSuspended()
    {
        if ($user = User::findOne(['username' => $this->username])) {
            if ($user->status === User::STATUS_SUSPENDED) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update user attributes
     *
     * @param array $attributes
     * @param int $user_id
     *
     * @return bool
     */
    private function updateAttributes(array $attributes, int $user_id)
    {
        if ($attributes && $user_id) {
            if (User::updateAll($attributes, ['id' => $user_id])) {
                return true;
            }
        }

        return false;
    }
}
        