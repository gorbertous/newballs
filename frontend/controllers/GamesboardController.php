<?php

namespace frontend\controllers;

use Yii;
use backend\models\GamesBoard;
use frontend\models\RotaSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\Errorhandler as Errorhandler;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;
use common\dictionaries\OutcomeStatus;

/**
 * GamesboardController implements the CRUD actions for GamesBoard model.
 */
class GamesboardController extends Controller
{

    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::PLAYDATES);
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
                        'controllers' => ['gamesboard'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['admin'],
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
     * Lists all GamesBoard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RotaSearch();
        $searchModel->timefilter = 1;
        $searchModel->tokens = -1;
        $searchModel->late = -1;
        $searchModel->seasonfilter = Yii::$app->session->get('club_season');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 64;

        return $this->render('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }
  
    public function actionBulk()
    {
        $action = Yii::$app->request->post('status_id');
        $selection = (array) Yii::$app->request->post('selection'); //typecasting
        foreach ($selection as $id) {
            $games = GamesBoard::findOne((int) $id); //make a typecasting
            //do your stuff
            $games->status_id = $action;
            $games->save();
        }
        Yii::$app->session->setFlash('success', 'Game statuses updated to ' . OutcomeStatus::get($action));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSendemailreminder()
    {
        if (!empty(Yii::$app->request->post('sendemail'))) {

            if (GamesBoard::sendMailReminders()) {
                Yii::$app->session->setFlash('success', 'Email reminders were sent out!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single GamesBoard model.
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
     * Creates a new GamesBoard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GamesBoard();
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
     * Updates an existing GamesBoard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $model \backend\models\base\GamesBoard */
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
     * Deletes an existing GamesBoard model.
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
