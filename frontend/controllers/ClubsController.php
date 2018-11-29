<?php

namespace frontend\controllers;

use Yii;
use backend\models\Clubs;
use frontend\models\ClubsSearch;
use frontend\models\MembersSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\UploadForm;
use yii\web\UploadedFile;
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
                        'actions'     => ['create', 'delete'],
                        'allow'       => true,
                        'roles'       => ['developer']
                    ],
                    [
                        'controllers' => ['clubs'],
                        'actions'     => ['index', 'update', 'view', 'upload'],
                        'allow'       => true,
                        'roles'       => ['admin']
                    ],
                    [
                        'controllers' => ['clubs'],
                        'actions'     => ['stats','photos'],
                        'allow'       => true,
                        'roles'       => ['member']
                    ],
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

    public function actionStats()
    {
        $model = $this->findModel(Yii::$app->session->get('c_id'));

        $searchModel = new MembersSearch();
        $searchModel->is_active = 1;
        $searchModel->is_admin = -1;
        $searchModel->is_visible = 1;
        //$searchModel->has_paid = 1;
        $searchModel->is_organiser = -1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('stats', [
                    'model'        => $model,
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        $model->albumyear = date('Y');

        if (Yii::$app->request->isPost) {

            $album_year = $_POST['UploadForm']['albumyear'];

            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload($album_year)) {
                // file is uploaded successfully
                Yii::$app->getSession()->setFlash('success', 'Pictures uploaded successfully!');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $viewGallery = $this->renderPartial('_gallery');

        return $this->render('upload', ['model' => $model, 'gallery' => $viewGallery]);
    }

    /**
     * Lists all Clubs models.
     * @return mixed
     */
    public function actionPhotos()
    {
        $viewGallery = $this->renderPartial('_gallery');
        return $this->render('photos', ['gallery' => $viewGallery]);

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
        /** @var $model \frontend\models\base\Clubs */
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
        /* @var $model \frontend\models\base\Clubs */
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
