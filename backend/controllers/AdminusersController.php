<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Members;
use common\models\User;
use common\models\AdminusersSearch;
use common\dictionaries\ContextLetter;
use common\rbac\models\Role;

/**
 * Class AdminusersController
 * @package backend\controllers
 */
class AdminusersController extends Controller
{
    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::RBAC);
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
                        'controllers' => ['adminusers'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['developer'],
                    ],
                ],
            ],
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
        $searchModel = new AdminusersSearch();
        $searchModel->status = -2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('/admin/rbac/users/index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionView($id)
    {
        /** @var $model \common\models\User */
        $model = $this->findModel($id);

        if ($model->id === Yii::$app->user->identity->id ||
                Yii::$app->user->can('writer')
        ) {
            return $this->renderNormalorAjax('/user/view', [
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
                        ->where(['c_id' => $this->getSessionClubID(),
                            'email'      => $user->email])
                        ->One();

                if (!isset($member)) {
                    $member = new Members();
                    $member->c_id = $this->getSessionClubID();
                    $member->user_id = $user->id;
                    $member->save(false);
                }
            }
            // todo create new member
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/users/create', [
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

            $user->save(false);
            $role->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/users/update', [
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
                            'logo'      => Yii::getAlias('@backend/mail/logo-mail.png')
                        ])
                        ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name])
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
