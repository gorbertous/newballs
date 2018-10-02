<?php

namespace backend\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\grid\GridView;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\console\controllers\MigrateController;
use yii\httpclient\Client;
use yii\helpers\StringHelper;
//use yii\helpers\ArrayHelper;
use backend\models\Message;
use backend\models\Sourcemessage;
use common\helpers\Language as Lx;
use common\helpers\Impex;
use common\helpers\ImportJson;
use common\helpers\Helpers;
use common\models\User;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class ImportController
 * @package backend\controllers
 */
class ImportController extends Controller
{

    use TraitController;

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
                        'controllers' => ['import'],
                        'actions'     => [],
                        'allow'       => true,
                        'roles'       => ['@']
                    ]
                ]
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ]
            ]
        ];
    }

    public function actionUpload()
    {
        $uploadsPath = yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        if (!file_exists($uploadsPath)) {
            Helpers::createPath($uploadsPath);
        }

        $mode = '';

        if (isset($_FILES['ImportJsonFile'])) {
            $mode = 'ImportJsonFile';
        } elseif (isset($_FILES['ImportDBFile'])) {
            $mode = 'ImportDBFile';
        } elseif (isset($_FILES['ImportMandantFile'])) {
            $mode = 'ImportMandantFile';
        }

        // remove the session variable in case upload fails
        $this->removeSession($mode);
        @unlink($uploadsPath . $mode);
        $ajaxfile = $_FILES[$mode];

        if (move_uploaded_file($ajaxfile['tmp_name'], $uploadsPath . $ajaxfile['name'])) {
            $output = ['uploaded' => $ajaxfile['name']];
            Yii::$app->session->set($mode, $uploadsPath . $ajaxfile['name']);
        } else {
            $output = ['error' => 'Error while uploading ' . $ajaxfile['name'] . '.'];
        }

        // return a json encoded response for plugin to process successfully
        echo json_encode($output);
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'Migration');
        $request = Yii::$app->getRequest();
        $SubmitButton = Yii::$app->request->post('SubmitButton');
        $ilog = [];

        $auth_key = '';

        // MODELS LIST
        $modelspath = Yii::getAlias('@backend') . DIRECTORY_SEPARATOR .
                'models' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;
        $modelsmissingaddlang = [];

        if ($handle = opendir($modelspath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if (StringHelper::endsWith($entry, '.php')) {
                    $entry = '\backend\models\\' . substr($entry, 0, strlen($entry) - 4);
                    if (method_exists($entry, 'ContLangAttributes')) {
                        $addlang = $entry::ContLangAttributes();
                        $m = new $entry;
                        $foundallattr = true;
                        // check if the addlang attributes are well defined
                        foreach ($addlang as $addlangattr) {
                            foreach (Yii::$app->contLang->languages as $iso) {
                                if (!$m->hasAttribute($addlangattr . '_' . $iso)) {
                                    $foundallattr = false;
                                }
                            }
                        }
                        if (!$foundallattr) {
                            $modelsmissingaddlang[] = $entry;
                        }
                    }
                }
            }
            closedir($handle);
        }

        // get our local api user
        // which we will use to validate against a master server api user e.g. api_post
        $api_user = User::find()
                ->where(['username' => 'api'])
                ->one();

        if (isset($api_user)) {
            $auth_key = $api_user->auth_key . ":";
        }

        //Define, if you want to capture output, alternative to use if you dont want to write to file
        // defined('STDIN') or define('STDIN', fopen('php://input', 'r'));
        // defined('STDOUT') or define('STDOUT', fopen('php://output', 'w'));
        if (!defined('STDOUT')) {
            define('STDOUT', fopen($temp_file, 'w'));
        }

        if (!$request->isPost) {

            $model = new DynamicModel([
                'faker', 'dryrun',  'NewMigrationLabel', 'sqldumpfile', 'maintenancemode'
            ]);

            $model->addRule(['faker', 'dryrun','maintenancemode' ], 'integer');

            $zipUrl = '';
            $zipPath = '';
            $sqldumpfiles = [];
            $pendinguploads = 0;
            $model->dryrun = 1;

            if (!Lx::IsMaster()) {
                // get current backupfiles from our master
                $client = new Client();

                try {
                    /** @noinspection MissedFieldInspection */
                    $response = $client->createRequest()
                            ->setMethod('POST')
                            ->setUrl('https://www.' . Lx::MasterName() . '/api/v1/master/getsqldumplist')
                            ->setHeaders([
                                'cache-control' => 'no-cache',
                                'content-type'  => 'application/x-www-form-urlencoded',
                                'authorization' => 'Basic ' . base64_encode($auth_key)
                            ])
                            ->send();

                    if ($response->isOk) {
                        $data = json_decode($response->data, true);
                        // decode the response data
                        $sqldumpfiles = $data['files'];
                        rsort($sqldumpfiles);
                    }
                } catch (\Exception $e) {
                    $sqldumpfiles = [];
                }

                $pendinguploads = Sourcemessage::find()
                                ->select('COUNT(*)')
                                ->where(['not', ['localts' => null]])
                                ->scalar() +
                                Message::find()
                                ->select('COUNT(*)')
                                ->where(['not', ['localts' => null]])
                                ->scalar();
            }

            //migration command begin
            $migration = new MigrateController('migrate', Yii::$app);
            $migration->runAction('new', ['migrationPath' => '@console/migrations/', 'interactive' => false]);
            //migration command end
            $migrationstatus = file($temp_file);


            $model->maintenancemode = (Yii::$app->maintenanceMode->getIsEnabled(true) ? 1 : 0);

            return $this->renderNormalorAjax('import', ['model'                => $model,
                        'migrationstatus'      => $migrationstatus,
                        'pendinguploads'       => $pendinguploads,
                        'sqldumpfiles'         => $sqldumpfiles,
                        'modelsmissingaddlang' => $modelsmissingaddlang,
                        'zipUrl'               => $zipUrl,
                        'zipPath'              => $zipPath,
                        'title'                => $SubmitButton,
                        'lastConnectedUsers'   => $this->getLastConnectedUsers(),
                        'ilog'                 => null]);
        } else {

            $FormData = Yii::$app->request->post('DynamicModel');

            if ($SubmitButton == 'ImportJson') {

                if (!Yii::$app->session->has('ImportJsonFile') ||
                        !file_exists(Yii::$app->session->get('ImportJsonFile'))) {
                    Yii::$app->session->setFlash('error', 'No file uploaded!');
                } else {
                    $c_id = Yii::$app->session->get('c_id');
                    // see if our faker should be used
                    $faker = ('1' == $FormData['faker']);

                    array_push($ilog, ($faker ? 'Using faker.' : 'Not using faker'));
                    mt_srand(crc32(microtime()));

                    $ilog = ImportJson::File(Yii::$app->session->get('ImportJsonFile'), $faker);
                }
                Yii::$app->session->remove('ImportJsonFile');
            } elseif ($SubmitButton == 'SyncTranslations') {

                Yii::$app->runAction('message/sync');
            } elseif ($SubmitButton == 'BackupMaster') {

                $client = new Client();

                $response = $client->createRequest()
                        ->setMethod('POST')
                        ->setUrl('https://www.' . Lx::MasterName() . '/api/v1/master/makesqldump')
                        ->setHeaders([
                            'cache-control' => 'no-cache',
                            'content-type'  => 'application/x-www-form-urlencoded',
                            'authorization' => 'Basic ' . base64_encode($auth_key)
                        ])
                        ->send();

                $ilog[] = $response;
                $data = json_decode($response->data, true);
            } elseif ($SubmitButton == 'ImportMaster') {

                $ilog = Impex::ImportSqldump($FormData['sqldumpfile']);
            } elseif ($SubmitButton == 'ImportMasterFiles') {

                $ilog = Impex::ImportFiles($auth_key);
            } elseif ($SubmitButton == 'CleanupFiles') {

                $ilog = Impex::CleanupFiles($FormData['dryrun']);
            } elseif ($SubmitButton == 'ExportDB') {

                $zipPath = yii::getAlias('@uploads') . '/ExportDB.zip';
                $zipUrl = yii::getAlias('@uploadsURL') . '/ExportDB.zip';
                $ilog = Impex::ExportDB();
            } elseif ($SubmitButton == 'ImportDB') {

                if (!Yii::$app->session->has('ImportDBFile') ||
                        !file_exists(Yii::$app->session->get('ImportDBFile'))) {
                    Yii::$app->session->setFlash('error', 'No file uploaded!');
                } else {
                    $ilog = Impex::ImportDB(Yii::$app->session->get('ImportDBFile'));
                }
                Yii::$app->session->remove('ImportDBFile');
            } elseif ($SubmitButton == 'MigrateUp') {

                //reset the output buffer
                fopen($temp_file, 'w');
                ob_start();
                $migration = new MigrateController('migrate', Yii::$app);
                $migration->runAction('up', ['migrationPath' => '@console/migrations/', 'interactive' => false]);
                $ilog = array_merge(explode(PHP_EOL, ob_get_clean()), file($temp_file));
            } elseif ($SubmitButton == 'AddMissAddLang') {

                foreach ($modelsmissingaddlang as $entry) {
                    $addlang = $entry::ContLangAttributes();
                    $m = new $entry;
                    // check if the addlang attributes are well defined
                    foreach ($addlang as $addlangattr) {
                        $tablename = substr($entry, strrpos($entry, '\\') + 1);
                        $afterattr = '';
                        if ($m->hasAttribute($addlangattr . '_FR')) {
                            $typeattr = $m->getTableSchema()->columns[$addlangattr . '_FR']->dbType;
                        } else {
                            $typeattr = $m->getTableSchema()->columns[$addlangattr]->dbType;
                        }
                        foreach (Yii::$app->contLang->languages as $iso) {
                            $sql = '';
                            $iso = '_' . $iso;
                            if (!$m->hasAttribute($addlangattr . $iso)) {
                                if ($iso == '_FR') {
                                    if ($m->hasAttribute($addlangattr)) {
                                        // rename old attribute to FR
                                        $sql = "ALTER TABLE `$tablename` CHANGE COLUMN `$addlangattr` `$addlangattr$iso` $typeattr DEFAULT NULL;";
                                        $ilog[] = 'Renamed ' . $sql;
                                    } else {
                                        $sql = "ALTER TABLE `$tablename` ADD COLUMN `$addlangattr$iso` $typeattr DEFAULT NULL $afterattr;";
                                        $ilog[] = 'Added ' . $sql;
                                    }
                                } else {
                                    $sql = "ALTER TABLE `$tablename` ADD COLUMN `$addlangattr$iso` $typeattr DEFAULT NULL $afterattr;";
                                    $ilog[] = 'Added ' . $sql;
                                }
                            }
                            if ($sql != '') {
                                Yii::$app->getDb()->createCommand($sql)->execute();
                            }
                            $afterattr = 'AFTER ' . $addlangattr . $iso;
                        }
                    }
                }
            } elseif ($SubmitButton == 'MigrateCreate') {

                $NewMigrationLabel = $FormData['NewMigrationLabel'];
                if ($NewMigrationLabel != '') {
                    //reset the output buffer
                    fopen($temp_file, 'w');
                    $migration = new MigrateController('migrate', Yii::$app);
                    $migration->runAction('create', ['migrationPath' => '@console/migrations', 'interactive' => false, $NewMigrationLabel]);
                    $ilog = array_merge(explode(PHP_EOL, ob_get_clean()), file($temp_file));
                    Yii::$app->session->setFlash('success', 'New migration created!');
                } else {
                    Yii::$app->session->setFlash('error', 'No label given for new migration!');
                }
            } elseif ($SubmitButton == 'FlushCache') {

                Yii::$app->cache->flush();
                Yii::$app->db->schema->refresh();
                $ilog = ['Yii::$app->cache->flush()',
                    'Yii::$app->db->schema->refresh()'];
            } elseif ($SubmitButton == 'FlushAssetsCache') {

                $dir = Yii::getAlias('@webroot') . "/assets/";

                $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

                foreach ($ri as $file) {
                    $ilog[] = $file;
                    $file->isDir() ? rmdir($file) : unlink($file);
                }
            } elseif ($SubmitButton == 'Maintenancemode') {

                if (empty($FormData['maintenancemode'])) {
                    Yii::$app->maintenanceMode->disable();
                } else {
                    Yii::$app->maintenanceMode->enable();
                }
            }

            $ilog[] = 'Done';

            /** @noinspection RequireParameterInspection */
            return $this->renderNormalorAjax('import', [
                        'title' => $SubmitButton,
                        'ilog'  => $ilog]);
        }
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    protected function getLastConnectedUsers()
    {
        $sql = "SELECT user.id, members.lastname, members.firstname, user.status,
                       UserAuthLog.userId, UserAuthLog.userAgent, UserAuthLog.date, UserAuthLog.cookieBased,
                       members.c_id, members.user_id, clubs.name
                FROM UserAuthLog
                LEFT JOIN user ON UserAuthLog.userId = user.id
                LEFT JOIN members ON user.id = members.user_id
                LEFT JOIN clubs ON members.c_id = clubs.c_id
                ORDER BY date DESC";

        $lastConnectedUsers = new \yii\data\SqlDataProvider([
            'sql'        => $sql,
            'pagination' => ['pageSize' => 10]
        ]);

        return GridView::widget([
                    'dataProvider' => $lastConnectedUsers,
                    'columns'      => [
                        [
                            'attribute' => 'date',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                return Yii::$app->formatter->format($model['date'], 'datetime') . '<br />' .
                                        '<strong>' . Yii::$app->formatter->asRelativeTime($model['date']) . '</strong>';
                            }
                        ],
                        'userId',
                        'lastname',
                        'firstname',
                        'name',
                        [
                            'attribute' => 'name',
                            'label' => Yii::t('appMenu', 'Club')
                        ],
                        [
                            'attribute' => 'userAgent',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                return "<span data-toggle='tooltip' title='" . $model['userAgent'] . "'>" . substr($model['userAgent'], 0, 20) . "...</span>";
                            }
                        ],
                        [
                            'attribute' => 'cookieBased',
                            'value'     => function ($model) {
                                return Yii::$app->formatter->asBoolean($model['cookieBased']);
                            }
                        ]
                    ]
        ]);
    }

    
    /**
     * @param string $filepath
     *
     * @return string
     */
    static function getfilecontentswor(string $filepath): string
    {
        if (file_exists($filepath)) {
            return str_replace("\r", '', file_get_contents($filepath));
        } else {
            return '';
        }
    }

}
