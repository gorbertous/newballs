<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class ClearsessionsController
 *
 * @package console\controllers
 */
class ClearsessionsController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->db->createCommand()->delete('session', 'expire < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 DAY))')->execute();
    }
}