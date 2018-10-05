<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\LoginForm;
use backend\models\Members;
use backend\models\Clubs;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main-client';
        return $this->render('index');
    }

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'main-client';

            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect('/select');
            }
            elseif ($model->accountSuspended()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account was suspended due to many login attempts, please enter in member with us.'));
            }
            elseif ($model->accountNotActivated()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'You have to activate your account first. Please check your email.'));
            }

            return $this->render('login', [
                'model' => $model
            ]);
        }

        return $this->redirect('/select');
    }
    
     /**
     * @return string|\yii\web\Response
     */
    public function actionSelect()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // default layout is the public web site
        $this->layout = 'select-layout';

        // get mandants list to which this user has access
        $user_id = Yii::$app->user->id;

        if (Yii::$app->user->can('team_member')) {
            // user is club team member, he has access to all the mandants
            $clubs = Clubs::find()
                ->orderBy('name')
                ->all();
        } else {

            // user is not club team member, maybe he is managing multiple
            // clubs as defined in the jClubUsers table
            $clubs = Clubs::find()
                ->innerJoinWith('jClubUsers')
                ->where(['user_id' => $user_id])
                ->orderBy('clubs.name')
                ->all();

            if (empty($clubs)) {
                // user is not consultant and is not managing multiple mandants
                // give him access to the club defined in the members table
                $member = Members::find()
                    ->where(['user_id' => $user_id])
                    ->one();
                if (!empty($member)) {
                    $clubs = Clubs::find()
                        ->where(['c_id' => $member->c_id])
                        ->all();
                }
            }

        }

        $club_ids = ArrayHelper::getColumn($clubs, 'c_id');

        if (isset($_POST['club'])) {

            $selected_id = (int)$_POST['club'];
            // select form has been submitted
            if ($selected_id == -1 || !in_array($selected_id, $club_ids)) {
                // user pressed the cancel button
                // render the normal login form
                $this->redirect('/login');
            } else {
                // user selected a particular club to work on
                $this->actionSetupSession($selected_id);
            }

        } else {
            // select form needs to be drawn
            if (empty($clubs)) {
                // something is wrong because we should always have at least one club 
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please member the site administrator for help'));
                $this->goHome();
            }

            if (count($clubs) == 1) {
                $this->actionSetupSession($clubs[0]->c_id);
            } else {
                return $this->render('select', ['model' => $clubs]);
            }
        }

        return '';
    }

    /**
     * @param int $c_id
     */
    public function actionSetupSession($c_id)
    {
        session_regenerate_id();
        $club = Clubs::findOne($c_id);

        // set up some additional session variables to be used in the header avoiding db queries
        // we don't use Yii::$app->user->identity->id
        $user_id = Yii::$app->user->id;
        $member = Members::find()
            ->where(['user_id' => $user_id])
            ->one();
        $session = Yii::$app->session;

        if (isset($member)) {
            $session->set('user_id', $user_id);
            // save often used variables in the session
            // member data
            $session->set('member_id', $member->member_id);
            $session->set('member_photo', $member->photo);
            $session->set('member_name', $member->name);
            // club data
            $session->set('c_id', $c_id);
            $session->set('club_name', $club->name);
            
            
            $session->set('club_languages', Yii::$app->contLang->defaultClubLanguages);
           
            $this->redirect('/gamesboard/index');
        } else {
            //fail user cannot proceed - missing data
            Yii::$app->user->logout();
            $session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please member the site administrator for help'));
            $this->goHome();
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays member page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $this->layout = 'main-client';
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $this->layout = 'main-client';
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'main-client';
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'main-client';
        
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'main-client';
        
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    /**
     * Activates the user account so he can log in into system.
     *
     * @param string $token
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        $this->layout = 'main-client';

        try {
            $user = new AccountActivation($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Success! You can now log in.') . ' ' .
                Yii::t('app', 'Thank you') . ' ' . Html::encode($user->username) . ' ' .
                Yii::t('app', 'for joining us!'));
        } else {
            Yii::$app->session->setFlash('error', Html::encode($user->username) .
                Yii::t('app', 'your account could not be activated, please member us!'));
        }

        return $this->redirect('login');
    }
}
