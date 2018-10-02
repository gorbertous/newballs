<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Tags;
use backend\models\TagsSearch;
use common\dictionaries\ContextLetter;
use common\helpers\Errorhandler;

/**
 * Class TagsController
 * @package backend\controllers
 */
class TagsController extends Controller
{
    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::NEWS);
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
                        'controllers' => ['tags'],
                        'actions'     => ['create', 'update', 'delete'],
                        'allow'       => true,
                        'roles'       => ['writer']
                    ],
                    [
                        'controllers' => ['tags'],
                        'actions'     => ['index', 'view', 'list'],
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
        $searchModel = new TagsSearch();
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
     * @param $query
     *
     * @return array
     */
    public function actionList($query)
    {
        $models = $this->findAllByName($query);
        $items = [];

        foreach ($models as $model) {
            $items[] = ['Name' => $model->Name];
        }       
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new Tags();
      
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
     * {@inheritdoc}
     */
    public function actionUpdate($id)
    {
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
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
        /** @var $model \backend\models\base\Tags */
        $model = $this->findModel($id);

        try {
            $model->delete();
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', Errorhandler::getRelatedData($model));
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
