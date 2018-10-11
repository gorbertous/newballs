<?php

namespace frontend\controllers;

use Yii;
use backend\models\GamesBoard;
use frontend\models\RotaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\dictionaries\ContextLetter;

/**
 * RotaController implements the CRUD actions for GamesBoard model.
 */
class RotaController extends Controller
{

    use TraitController;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::ROTA);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'controllers' => ['rota'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['member'],
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
        $searchModel = new RotaSearch();
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

    public function actionUpdate($id)
    {
        /** @var $model \backend\models\base\GamesBoard */
        // check for existing name on the court
        $is_on_court = GamesBoard::checkForExisting($id);
        if ($is_on_court) {
            Yii::$app->session->setFlash('warning', 'Your name is already on the rota!');
        } else {
            $model = $this->findModel($id);
            $model->member_id = Yii::$app->user->member->member_id;
            $model->save(false);
            Yii::$app->session->setFlash('success', 'You have successfully added your name on the rota!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * 
     * Export GamesBoard information into PDF format.
     * @param integer $id
     * @return mixed
     */
    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
        ]);

        $pdf = new \kartik\mpdf\Pdf([
            'mode'        => \kartik\mpdf\Pdf::MODE_CORE,
            'format'      => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content'     => $content,
            'cssFile'     => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline'   => '.kv-heading-1{font-size:18px}',
            'options'     => ['title' => \Yii::$app->name],
            'methods'     => [
                'SetHeader' => [\Yii::$app->name],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Finds the GamesBoard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GamesBoard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
//    protected function findModel($id)
//    {
//        if (($model = GamesBoard::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
//        }
//    }
}
