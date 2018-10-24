<?php

namespace frontend\controllers;

use Yii;
use backend\models\Log;
use backend\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\dictionaries\ContextLetter;
use yii\filters\AccessControl;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
    use TraitController;
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::LOGS);
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'controllers' => ['log'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['developer'],
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
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }
    
     /**
     * Lists all User Logs
     * @return mixed
     */
    public function actionUsers()
    {
        $sql = "SELECT ua.userId,ua.date, ua.cookieBased, ua.userAgent,  
                        m.member_id, m.c_id, m.user_id, u.status,c.name, 
                        CONCAT(firstname, ' ', lastname) AS fullname
                FROM UserAuthLog ua
                INNER JOIN user u ON ua.userId = u.id
                INNER JOIN members m ON u.id = m.user_id
                INNER JOIN clubs c ON m.c_id = c.c_id
               
                ORDER BY date DESC";

        $lastConnectedUsers = new \yii\data\SqlDataProvider([
            'sql'        => $sql,
            'pagination' => ['pageSize' => 20]
        ]);

        return $this->render('users', [
            'dataProvider' => $lastConnectedUsers,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * Displays a single Log model.
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
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
