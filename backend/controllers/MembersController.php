<?php

namespace backend\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\{
    Members,
    MembersSearch
};
use common\helpers\Helpers;
use common\helpers\Thumbnails;
use common\dictionaries\ContextLetter;
use common\models\User;

/**
 * Class WorkersController
 * @package backend\controllers
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
                        'actions'     => ['create', 'update', 'delete', 'index', 'view'],
                        'allow'       => true,
                        'roles'       => ['developer']
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
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $searchModel = new MembersSearch();
        $searchModel->is_active = -1;
        $searchModel->is_admin = -1;
        $searchModel->has_paid = -1;
        $searchModel->is_organiser = -1;
        $searchModel->is_visible = -1;
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
    public function actionView($id)
    {
        /** @var $model \backend\models\base\Contacts */
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
        /** @var $model \backend\models\base\Members */
        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->identity->id && !Yii::$app->user->can('writer')) {
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
        /** @var $model \backend\models\base\Members */
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
