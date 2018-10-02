<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\web\MethodNotAllowedHttpException;
use backend\models\Message;
use backend\models\Sourcemessage;
use yii\db\Expression;
//use common\helpers\Language as Lx;

// the client server does a query in the user table username='api'
// the auth_key from this user is sent to the master server
// this one checks if a user with such a auth_key exists, event if he has another name
// if yes -> access is granted
// ->> this means that client servers should only have one 'api' user
//     each client server with a different auth_key

class MessageController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];
        return $behaviors;
    }
    
    public function actionSync() {
        // decode the data
        $masterts_messages = json_decode(Yii::$app->request->post('masterts_messages'), true);
        $masterts_source_messages = json_decode(Yii::$app->request->post('masterts_source_messages'), true);
        // list of messages which were updated on the master
        if ($masterts_messages == 'null') {
            $masterts_messages = '99999999999999';
        }
        if ($masterts_source_messages == 'null') {
            $masterts_source_messages = '99999999999999';
        }
        $master_messages = Sourcemessage::find()
            ->select(['id' => 'source_message.id', 
                'cat' => 'category', 
                'mess' => 'message',
                'lang' => 'language',
                'tran' => 'translation',
                'smts' => 'source_message.masterts',
                'mts' => 'message.masterts'])
            ->joinWith('translations')
            ->where(['>', 'source_message.masterts', $masterts_source_messages])
            ->orWhere(['>', 'message.masterts', $masterts_messages])
            ->createCommand()
            ->queryAll();

        // decode the data
        $local_messages = json_decode(Yii::$app->request->post('local_messages'), true);
        // update master messages
        $local_messages_processed = [];
        foreach ($local_messages as &$row) {
            $source = Sourcemessage::find()
                    ->where(['category' => $row['cat'], 'message' => $row['mess']])
                    ->one();
            if (!isset($source)) {
                $source = new Sourcemessage();
            }
            $source->category = $row['cat'];
            $source->message = $row['mess'];
            // update our timestamps, which we need for database synchronisation
            if ($source->isAttributeChanged('message', true) ||
                    $source->isAttributeChanged('category', true)) {
                $source->masterts = new Expression('NOW()');
            }
            $source->save();
            $language = $row['lang'];
            $message = Message::find()
                    ->where(['id' => $source->id])
                    ->andWhere(['language' => $language])
                    ->one();
            if (!isset($message)) {
                $message = new Message();
                $message->language = $language;
                $message->id = $source->id;
            }
            $message->translation = $row['tran'];
            // update our timestamps, which we need for database synchronisation
            if ($message->isAttributeChanged('translation', true)) {
                $message->masterts = new Expression('NOW()');
            }
            $message->save();
            // collect the id's, they will be sent back to the local server
            $local_messages_processed[] = $row['id'];
        }
        
        return json_encode(['local_message_processed' => $local_messages_processed,
            'master_messages' => $master_messages
                ]);
    }
}