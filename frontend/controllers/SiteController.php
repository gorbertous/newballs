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
use frontend\models\Contacts;
use frontend\models\Mandants;
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
                return $this->redirect('/frontend/select');
            }
            elseif ($model->accountSuspended()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account was suspended due to many login attempts, please enter in contact with us.'));
            }
            elseif ($model->accountNotActivated()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'You have to activate your account first. Please check your email.'));
            }

            return $this->render('login', [
                'model' => $model
            ]);
        }

        return $this->redirect('/frontend/select');
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
        $this->layout = 'main-client';

        // get mandants list to which this user has access
        $user_id = Yii::$app->user->id;

        if (Yii::$app->user->can('team_member')) {
            // user is eSST team member, he has access to all the mandants
            $mandants = Mandants::find()
                ->orderBy('Mandants.Name')
                ->all();
        } else {

            // user is not eSST team member, maybe he is managing multiple
            // mandants as defined in the jMandantUser table
            $mandants = Mandants::find()
                ->innerJoinWith('jMandantUsers')
                ->where(['ID_User' => $user_id])
                ->orderBy('Mandants.Name')
                ->all();

            if (empty($mandants)) {
                // user is not consultant and is not managing multiple mandants
                // give him access to the mandant defined in the contacts table
                $contact = Contacts::find()
                    ->where(['ID_User' => $user_id])
                    ->one();
                if (!empty($contact)) {
                    $mandants = Mandants::find()
                        ->where(['ID_Mandant' => $contact->ID_Mandant])
                        ->all();
                }
            }

        }

        $mandant_ids = ArrayHelper::getColumn($mandants, 'ID_Mandant');

        if (isset($_POST['mandant'])) {

            $selected_id = (int)$_POST['mandant'];
            // select form has been submitted
            if ($selected_id == -1 || !in_array($selected_id, $mandant_ids)) {
                // user pressed the cancel button
                // render the normal login form
                $this->redirect('/backend/login');
            } else {
                // user selected a particular mandant to work on
                $this->actionSetupSession($selected_id);
            }

        } else {
            // select form needs to be drawn
            if (empty($mandants)) {
                // something is wrong because we should always have at least one mandant 
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please contact the site administrator for help'));
                $this->goHome();
            }

            if (count($mandants) == 1) {
                $this->actionSetupSession($mandants[0]->ID_Mandant);
            } else {
                return $this->render('select', ['model' => $mandants]);
            }
        }

        return '';
    }

    /**
     * @param int $mandant_id
     */
    public function actionSetupSession($mandant_id)
    {
        session_regenerate_id();
        $mandant = Mandants::findOne($mandant_id);

        // set up some additional session variables to be used in the header avoiding db queries
        // we don't use Yii::$app->user->identity->id
        $user_id = Yii::$app->user->id;
        $contact = Contacts::find()
            ->where(['ID_User' => $user_id])
            ->one();
        $session = Yii::$app->session;

        if (isset($contact)) {
            $session->set('user_id', $user_id);
            // save often used variables in the session
            // contact data
            $session->set('contact_id', $contact->ID_Contact);
            $session->set('contact_photo', $contact->Photo);
            $session->set('contact_name', $contact->Name);
            // mandant data
            $session->set('mandant_id', $mandant_id);
            $session->set('mandant_name', $mandant->Name);
            $session->set('ispublic', $mandant->IsPublic);
            // multi employer data
            $session->set('multiemp', $mandant->Multiemployer);
            // use field permissions
            $session->set('fieldpermissions', $mandant->Fieldpermissions);
            // additional language setup
            // list of all available languages for this mandant
            if (empty($mandant->ContLanguages)) {
                // assign default values
                $session->set('club_languages', Yii::$app->contLang->defaultClubLanguages);
            } else {
                $session->set('club_languages', explode('.', $mandant->ContLanguages));
            }
            // set our superfilter
            $session->set('Filter_worker_status', 1);

            //member role case
            if (!Yii::$app->user->can('writer')) {
                $session->set('Filter_workers_ids', [$contact->ID_Contact]);
            } else {
                $session->set('Filter_workers_ids', []);
            }
            $session->set('Filter_workunits_ids', []);
            $session->set('Filter_locations_ids', []);
            $session->set('Filter_employers_ids', []);
            $session->set('Filter_trainings_ids', []);
            $session->set('Filter_all_workers_ids', []);
            $session->set('Filter_object_groups_ids', []);
            $session->set('Filter_object_types_ids', []);
            $session->set('Filter_count', 1);
            if ($session->get('fieldpermissions')) {
                // make sure default permissions are set for at least:
                // - current userrole
                // - all $menu$ items as defined in permission config
                /* @var $perm \common\components\Permissions */
                $perm = Yii::$app->permissions;
                $perm->checkAndSetMenuPermissions();
            }
            $this->redirect('/backend/dashboard/index');
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
     * Displays contact page.
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
                Yii::t('app', 'your account could not be activated, please contact us!'));
        }

        return $this->redirect('login');
    }
}
