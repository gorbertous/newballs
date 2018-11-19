<?php

namespace frontend\controllers;

use Yii;
use frontend\models\GamesSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;

/**
 * GamesboardController implements the CRUD actions for GamesBoard model.
 */
class YourgamesController extends Controller
{

    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::YOURGAMES);
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
                        'controllers' => ['yourgames'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['reader'],
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
        if (!Yii::$app->session->get('member_is_active')) {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Your account has been temporarily suspended, contact the site administrator'));
            return $this->redirect(['/clubs/stats']);
        }
        $searchModel = new GamesSearch();
        $searchModel->timefilter = 1;
        $searchModel->tokens = -1;
        $searchModel->late = -1;
        $searchModel->seasonfilter = Yii::$app->session->get('club_season');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }
    
   
    /**
     * Updates current game with free slot
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->member_id = 1;
        $model->save();
        

        return $this->redirect(Yii::$app->request->referrer);
    }

}
