<?php

namespace backend\controllers;

use Yii;
use backend\models\Clubs;
use backend\models\ClubsSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\dictionaries\ContextLetter;

/**
 * ClubsController implements the CRUD actions for Clubs model.
 */
class ClubsController extends Controller
{

    use TraitController;
    use TraitFileUploads;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::CLUBS);
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
                        'controllers' => ['clubs'],
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
     * Lists all Clubs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClubsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->renderNormalorAjax('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * Displays a single Clubs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->renderNormalorAjax('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Clubs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clubs();

        if ($model->load(Yii::$app->request->post())) {

            $model->AjaxUpdateModel('ajaxfileinputLogo');

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);
            Yii::$app->session->setFlash('success', 'you have successfully published club data!');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Clubs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $model \backend\models\base\Clubs */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {


            $model->AjaxUpdateModel('ajaxfileinputLogo');

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Clubs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* @var $model \backend\models\base\Clubs */
        $model = $this->findModel($id);

        $modelbackup = $model;

        try {
            $model->delete();
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', \common\helpers\Errorhandler::getRelatedData($model));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $modelbackup->DeleteStoreFiles('ajaxfileinputLogo');

        return $this->redirect(Yii::$app->request->referrer);
    }

}
