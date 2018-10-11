<?php

namespace frontend\controllers;

use Yii;
use backend\models\Fees;
use frontend\models\FeesSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\Errorhandler as Errorhandler;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;

/**
 * FeesController implements the CRUD actions for Fees model.
 */
class FeesController extends Controller
{
    use TraitController;
    
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
                'rules' => [
                   [
                        'controllers' => ['fees'],
                        'actions'     => [
                            'index', 'view', 'update', 'create', 'delete'
                        ],
                        'allow'       => true,
                        'roles'       => ['admin']
                    ]
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
     * Lists all Fees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * Displays a single Fees model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderNormalorAjax('view', [
                    'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Fees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
     public function actionCreate()
    {
        $model = new Fees();
        $model->c_id = Yii::$app->session->get('c_id');
        
        if ($model->load(Yii::$app->request->post())) {

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('create', [
                        'model' => $model
            ]);
        }
    }
    /**
     * Updates an existing Fees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $model \backend\models\base\Fees */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {


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
     * Deletes an existing Fees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $modelbackup = $model;

        try {
            $model->delete();
        } catch (\Exception $ex) {
            \Yii::$app->getSession()->setFlash('error', Errorhandler::getRelatedData($model));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
