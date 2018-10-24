<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\FileHelper;
use yii\filters\auth\HttpBasicAuth;
use yii\web\MethodNotAllowedHttpException;

// the client server does a query in the user table username='api'
// the auth_key from this user is sent to the master server
// this one checks if a user with such a auth_key exists, event if he has another name
// if yes -> access is granted
// ->> this means that client servers should only have one 'api' user
//     each client server with a different auth_key

class MasterController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];
        return $behaviors;
    }
    
    public function actionGetfileslist()
    {
        // get the uploads folder path
        $uploadsPath = yii::getAlias('@backups') . DIRECTORY_SEPARATOR;
        $uploadsUrl = yii::getAlias('@backupsURL') . DIRECTORY_SEPARATOR;
        
        $files = FileHelper::findFiles($uploadsPath, [
            'except' => ['thumbs/',
                'temp/',
                'mpdf/',
                'export/',
                'forms/'
                ],
            'recursive' => true,
        ]);
        $l = strlen($uploadsPath);
        foreach ($files as &$file) {
            $file = substr($file, $l);
        }
        
        return json_encode(['uploadsPath' => $uploadsPath,
            'uploadsUrl' => $uploadsUrl,
            'files' => $files
                ]);
    }

    public function actionMakesqldump()
    {
        // get the uploads folder path
        $uploadsPath = yii::getAlias('@webroot') . DIRECTORY_SEPARATOR;
        $uploadsUrl = yii::getAlias('@web') . DIRECTORY_SEPARATOR;

        $mysqldump = 'mysqldump --add-drop-table --allow-keywords -q -c -u "{username}" -h "{host}" -p"{password}" {db} | gzip -9';
        $mysqldump = 'mysqldump -u{username} -p{password} {db}';

        $mysqldump = str_replace('{username}', Yii::$app->db->username, $mysqldump);
        $mysqldump = str_replace('{password}', Yii::$app->db->password, $mysqldump);
        $mysqldump = str_replace('{host}', 'localhost', $mysqldump);
        $mysqldump = str_replace('{db}', Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar(), $mysqldump);
        
        $file = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar() . '_' .
                date('Y-m-d_H-i-s') . '.sql';
                
        exec($mysqldump . ' > ' . $uploadsPath . $file);

        $zip = new \ZipArchive;
        $zip->open($uploadsPath . $file.'.zip', \ZipArchive::CREATE || \ZipArchive::OVERWRITE);
        $zip->addFile($uploadsPath . $file, $file);
        $zip->close();
        @unlink($uploadsPath . $file);

        return json_encode([
            'uploadsPath' => $uploadsPath,
            'uploadsUrl' => $uploadsUrl,
            'file' => $file . '.zip'
        ]);
    }

    public function actionGetsqldumplist()
    {
        // get the uploads folder path
        $uploadsPath = yii::getAlias('@webroot') . DIRECTORY_SEPARATOR;
        $uploadsUrl = yii::getAlias('@web') . DIRECTORY_SEPARATOR;
        
        $files = FileHelper::findFiles($uploadsPath, [
            'only' => ['*.sql.zip'],
            'recursive' => false,
        ]);
        $l = strlen($uploadsPath);
        foreach ($files as &$file) {
            $file = substr($file, $l);
        }
        
        return json_encode([
            'uploadsPath' => $uploadsPath,
            'uploadsUrl' => $uploadsUrl,
            'files' => $files
        ]);
    }
}