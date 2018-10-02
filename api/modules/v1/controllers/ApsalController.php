<?php

namespace api\modules\v1\controllers;

use common\mailer\ImportMailer;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\web\MethodNotAllowedHttpException;
use common\helpers\ImportJson;
use common\todo\ToDoWorkers;
use common\todo\ToDoEmpcontracts;
use common\todo\ToDoMedvisits;
use common\models\User;

// the client server does a query in the user table username='api'
// the auth_key from this user is sent to the master server
// this one checks if a user with such a auth_key exists, event if he has another name
// if yes -> access is granted
// ->> this means that clients should only have one 'api' user
//     each client server with a different auth_key

class ApsalController extends Controller
{
    public $ky = 'Iq4P6ZudZRs9QhFxLJ98L6a84Af805fS'; // 32 * 8 = 256 bit key
    public $iv = 'Xk1gX85W90uX7y7MS41E5948qEGg5swq'; // 32 * 8 = 256 bit iv

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];
        return $behaviors;
    }

    public function actionImport()
    {
        $faker = false;
        $ilog = [];
        $mandantIDcache = [];

        $filedata = file_get_contents($_FILES["file"]["tmp_name"]);
        $jsondata = $this->decryptRJ256($this->ky, $this->iv, $filedata);
        $rows = json_decode($jsondata, true);

        // nearly identical loops for both:
        // - api\modules\v1\controllers\ApsalController
        // - common\helpers\ImportJson\File
        // always change both !!
        try {
            foreach ($rows as $row) {
                if (empty($row['MM'])) {
                    array_push($ilog, 'Skipping row, no Mandant Matricule MM given');
                    continue;
                }

                if (empty($row['Code'])) {
                    array_push($ilog, 'Skipping row, no Code given');
                    continue;
                }

                if (empty($mandantIDcache[$row['MM']])) {
                    // get the mandant and populate chache
                    $mandant_id = User::find()
                                  ->select(['username'])
                                  ->innerJoin('Mandants', "Concat('api', Mandants.ID_Mandant) = user.username AND " . "Mandants.Matricule = '" . $row['MM'] . "'")
                                  ->where(['auth_key' => $_SERVER['PHP_AUTH_USER']])
                                  ->scalar();

                    $mandant_id = ltrim($mandant_id, 'api');

                    if (empty($mandant_id)) {
                        array_push($ilog, 'Skipping row, Mandant Matricule MM not found or invalid auth_key');
                        continue;
                    }

                    $mandantIDcache[$row['MM']] = $mandant_id;
                }

                switch ($row['Code']) {
                    case "APSALENT":
                        $ilog = array_merge($ilog, ImportJson::APSALENT($row, $faker, $mandantIDcache[$row['MM']]));
                        break;
                    case "APSALTRAV":
                        $ilog = array_merge($ilog, ImportJson::TRAV($row, $faker, $mandantIDcache[$row['MM']], 'Y-m-d'));
                        break;
                    case "APSALABS":
                        $ilog = array_merge($ilog, ImportJson::APSALABS($row, $faker, $mandantIDcache[$row['MM']]));
                        break;
                    default:
                        array_push($ilog, 'Skipping row, invalid Code ' . $row['Code']);
                }
            }

            foreach ($mandantIDcache as $key => $value) {
                $ilog = array_merge($ilog, ToDoWorkers::CheckAll($value));
                $ilog = array_merge($ilog, ToDoEmpcontracts::CheckAll($value));
                $ilog = array_merge($ilog, ToDoMedvisits::CheckAll($value));
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        // send report by email to admins and TD
        $mailerImport = new ImportMailer();
        $ilog = array_merge($ilog, $mailerImport->import(array_values($mandantIDcache), $ilog));
        $mailerImport->sendToPeter('Import ' . join(', ', array_keys($mandantIDcache)), $ilog);
        return json_encode($ilog);
    }

    function decryptRJ256($key, $iv, $string_to_decrypt)
    {
        $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string_to_decrypt), MCRYPT_MODE_CBC, $iv);
        return(rtrim($rtn, "\0..\32"));
    }

    function encryptRJ256($key, $iv, $string_to_encrypt)
    {
        $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
        return(base64_encode($rtn));
    }
}
