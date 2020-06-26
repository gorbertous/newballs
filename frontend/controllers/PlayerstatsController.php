<?php

namespace frontend\controllers;

use Yii;
use backend\models\PlayerStats;
use backend\models\PlayerStatsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;


/**
 * PlayerStatsController implements the CRUD actions for PlayerStats model.
 */
class PlayerstatsController extends Controller
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
                        'controllers' => ['playerstats'],
                        'actions'     => [
                            'update', 'create', 'delete', 'generateplayerstats','generatealltimeplayerstats','updateplayerstats','updatealltimeplayerstats','generatecoachingplayerstats'
                        ],
                        'allow'       => true,
                        'roles'       => ['developer']
                    ],
                    [
                        'controllers' => ['playerstats'],
                        'actions'     => [
                            'index', 'view'
                        ],
                        'allow'       => true,
                        'roles'       => ['member']
                    ]
                ],
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
     * Lists all PlayerStats models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlayerStatsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlayerStats model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }
    
    public function actionGeneratecoachingplayerstats()
    {
        if (!empty(Yii::$app->request->post('generatecoachingstats'))) {

            if (PlayerStats::generateCoachingPlayerStats()) {
                Yii::$app->session->setFlash('success', 'Stats Updated!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionGeneratealltimeplayerstats()
    {
        if (!empty(Yii::$app->request->post('generatealltimestats'))) {

            if (PlayerStats::generateAllTimePlayerStats()) {
                Yii::$app->session->setFlash('success', 'Stats Updated!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionUpdatealltimeplayerstats()
    {
        if (!empty(Yii::$app->request->post('updatealltimestats'))) {

            if (PlayerStats::updateAllTimePlayerStats()) {
                Yii::$app->session->setFlash('success', 'Stats Updated!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGenerateplayerstats()
    {
        if (!empty(Yii::$app->request->post('generatestats'))) {

            if (PlayerStats::generatePlayerStats()) {
                Yii::$app->session->setFlash('success', 'Stats Updated!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionUpdateplayerstats()
    {
        if (!empty(Yii::$app->request->post('updatestats'))) {

            if (PlayerStats::updatePlayerStats()) {
                Yii::$app->session->setFlash('success', 'Stats Updated!');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Error something went wrong here!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new PlayerStats model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlayerStats();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlayerStats model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlayerStats model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}
