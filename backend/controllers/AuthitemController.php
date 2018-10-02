<?php

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\rbac\models\Authitem;
use common\rbac\models\AuthtitemSearch;
use common\helpers\Errorhandler;
use common\dictionaries\ContextLetter;

/**
 * Class AuthitemController
 * @package backend\controllers
 */
class AuthitemController extends Controller
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
                        'controllers' => ['authitem'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['developer']
                    ],
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
        $searchModel = new AuthtitemSearch();
        $searchModel->type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('/admin/rbac/authitem/index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * Lists all Authitem models.
     * @return mixed
     */
    public function actionIndexp()
    {
        $searchModel = new AuthtitemSearch();
        $searchModel->type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('/admin/rbac/authitem/indexp', [
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
        $model = $this->findModel($id);
        $providerAuthAssignment = new ArrayDataProvider([
            'allModels' => $model->authAssignments,
        ]);
        $providerAuthItemChild = new ArrayDataProvider([
            'allModels' => $model->authItemChildren,
        ]);
        return $this->renderNormalorAjax('/admin/rbac/authitem/view', [
            'model'                  => $this->findModel($id),
            'providerAuthAssignment' => $providerAuthAssignment,
            'providerAuthItemChild'  => $providerAuthItemChild,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new Authitem();

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/authitem/create', [
                'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($id)
    {
        /** @var $model \common\rbac\models\Authitem */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/authitem/update', [
                'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
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
