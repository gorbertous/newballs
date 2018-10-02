<?php

namespace common\components;

use Yii;
use backend\models\Mandants;
use backend\models\Contacts;

class User extends \yii\web\User
{
    /**
     * Returns the Mandant data
     * depending on which Mandant the user is connected
     *
     * { Example: Yii::$app->user->mandant->ID_Mandant; }
     *
     * @return Mandants|null
     */
    public function getMandant()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->session->get('mandant_id') !== null) {
            return Mandants::findOne(['ID_Mandant' => Yii::$app->session->get('mandant_id')]);
        }
        return null;
    }

    /**
     * Returns the data from the Contacts table
     * based on the current connected user
     *
     * { Example: Yii::$app->user->contact->Firstname; }
     *
     * @return Contacts|null
     */
    public function getContact()
    {
        if (!Yii::$app->user->isGuest) {
            return Contacts::findOne(['ID_User' => Yii::$app->user->id]);
        }
        return null;
    }
}