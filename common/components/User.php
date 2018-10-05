<?php

namespace common\components;

use Yii;
use backend\models\Clubs;
use backend\models\Members;

class User extends \yii\web\User
{
    /**
     * Returns the Club data
     * depending on which club the user is connected
     *
     * { Example: Yii::$app->user->club->club_id; }
     *
     * @return Clubs|null
     */
    public function getClub()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->session->get('club_id') !== null) {
            return Clubs::findOne(['club_id' => Yii::$app->session->get('club_id')]);
        }
        return null;
    }

    /**
     * Returns the data from the Members table
     * based on the current connected user
     *
     * { Example: Yii::$app->user->member->firstname; }
     *
     * @return Members|null
     */
    public function getMember()
    {
        if (!Yii::$app->user->isGuest) {
            return Members::findOne(['user_id' => Yii::$app->user->id]);
        }
        return null;
    }
}