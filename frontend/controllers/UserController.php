<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\base\Model;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Members;
use common\dictionaries\ContextLetter;
use common\models\User;
use common\models\UserSearch;
use common\rbac\models\Role;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends Controller
{

    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::USER);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'controllers' => ['user'],
                        'actions'     => ['index', 'create', 'update', 'delete', 'sendresetemail', 'passresetemail', 'updateacc'],
                        'allow'       => true,
                        'roles'       => ['writer'],
                    ],
                    [
                        'controllers' => ['user'],
                        'actions'     => ['updateacc'],
                        'allow'       => true,
                        'roles'       => ['reader'],
                    ],
                    [
                        'controllers' => ['user'],
                        'actions'     => ['view'],
                        'allow'       => true
                    ],
                    [
                    // other rules
                    ],
                ], // rules
            ], // access
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $searchModel->c_id = $this->getSessionClubID();
        $searchModel->status = -2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate empcontract model for saving
            $userId = Yii::$app->request->post('editableKey');
            $model = User::findOne($userId);
            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);
            //$out2 = Json::encode(['output' => '', 'message' => '']);
            // fetch the first entry in posted data (there should only be one entry 
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Empcontracts without any indexes
            // - $post is the converted array for single model validation
            $posted = current($_POST['User']);
            $post = ['User' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
                // can save model or do something before saving model
                $model->save();
                $output = '';
                if (!empty($posted['username'])) {
                    //$userupdated = User::findOne($model->id);
                    $output = $model->username;
                }
                if (isset($posted['status'])) {
                    $output = $model->getStatusName(intval($model->status));
                }
                if (!empty($posted['item_name'])) {
                    $role = Role::findOne(['user_id' => $model->id]);
                    $role->item_name = $posted['item_name'];
                    $role->save(false);
                    $output = $role->item_name;
                }
                //$modelwu = Workunits::findOne($model->ID_Workunit);
                //$Workplace = Members::findOne($model->ID_Workplace);
                $out = Json::encode(['output' => $output, 'message' => '']);
                //$out = Json::encode(['output' => $Workplace->name, 'message' => '']);
            }
            // return ajax json encoded response and exit
            return $out;
        } else {
            return $this->renderNormalorAjax('index', [
                        'searchModel'   => $searchModel,
                        'dataProvider'  => $dataProvider,
                        'context_array' => $this->getSpecificContextArray()
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->id === Yii::$app->user->identity->id || Yii::$app->user->can('writer')) {
            return $this->renderNormalorAjax('view', [
                        'model' => $model
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);
        $role = new Role();

        if ($user->load(Yii::$app->request->post()) &&
                $role->load(Yii::$app->request->post()) && Model::validateMultiple([$user, $role])) {
            $user->setPassword($user->password);
            $user->generateAuthKey();

            if ($user->save()) {
                $role->user_id = $user->getId();
                $role->save();
                $member = Members::find()
                        ->where(['c_id'  => $this->getSessionClubID(),
                            'email' => $user->email])
                        ->One();

                if (!isset($member)) {
                    $member = new Members();
                    $member->c_id = $this->getSessionClubID();
                    $member->user_id = $user->id;

                    $member->save(false);
                }
            }
            //todo create new member
            return $this->redirect('index');
        } else {
            return $this->renderNormalorAjax('create', [
                        'user' => $user,
                        'role' => $role,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($id)
    {
        // get role
        $role = Role::findOne(['user_id' => $id]);


        if (empty($role)) {
            // make shure we always have a default member role
            $role = new Role();
            $role->user_id = $id;
            $role->item_name = 'member';
        }

        // get user details
        $user = $this->findModel($id);
        if ($user->id !== Yii::$app->user->identity->id &&
                !Yii::$app->user->can('writer')) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }

        // only the developer can update everyone`s roles
        // consultant will not be able to update role of developer
        if (!Yii::$app->user->can('developer')) {
            if ($role->item_name === 'developer') {
                return $this->goHome();
            }
        }

        // load user data with role and validate them
        if ($user->load(Yii::$app->request->post()) &&
                $role->load(Yii::$app->request->post()) && Model::validateMultiple([$user, $role])) {
            // only if user entered new password we want to hash and save it
            if ($user->password) {
                $user->setPassword($user->password);
            }

            // if consultant is activating user manually we want to remove account activation token
            if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
                $user->removeAccountActivationToken();
            }

            // update email in contact table if it has changed in the user table
            if (!empty($user->email)) {
                $member = Members::find()->where(['user_id' => $user->id])->one();
                if (isset($member)) {
                    $member->email = $user->email;
                    $member->save(false);
                }
            }
//            dd($user);
            $user->save(false);
            $role->save(false);
            Yii::$app->session->setFlash('error', Yii::t('app', 'Account updated!'));

            return $this->redirect(Yii::$app->request->referrer);
//            return $this->goHome();
        } else {
            return $this->renderNormalorAjax('update', [
                        'user' => $user,
                        'role' => $role
            ]);
        }
    }

    public function actionUpdateacc($id)
    {
        // get role
        $role = Role::findOne(['user_id' => $id]);

        if (empty($role)) {
            // make shure we always have a default member role
            $role = new Role();
            $role->user_id = $id;
            $role->item_name = 'member';
        }

        // get user details
        $user = $this->findModel($id);
        if ($user->id !== Yii::$app->user->identity->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }

        // load user data with role and validate them
        if ($user->load(Yii::$app->request->post()) && Model::validateMultiple([$user, $role])) {

            // only if user entered new password we want to hash and save it
            if ($user->password) {
                $user->setPassword($user->password);
            }

            // if consultant is activating user manually we want to remove account activation token
            if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
                $user->removeAccountActivationToken();
            }

            // update email in contact table if it has changed in the user table
            if (!empty($user->email)) {
                $member = Members::find()->where(['user_id' => $user->id])->one();
                if (isset($member)) {
                    $member->email = $user->email;
                    $member->save(false);
                }
            }

            $user->save(false);
            $role->save(false);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Account updated!'));

//            return $this->redirect(Yii::$app->request->referrer);
            return $this->goHome();
        } else {
            return $this->renderNormalorAjax('update', [
                        'user' => $user,
                        'role' => $role
            ]);
        }
    }

    public function actionPassresetemail(int $id)
    {
        $user = $this->findModel($id);
        //only send this to non active users
        if ($user->status === User::STATUS_ACTIVE) {
            $user->generatePasswordResetToken();
            $user->save(false);

            // try to send account activation email
            if ($this->sendResetemail($user)) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Password reset email has been succesfully sent out to') . ' ' . Html::encode($user->email));
            } else {
                // email could not be sent
                Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to send reset password email'));
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function sendResetemail($user)
    {
        $resetlink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

        return Yii::$app->mailer->compose('@backend/mail/account/password-reset.php', [
                            'user'      => $user,
                            'resetlink' => $resetlink,
                            'logo'      => Yii::getAlias('@backend') . '/mail/logo-mail.png'
                        ])
                        ->setFrom(['noreply@esst.lu' => Yii::$app->name])
                        ->setTo($user->email)
                        ->setSubject(Yii::t('app', 'Password reset'))
                        ->send();
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // delete this user's role from auth_assignment table
        if ($role = Role::find()->where(['user_id' => $id])->one()) {
            $role->delete();
        }

        return $this->redirect(['index']);
    }

}
