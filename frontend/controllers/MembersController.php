<?php

namespace frontend\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\MembersSearch;
use backend\models\Members;
use common\helpers\Helpers;
use common\helpers\Thumbnails;
use common\dictionaries\ContextLetter;
use common\models\User;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;

/**
 * Class WorkersController
 * @package frontend\controllers
 */
class MembersController extends Controller
{

    use TraitController;
    use TraitFileUploads;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::MEMBERS);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => array_merge(self::FileUploadRules(), [
                    [
                        'controllers' => ['members'],
                        'actions'     => ['create', 'delete'],
                        'allow'       => true,
                        'roles'       => ['developer']
                    ],
                    [
                        'controllers' => ['members'],
                        'actions'     => [
                            'index', 'view', 'update', 'membership', 'editmember'
                        ],
                        'allow'       => true,
                        'roles'       => ['member']
                    ]
                ])
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }
    /**
     * editable columns processing
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'editmember' => [// identifier for your editable action
                'class'       => EditableColumnAction::className(), // action class name
                'modelClass'  => Members::className(), // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {                 
                    $value = $model->$attribute;                 // your attribute value
                    if ($value) {           // selective validation by attribute
                        return Yii::t('modelattr', 'Yes');       // return formatted value if desired
                    } else {   // selective validation by attribute
                        return Yii::t('modelattr', 'No'); // return formatted value if desired
                    }
                    return '';                                   // empty is same as $value
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';                                  // any custom error after model save
                },
            // 'showModelErrors' => true,                     // show model errors after save
            // 'errorOptions' => ['header' => '']             // error summary HTML options
            // 'postOnly' => true,
            // 'ajaxOnly' => true,
            // 'findModel' => function($id, $action) {},
            // 'checkAccess' => function($action, $model) {}
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $searchModel = new MembersSearch();
        $searchModel->is_active = 1;
        $searchModel->is_admin = -1;
        $searchModel->has_paid = -1;
        $searchModel->is_organiser = -1;
        $searchModel->is_visible = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionMembership()
    {
        $searchModel = new MembersSearch();
        $searchModel->is_active = 1;
        $searchModel->is_admin = -1;
        $searchModel->has_paid = -1;
        $searchModel->is_organiser = -1;
        $searchModel->is_visible = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('membership', [
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
        /** @var $model \backend\models\base\Members */
        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->identity->id && !Yii::$app->user->can('writer')) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }

        return $this->renderNormalorAjax('view', [
                    'model' => $this->findModel($id)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new Members;
        $model->c_id = Yii::$app->session->get('c_id');
        if ($model->load(Yii::$app->request->post())) {

            $model->co_code = (empty($model->co_code)) ? null : $model->co_code;
            $model->nationality = (empty($model->nationality)) ? null : $model->nationality;

            $model->setGender($model->Title);

            $model->AjaxUpdateModel('ajaxfileinputPhoto');

            // validate models data
            $valid = $model->validate();

            if (!$valid) {

                $this->getBaseMsg($model->errors);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                if ($model->save(false)) {
                    $transaction->commit();
                    //special case copy thumbs (25, 90, 160 into profile-thumbs folder
                    $thumbspath = $model->uploadsFolder . 'profile-thumbs/';
                    Helpers::createPath($thumbspath);
                    if (!empty($model->photo)) {
                        $filepath = $model->uniqueFolder . $model->photo;
                        Thumbnails::generateThumbs($filepath, $thumbspath, $model->photo);
                        //update session thumbs only in case logged-in user is updating his/her profile
                        if (Yii::$app->session->get('member_id') == $model->member_id) {
                            Yii::$app->session->set('contact_photo', $model->photo);
                        }
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->getDbMsg($e->getMessage());
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($id)
    {
        /** @var $model \frontend\models\base\Members */
        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->identity->id && !Yii::$app->user->can('team_member')) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->co_code = (empty($model->co_code)) ? null : $model->co_code;
            $model->nationality = (empty($model->nationality)) ? null : $model->nationality;


            $model->setGender($model->title);
//            $model->generateEmail();

            $model->AjaxUpdateModel('ajaxfileinputPhoto');
            // validate models
            $valid = $model->validate();

            if (!$valid) {

                $this->getBaseMsg($model->errors);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                if ($model->save(false)) {
                    $transaction->commit();
                    // special case copy thumbs (25, 90, 160 into profile-thumbs folder
                    $thumbspath = $model->uploadsFolder . 'profile-thumbs/';
                    Yii::$app->session->set('member_has_paid', $model->has_paid);
                    Yii::$app->session->set('member_profile_complete', $model->profileCompletion);
                    Helpers::createPath($thumbspath);
                    if (!empty($model->photo)) {
                        $filepath = $model->uniqueFolder . $model->photo;
                        Thumbnails::generateThumbs($filepath, $thumbspath, $model->photo);

                        //update session thumbs only in case logged-in user is updating his/her profile
                        if (Yii::$app->session->get('member_id') == $model->member_id) {
                            Yii::$app->session->set('contact_photo', $model->photo);
                        }
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->getDbMsg($e->getMessage());
            }

            return $this->redirect(Yii::$app->request->referrer);
        } else {

            return $this->renderNormalorAjax('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
        /** @var $model \frontend\models\base\Members */
        $model = $this->findModel($id);

        $modelbackup = $model;

        try {
            $model->delete();
            //deleting member, we delete user account
            if (isset($user_id)) {
                $user = User::findOne($modelbackup->user_id);
                //$user = User::find()->where(['id' => $ID_User])->one();
                $user->delete();
            }
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', \common\helpers\Errorhandler::getRelatedData($model));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $modelbackup->DeleteStoreFiles('ajaxfileinputPhoto');


        return $this->redirect(Yii::$app->request->referrer);
    }

}
