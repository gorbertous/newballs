<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Texts;
use backend\models\TextsSearch;
use common\helpers\Errorhandler;
use common\dictionaries\ContextLetter;

/**
 * Class TextsController
 * @package backend\controllers
 */
class TextsController extends Controller
{

    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::TEXTS);
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
                        'controllers' => ['texts'],
                        'actions'     => ['create', 'update', 'delete'],
                        'allow'       => true,
                        'roles'       => ['writer']
                    ],
                    [
                        'controllers' => ['texts'],
                        'actions'     => ['view', 'index', 'print'],
                        'allow'       => true,
                        'roles'       => ['reader']
                    ]
                ]
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
        $searchModel = new TextsSearch();

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
        return $this->renderNormalorAjax('view', [
                    'model' => $this->findModel($id)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new Texts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderNormalorAjax('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderNormalorAjax('update', [
                    'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
        /** @var $model \backend\models\base\Texts */
        $model = $this->findModel($id);

        $modelbackup = $model;

        try {
            $model->delete();
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', Errorhandler::getRelatedData($model));
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
