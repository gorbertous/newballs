<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
//        dd($this->goHome());
        if (empty(Yii::$app->session->get('member_id'))) {
            return $this->redirect(['site/login']);
        } else {
            return $this->render('index');
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login';
        if (!empty(Yii::$app->session->get('member_id'))) {
            return $this->redirect('/admin/import');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->actionSetupSession();
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * @param int $club_id
     */
    public function actionSetupSession()
    {
//        session_regenerate_id();
        // set up some additional session variables to be used in the header avoiding db queries
        // we don't use Yii::$app->user->identity->id
        $user_id = Yii::$app->user->id;
        $member = \backend\models\Members::find()
                ->where(['user_id' => $user_id])
                ->one();
        $session = Yii::$app->session;

        if (isset($member)) {
            $session->set('user_id', $user_id);
            // save often used variables in the session
            // contact data
            $session->set('member_id', $member->member_id);
            $session->set('member_photo', $member->photo);
            $session->set('member_name', $member->name);

            $session->set('club_languages', Yii::$app->contLang->defaultClubLanguages);
            $session->set('_content_language', '_' . strtoupper(Yii::$app->language));

            $this->redirect('/admin/index');
        } else {
            //fail user cannot proceed - missing data
            Yii::$app->user->logout();
            $session->setFlash('danger', Yii::t('app', 'Your account has not been setup correctly, please contact the site administrator for help'));
            $this->goHome();
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
