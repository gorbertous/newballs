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
use common\models\User;
use backend\models\Members;
use backend\models\Clubs;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
//use yii\web\Cookie;

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
                'only'  => ['logout', 'signup', 'contact'],
                'rules' => [
                    [
                        'actions' => ['signup', 'contact'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
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
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * default public page
     *
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'main-client';

            $session = Yii::$app->session;
            if (!empty($id)) {
                $session->set('c_id_public', $id);
            } elseif ($session->get('c_id_public') !== null && empty($id)) {
                $id = $session->get('c_id_public');
            }else{
                $id = 1;
            }
//            $cookies = Yii::$app->response->cookies;
            //check if cookie exists
//            if (empty($id)) {
//                //read value form client cookie
//                $cookie_club_value = $cookies->getValue('club_identifier_cookie');
//                dd($cookie_club_value);
//                if (isset($cookie_club_value) && $cookie_club_value > 1) {
//                    $id = (int) $cookie_club_value;
//                }
////            } elseif (empty($id) && !$cookies->has('club_identifier_cookie')) {
////                //set default id balls-tennis
////                $id = 1;
//            } elseif (!empty($id) && $id > 1) {
//                if ($cookies->has('club_identifier_cookie')) {
//                    //remove old cookie
//                    $cookies->remove('club_identifier_cookie');
//                    unset($cookies['club_identifier_cookie']);
//                    $cookie_club_id = new Cookie([
//                        'name'   => 'club_identifier_cookie',
//                        'value'  => (string) $id,
//                        'domain' => '.balls.test',
//                        //                'domain' => '.balls-tennis.com',
//                        'expire' => time() + 86400 * 365,
//                    ]);
//                    $cookies->add($cookie_club_id);
//                    $id = (int) $cookies->getValue('club_identifier_cookie');
//                }
//            }else{
//                $id = 1;
//            }
            //dd($id);
            $model = Clubs::findOne($id);

            return $this->render('index', [
                        'model' => $model
            ]);
        }
        return $this->redirect('/rota');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'main-client';
            $session = Yii::$app->session;
            if ($session->get('c_id_public') !== null) {
                $id = $session->get('c_id_public');
            } else {
                $id = 1;
            }
            $model = Clubs::findOne($id);

            return $this->render('about', [
                        'model' => $model
            ]);
        }
        return $this->redirect('/rota');
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
                if (($model->userHasnoclub()) !== null) {
//                    dd($model->userHasnoclub());
                    Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has not been set up correctly, contact the site administrator'));
                    return $this->redirect(['/logout']);
                } else {
                    return $this->redirect('/select');
                }
            } elseif ($model->accountSuspended()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has been suspended due to too many login attempts'));
            } elseif ($model->accountNotActivated()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'You have to activate your account first, please check your email.'));
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
            // user is super admin, with access to all the clubs
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

            $selected_id = (int) $_POST['club'];
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
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please contact the site administrator for help'));
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
            $session->set('member_has_paid', $member->has_paid);
            $session->set('member_is_active', $member->is_active);
            $session->set('member_since', $member->memberSince);
            // club data
            $session->set('c_id', $c_id);
            $session->set('club_name', $club->name);
            $session->set('club_season', $club->season_id);
            $session->set('club_logo', $club->logo);


            $session->set('club_languages', Yii::$app->contLang->defaultClubLanguages);
            $session->set('_content_language', '_' . strtoupper(Yii::$app->language));

            $this->redirect('/rota/index');
        } else {
            //fail user cannot proceed - missing data
            Yii::$app->user->logout();
            $session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please contact the site administrator for help'));
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
        if (Yii::$app->user->isGuest) {

            $this->layout = 'main-client';

            $model = new ContactForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                dd($model);

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
        return $this->redirect('/rota');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'main-client';
            // get setting value for 'Registration Needs Activation'
            $rna = Yii::$app->params['rna'];

            // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
            $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

            // if validation didn't pass, reload the form to show errors
            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                return $this->render('signup', ['model' => $model]);
            }

            // try to save user data in database, if successful, the user object will be returned
            $user = $model->signup();

            if (!$user) {
                // display error message to user
                Yii::$app->session->setFlash('error', Yii::t('app', 'We couldn\'t sign you up, please contact us.'));
                return $this->refresh();
            }

            // user is saved but activation is needed, use signupWithActivation()
            if ($user->status === User::STATUS_NOT_ACTIVE) {
                $this->signupWithActivation($model, $user);
                return $this->refresh();
            }

            // now we will try to log user in
            // if login fails we will display error message, else just redirect to home page

            if (!Yii::$app->user->login($user)) {
                // display error message to user
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Please try to log in.'));

                // log this error, so we can debug possible problem easier.
                Yii::error('Login after sign up failed! User ' . Html::encode($user->username) . ' could not log in.');
            }

            return $this->goHome();
        }
        return $this->redirect('/rota');
    }

    /**
     * Tries to send account activation email.
     *
     * @param $model
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // sending email has failed
        if (!$model->sendAccountActivationEmail($user)) {
            // display error message to user
            Yii::$app->session->setFlash('error', Yii::t('app', 'We couldn\'t send you account activation email, please contact us.'));

            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed! User ' . Html::encode($user->username) . ' could not sign up. 
                Possible causes: verification email could not be sent.');
        }

        // everything is OK
        Yii::$app->session->setFlash('success', Yii::t('app', 'Hello') . ' ' . Html::encode($user->username) . '. ' .
                Yii::t('app', 'To be able to log in, you need to confirm your registration. 
                Please check your email, we have sent you a message.'));
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
                    Yii::t('app', 'your account could not be activated, please contact us, using the site contact form!'));
        }

        return $this->redirect('login');
    }

}
