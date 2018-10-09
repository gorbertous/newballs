<?php

namespace frontend\controllers;

use Yii;
use backend\models\Clubs;
use yii\filters\VerbFilter;

class RulesController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        
        $model = Clubs::findOne(Yii::$app->session->get('c_id'));
        return $this->render('index', [
                    'model'  => $model
        ]);
    }

}