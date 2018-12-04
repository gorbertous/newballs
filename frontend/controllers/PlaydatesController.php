<?php

namespace frontend\controllers;

use Yii;
use backend\models\PlayDates;
use frontend\models\PlaydatesSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\Errorhandler as Errorhandler;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;
use yii\helpers\Json;

/**
 * PlaydatesController implements the CRUD actions for PlayDates model.
 */
class PlaydatesController extends Controller
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
                        'controllers' => ['playdates'],
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
     * Lists all PlayDates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlaydatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * Displays a single PlayDates model.
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
     * Displays a scores.
     * @param integer $termin_id
     * @param integer $score_id
     * @return mixed
     */
    public function actionScores($termin_id, $court_id)
    {
        // get players on the court
        $game = \backend\models\GamesBoard::find()
                ->joinWith('member')
                ->where(['games_board.termin_id' => $termin_id])
                ->andWhere(['games_board.court_id' => $court_id])
                ->all();
        // get score
        $score = \backend\models\Scores::find()
                ->where(['termin_id' => $termin_id])
                ->andWhere(['court_id' => $court_id])
                ->one();

        return $this->renderNormalorAjax('score', [
                    'game'  => $game,
                    'score' => $score,
        ]);
    }
    
    /**
     * Displays a scores.
     * @param integer $termin_id
     * @param integer $score_id
     * @return mixed
     */
    public function actionUploadscore($termin_id, $court_id)
    {
         /** @var $model \backend\models\GamesBoard */
        $model = new \backend\models\Scores();
        $model->termin_id = $termin_id;
        $model->court_id = $court_id;

        if ($model->load(Yii::$app->request->post())) {

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);
            Yii::$app->session->setFlash('success', 'You have successfully uploaded the score!');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('uploadscore', [
                        'model' => $model
            ]);
        }
    }

    private function getYearSeason($id = 1)
    {
        $yearseason = \backend\models\Clubs::find()
                        ->select(['season_id AS id', 'season_id AS name'])
                        ->where(['c_id' => $id])->asArray()->all();
        return $yearseason;
    }

    public function actionSubcat()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $c_id = $parents[0];
                $out = self::getYearSeason($c_id);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output' => $out, 'selected' => $out[0]]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Creates a new PlayDates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlayDates();
        $model->c_id = Yii::$app->session->get('c_id');
        $model->season_id = Yii::$app->session->get('club_season');

        if ($model->load(Yii::$app->request->post())) {

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                $flag = $model->save(false);

                if ($flag) {
                    $model->generateGamesBoard($model->termin_id);
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();
                $this->getDbMsg($e->getMessage());
            }
            Yii::$app->session->setFlash('success', 'you have successfully published rota!');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing PlayDates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $model \backend\models\base\PlayDates */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $model->save(false);
            Yii::$app->session->setFlash('info', 'You have successfully updated the play date!');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing PlayDates model.
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
