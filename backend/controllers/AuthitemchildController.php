<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\rbac\models\Authitemchild;
use common\rbac\models\AuthitemchildSearch;
use common\helpers\Errorhandler;
use common\dictionaries\ContextLetter;

/**
 * Class AuthitemchildController
 * @package backend\controllers
 */
class AuthitemchildController extends Controller
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
                        'controllers' => ['authitemchild'],
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
        $searchModel = new AuthitemchildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('/admin/rbac/authitemchild/index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionView($parent, $child)
    {
        $model = $this->findModel($parent, $child);
        return $this->render('/admin/rbac/authitemchild/view', [
            'model' => $model
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new Authitemchild();

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/authitemchild/create', [
                'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($parent, $child)
    {
        /** @var $model \common\rbac\models\Authitemchild */
        $model = $this->findModel($parent, $child);

        if ($model->load(Yii::$app->request->post())) {
            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('/admin/rbac/authitemchild/update', [
                'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($parent, $child)
    {
        /** @var $model \common\rbac\models\Authitemchild */
        $model = $this->findModel($parent, $child);

        try {
            $model->delete();
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', Errorhandler::getRelatedData($model));
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
