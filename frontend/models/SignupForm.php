<?php
namespace frontend\models;

use common\models\User;
use backend\models\Members;
use common\rbac\helpers\RbacHelper;
use kartik\password\StrengthValidator;
use yii\base\Model;
use Yii;

/**
 * Model representing Signup Form.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;
    public $club;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match',  'not' => true,
                // we do not want to allow users to pick one of spam/bad usernames 
                'pattern' => '/\b('.Yii::$app->params['user.spamNames'].')\b/i',
                'message' => Yii::t('app', 'The selected username is not possible!')],            
            ['username', 'unique', 'targetClass' => '\common\models\User', 
                'message' => Yii::t('app', 'This username has already been taken.')],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 
                'message' => Yii::t('app', 'This email address has already been taken!')],

            ['password', 'required'],
            [['club'], 'integer'],
            ['club', 'required'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),

            // on default scenario, user status is set to active
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            // status is set to not active on rna (registration needs activation) scenario
            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'rna'],
            // status has to be integer value in the given range. Check User model.
            ['status', 'in', 'range' => [User::STATUS_NOT_ACTIVE, User::STATUS_ACTIVE]]
        ];
    }

    /**
     * Set password rule based on our setting value ( Force Strong Password ).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }    

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
            'club ' => Yii::t('modelattr', 'Club'),
        ];
    }

    /**
     * Signs up the user.
     * If scenario is set to "rna" (registration needs activation), this means
     * that user need to activate his account using email confirmation method.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup()
    {
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;
        
        // if scenario is "rna" ( Registration Needs Activation ) we will generate account activation token
        if ($this->scenario === 'rna') {
            $user->generateAccountActivationToken();
        }
        $user->save(false);
        RbacHelper::assignRole($user->getId());
        
        $member = new Members();
        $member->user_id = $user->getId();
        $member->c_id = $this->club;
        $member->save(false);
        
        $newmember = Members::findOne(['user_id' => $user->getId()]);
        
        // if user is saved, role is assigned and new member record created return user object
        return !empty($newmember) ? $user : null;
    }

    /**
     * Sends email to registered user with account activation link.
     *
     * @param  object $user Registered user.
     * @return bool         Whether the message has been sent successfully.
     */
    public function sendAccountActivationEmail($user)
    {
        return Yii::$app->mailer->compose('accountActivationToken', ['user' => $user])
                                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                                ->setTo($this->email)
                                ->setSubject('Account activation for ' . Yii::$app->name)
                                ->send();
    }
}
