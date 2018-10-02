<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Class PasswordResetRequestForm
 *
 * @package backend\models
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter'      => ['status' => User::STATUS_ACTIVE],
                'message'     => 'There is no user with such email.'
            ],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::class, 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email'  => $this->email,
        ]);

        if (!empty($user)) {
            $user->generatePasswordResetToken();

            if ($user->save()) {
                $resetlink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

                Yii::$app->mailer->compose('@backend/mail/account/password-reset.php', [
                    'user'      => $user,
                    'resetlink' => $resetlink,
                    'logo'      => Yii::getAlias('@backend/mail/logo-mail.png')
                ])
                ->setFrom(['noreply@esst.lu' => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject(Yii::t('app', 'Password reset'))
                ->send();

                return true;
            }
        }

        return false;
    }
}
