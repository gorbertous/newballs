<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Expression;
use yii\httpclient\Client;
use backend\models\ {
    Message, MessageSearch, Sourcemessage,
    Sourcemessagescan as SMS,
    SourcemessagescanSearch as SMSSearch,
    Sourcemessagescanlocation as SMSL
};
use common\helpers\Language as Lx;
use common\dictionaries\ContextLetter;
use common\models\User;

/**
 * Class MessageController
 * @package backend\controllers
 */
class MessageController extends Controller
{
    use TraitController;

    // which folders should be scanned
    private $scanroot = '@backend' . DIRECTORY_SEPARATOR;
    // list of the php function for translating messages.
    private $phpTranslators = ['::t'];
    // list of the js function for translating messages.
//    private $jsTranslators = ['lajax.t'];
    // list of file extensions that contain language elements.
    private $phpPatterns = ['*.php'];
    // these categories won't be included in the language database.
    private $ignoredCategories = ['yii'];
    // these files will not be processed.
    private $ignoredItems = [
        'config',
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/vendor',
        '/backend/assets',
        '/BaseYii.php',
        'runtime',
        'bower',
        'nikic',
    ];
    // Regular expression to match PHP namespace definitions.
    public $patternNamespace = '/namespace\s*(.+?)\s*;/i';
    // Regular expression to match PHP class definitions.
    public $patternClass = '/(?<!(\/\/\s)|\*\s|\/\*\*\s)(?:class|interface)\s*(.+?)\s/i';
    // Regular expression to match PHP Implements definitions.
    public $patternImplements = '/class\s*.+?\simplements\s(.+?)\s/i';
    // Regular expression to match PHP const assignments.
    public $patternConst = '/const\s*(.+?)\s*=\s*(.+);/i';
    // Regular expression to match PHP use and use as definitions.
    public $patternUse = '/use\s*(.+?\\<Searchstring>)\s*;/i';
    public $patternUseas = '/use\s*(.+?)\s*as\s*<Searchstring>\s*;/i';
    // Regular expression to match PHP Yii::t functions.
    public $patternPhp = '/::t\s*\(\s*(((["\'])(?:(?=(\\\\?))\4.)*?\3|[\w\d:$>-]*?|[\s\.]*?)+?)\s*\,\s*(((["\'])(?:(?=(\\\\?))\8.)*?\7|[\w\d:$>-]*?|[\s\.]*?)+?)\s*[,\)]/';
    // Regular expression to split up PHP concat "./dot" into parts
    public $patternConcatSplit = '/(["\'])(?:\\\\.|[^\1])*?\1|[^\s\.]+/';
    // holds all the const elements
    private $_constElements = [];
    // holds all the language elements
    private $_languageElements = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::MESSAGE);
    }

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
                        'controllers' => ['message'],
                        'actions'     => ['index', 'indexdupes', 'indexunused',
                            'create', 'update', 'view', 'delete', 'sync', 'copy',
                            'scanrun', 'scannew', 'scanblacklisted', 'locations',
                            'blacklist', 'whitelist'],
                        'allow'       => true,
                        'roles'       => ['developer'],
                    ]
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
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pendinguploads = Sourcemessage::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar() +
            Message::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar();

        return $this->renderNormalorAjax('index', [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'pendinguploads' => $pendinguploads,
            'context_array'  => $this->getSpecificContextArray()
        ]);
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionIndexdupes()
    {
        $searchModel = new MessageSearch();
        $searchModel->duplicates = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pendinguploads = Sourcemessage::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar() +
            Message::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar();

        return $this->renderNormalorAjax('indexdupes', [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'pendinguploads' => $pendinguploads,
            'context_array'  => $this->getSpecificContextArray()
        ]);
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionIndexunused()
    {
        $searchModel = new MessageSearch();
        $searchModel->unused = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pendinguploads = Sourcemessage::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar() +
            Message::find()
                ->select('COUNT(*)')
                ->where(['not', ['localts' => null]])
                ->scalar();

        return $this->renderNormalorAjax('indexunused', [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'pendinguploads' => $pendinguploads,
            'context_array'  => $this->getSpecificContextArray()
        ]);
    }

    /**
     * @return mixed
     */
    public function actionScannew()
    {
        $searchModel = new SMSSearch();
        $searchModel->blacklisted = 0;
        $searchModel->new = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('indexscannew', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * @return mixed
     */
    public function actionScanblacklisted()
    {
        $searchModel = new SMSSearch();
        $searchModel->blacklisted = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderNormalorAjax('indexscanblacklisted', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * @return string
     */
    public function actionLocations()
    {
        if (isset($_POST['expandRowKey'])) {
            $referrer = explode('?', Yii::$app->request->referrer)[0];
            $referrer = substr($referrer, strrpos($referrer, '/') + 1);
            switch ($referrer) {
                case 'index':
                case 'indexdupes':
                    $id = $_POST['expandRowKey']['id'];
                    $sms = SMS::findOne(['source_message_id' => $id]);
                    break;
                case 'scannew':
                case 'scanblacklisted':
                    $id = $_POST['expandRowKey'];
                    $sms = SMS::findOne(['id' => $id]);
                    break;
                default:
                    break;
            }
            if (!empty($sms)) {
                $smsl = $sms->sourceMessageScanlocations;
                return join('<br>', ArrayHelper::getColumn($smsl, 'location'));
            }
        }
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionBlacklist($id)
    {
        $sms = SMS::findOne($id);

        if (!isset($sms)) {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }

        $sms->blacklisted = 1;
        $sms->save(false);

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionWhitelist($id)
    {
        $sms = SMS::findOne($id);

        if (!isset($sms)) {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }

        $sms->blacklisted = 0;
        $sms->save(false);

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * {@inheritdoc}
     */
    public function actionView($id, $lang)
    {
        if (!$model = Message::findOne(['id' => $id, 'language' => $lang])) {
            throw new NotFoundHttpException('Error finding the current translation!');
        }

        return $this->renderNormalorAjax('view', [
            'model' => $model
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate($id = null)
    {
        $source = new Sourcemessage();

        if (!isset($source)) {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }

        if ($source->load(Yii::$app->request->post())) {
            if (!$source->validate()) {
                var_dump($source->errors);
                die;
            }

            // update our timestamps, which we need for database synchronisation
            if ($source->isAttributeChanged('message', true) ||
                $source->isAttributeChanged('category', true)) {
                if (Lx::isMaster()) {
                    $source->masterts = new Expression('NOW()');
                } else {
                    $source->localts = new Expression('NOW()');
                }
            }

            $source->save();

            // save the translations
            $langlist = array('en', 'fr', 'de');

            foreach ($langlist as $language) {
                $message = Message::find()
                    ->where(['id' => $source->id])
                    ->andWhere(['language' => $language])
                    ->one();

                if (!isset($message)) {
                    $message = new Message();
                    $message->language = $language;
                    $message->id = $source->id;
                }

                $message->translation = $_POST['Sourcemessage']['translation' . strtoupper($language)];

                // update our timestamps, which we need for database synchronisation
                if ($message->isAttributeChanged('translation', true)) {
                    if (Lx::isMaster()) {
                        $message->masterts = new Expression('NOW()');
                    } else {
                        $message->localts = new Expression('NOW()');
                    }
                }

                $message->save();
            }

            return $this->redirect(Yii::$app->request->referrer);

        } else {

            if ($id != null) {
                // we create a new item based on a Sourcemessagescan item
                $sms = SMS::findOne($id);
                $source->category = $sms->category;
                $source->message = $sms->message;
            }

            $categories = ArrayHelper::getColumn(SourceMessage::find()
                ->distinct()
                ->select('category')
                ->orderBy('category')
                ->all(), 'category');

            $pendinguploads = Sourcemessage::find()
                    ->select('COUNT(*)')
                    ->where(['not', ['localts' => null]])
                    ->scalar() + Message::find()
                    ->select('COUNT(*)')
                    ->where(['not', ['localts' => null]])
                    ->scalar();

            return $this->renderNormalorAjax('create', [
                'source'         => $source,
                'language'       => Yii::$app->language,
                'categories'     => $categories,
                'pendinguploads' => $pendinguploads
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($id, $id2)
    {
        $source = Sourcemessage::findOne($id);

        if (!isset($source)) {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }

        if ($source->load(Yii::$app->request->post())) {
            if (!$source->validate()) {
                var_dump($source->errors);
                die;
            }

            // update our timestamps, which we need for database synchronisation
            if ($source->isAttributeChanged('message', true) ||
                $source->isAttributeChanged('category', true)) {
                if (Lx::isMaster()) {
                    $source->masterts = new Expression('NOW()');
                } else {
                    $source->localts = new Expression('NOW()');
                }
            }

            $source->save();

            // save the translations
            $langlist = array('en', 'fr', 'de');

            foreach ($langlist as $language) {
                $message = Message::find()
                    ->where(['id' => $source->id])
                    ->andWhere(['language' => $language])
                    ->one();

                if (!isset($message)) {
                    $message = new Message();
                    $message->language = $language;
                    $message->id = $source->id;
                }

                $message->translation = $_POST['Sourcemessage']['translation' . strtoupper($language)];

                // update our timestamps, which we need for database synchronisation
                if ($message->isAttributeChanged('translation', true)) {
                    if (Lx::isMaster()) {
                        $message->masterts = new Expression('NOW()');
                    } else {
                        $message->localts = new Expression('NOW()');
                    }
                }

                $message->save();
            }

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $categories = ArrayHelper::getColumn(SourceMessage::find()
                ->distinct()
                ->select('category')
                ->orderBy('category')
                ->all(), 'category');

            $pendinguploads = Sourcemessage::find()
                    ->select('COUNT(*)')
                    ->where(['not', ['localts' => null]])
                    ->scalar() +
                Message::find()
                    ->select('COUNT(*)')
                    ->where(['not', ['localts' => null]])
                    ->scalar();

            return $this->renderNormalorAjax('update', [
                'source'         => $source,
                'language'       => $id2,
                'categories'     => $categories,
                'pendinguploads' => $pendinguploads
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionCopy($id)
    {
        $source = Sourcemessage::find()
            ->where(['id' => $id])
            ->one();

        if (!isset($source)) {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }

        $dest = new Sourcemessage();
        $dest->attributes = $source->attributes;
        if (Lx::isMaster()) {
            $dest->masterts = new Expression('NOW()');
        } else {
            $dest->localts = new Expression('NOW()');
        }
        if (!$dest->validate()) {
            var_dump($dest->errors);
            die;
        }
        $dest->save();

        // save the translations
        $langlist = array('en', 'fr', 'de');

        foreach ($langlist as $language) {
            $message = Message::find()
                ->where(['id' => $source->id])
                ->andWhere(['language' => $language])
                ->one();

            if (isset($message)) {
                $destmess = new Message();
                $destmess->attributes = $message->attributes;
                $destmess->id = $dest->id;
                if (Lx::isMaster()) {
                    $destmess->masterts = new Expression('NOW()');
                } else {
                    $destmess->localts = new Expression('NOW()');
                }
                if (!$destmess->validate()) {
                    var_dump($destmess->errors);
                    die;
                }
                $destmess->save();
            }

        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete(int $id)
    {
        Message::deleteAll(['id' => $id]);
        Sourcemessage::deleteAll(['id' => $id]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return string
     *
     * @throws \yii\db\Exception
     */
    public function actionSync()
    {
        // get our local api user
        // which we will use to validate against a master server api user e.g. api_post
        $api_user = User::find()
            ->where(['username' => 'api'])
            ->one();

        $auth_key = isset($api_user) ? $api_user->auth_key . ":" : '';

        // list of messages which were updated locally
        $local_messages = Sourcemessage::find()
            ->select(['id'   => 'source_message.id',
                      'cat'  => 'category',
                      'mess' => 'message',
                      'lang' => 'language',
                      'tran' => 'translation'])
            ->joinWith('translations')
            ->where(['not', ['source_message.localts' => null]])
            ->orWhere(['not', ['message.localts' => null]])
            ->createCommand()
            ->queryAll();

        // when did we get the last messages from the server?
        $masterts_messages = Message::find()
            ->select('masterts')
            ->orderBy('masterts DESC')
            ->one();

        // when did we get the last source_messages from the server?
        $masterts_source_messages = Sourcemessage::find()
            ->select('masterts')
            ->orderBy('masterts DESC')
            ->one();

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://www.' . Lx::getMaster() . '/api/v1/message/sync')// '.Lx::getMaster().'
            ->setHeaders(array(
                'cache-control' => 'no-cache',
                'content-type'  => 'application/x-www-form-urlencoded',
                'authorization' => 'Basic ' . base64_encode($auth_key)
            ))
            ->setData(['masterts_messages'        => json_encode($masterts_messages->masterts),
                       'masterts_source_messages' => json_encode($masterts_source_messages->masterts),
                       'local_messages'           => json_encode($local_messages)])
            ->send();

        if ($response->isOk) {
            $data = json_decode($response->data, true);

            // decode the response data
            $local_message_processed = $data['local_message_processed'];

            // reset the localts to null for the id's uploaded
            Yii::$app->db->createCommand()
                ->update('message', ['localts' => null], ['id' => $local_message_processed])
                ->execute();

            Yii::$app->db->createCommand()
                ->update('source_message', ['localts' => null], ['id' => $local_message_processed])
                ->execute();

            // insert/update list with master messages
            $master_messages = $data['master_messages'];

            foreach ($master_messages as &$row) {
                $source = Sourcemessage::find()
                    ->where(['category' => $row['cat'], 'message' => $row['mess']])
                    ->one();

                if (!isset($source)) {
                    $source = new Sourcemessage();
                }

                $source->category = $row['cat'];
                $source->message = $row['mess'];
                $source->masterts = $row['smts'];

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
                $message->masterts = $row['mts'];

                $message->save();
            }

            return 'SUCCESS: ' . PHP_EOL . count($local_message_processed) . ' source messages uploaded' . PHP_EOL .
                count($master_messages) . ' translations downloaded';
        } else {
            return 'ERROR: ' . $response;
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionScanrun()
    {
        // FILES LIST
        $rootpath = Yii::getAlias($this->scanroot);

        $files = FileHelper::findFiles($rootpath, [
            'except' => $this->ignoredItems,
            'only'   => $this->phpPatterns,
        ]);

        // first fetch constants
        $implementstack = [];
        foreach ($files as $file) {
            $text = file_get_contents($file);
            $namespace = $this->regexFirstMatch($this->patternNamespace, $text);
            if ($namespace) {
                $class = $this->regexSecondMatch($this->patternClass, $text);
                $implements = $this->regexFirstMatch($this->patternImplements, $text);
                $namespaceclass = $namespace . '\\' . $class;
                $namespaceimplements = $namespace . '\\' . $implements;
                preg_match_all($this->patternConst, $text, $matches, PREG_SET_ORDER, 0);
                foreach ($matches as $match) {
                    $const = $match[1];
                    $msg = $match[2];
                    $this->_constElements[$namespaceclass . '\\' . $const] = $msg;
                }
                if ($implements) {
                    $implementstack[$namespaceclass] = $namespaceimplements;
                }
            }
        }
        // apply all the implements
        foreach ($implementstack as $kis => $vis) {
            foreach ($this->_constElements as $constname => $msg) {
                if (0 === strpos($constname . '\\', $vis . '\\')) {
                    $this->_constElements[str_replace($vis, $kis, $constname)] = $msg;
                }
            }
        }

//        dd($this->_constElements);

        // parse all the files contains a namespace definition
        foreach ($files as $file) {
            $text = file_get_contents($file);
            $namespace = $this->regexFirstMatch($this->patternNamespace, $text);
            // process files only if we have a namespace
            if ($namespace) {
                $class = $this->regexSecondMatch($this->patternClass, $text);
                $implements = $this->regexFirstMatch($this->patternImplements, $text);
                $namespaceclass = $namespace . '\\' . $class;
                /** @noinspection PhpUnusedLocalVariableInspection */
                $namespaceimplements = $namespace . '\\' . $implements;
                if ($this->containsTranslator($this->phpTranslators, $text)) {
                    preg_match_all($this->patternPhp, $text, $matches, PREG_SET_ORDER, 0);
                    foreach ($matches as $match) {
                        $cat = $match[1];
                        $msg = $match[5];
                        $lines = file($file);
                        $line_number = false;
                        foreach ($lines as $key => $line) {
                            if (strpos($line, $match[0]) !== FALSE) {
                                $line_number = $key + 1;
                                break;
                            }
                        }
                        // split up the category by . and replace constants
                        $cat = $this->replaceConst($cat, $namespaceclass, $text);
                        $msg = $this->replaceConst($msg, $namespaceclass, $text);
                        if (!in_array($cat, $this->ignoredCategories)) {
                            $this->_languageElements[$cat][$msg][] = $namespaceclass . ':' . $line_number;
                        }
                    }
                }
            }
        }

//        dd($this->_languageElements);

        // store scan run in source_message_scan and _scanlocation tables
        // invalidate the existing records
        SMS::updateAll(['valid' => 0]);
        SMSL::deleteAll();
        foreach ($this->_languageElements as $cat => $msgs) {
            foreach ($msgs as $msg => $locations) {
                // try to find this cat/msg in the SMScan table
                $sms = SMS::findOne(['category' => $cat, 'message' => $msg]);
                if (empty($sms)) {
                    $sms = new SMS;
                    $sms->category = $cat;
                    $sms->message = $msg;
                }
                $sms->valid = 1;
                // try to find this cat/msg in the source_message table
                $sm = Sourcemessage::findOne(['category' => $cat, 'message' => $msg]);
                if (!empty($sm)) {
                    $sms->source_message_id = $sm->id;
                    $sms->new = 0;
                } else {
                    $sms->source_message_id = null;
                    $sms->new = 1;
                }
                $sms->loccount = count($locations);
                $sms->save(false);
                foreach ($locations as $loc) {
                    $smsl = new SMSL;
                    $smsl->source_message_scan_id = $sms->id;
                    $smsl->location = $loc;
                    $smsl->save(false);
                }
            }
        }
        SMS::deleteAll(['valid' => 0]);

        // redirect to the new items found
        return $this->redirect('/message/scannew');
    }

    /**
     * @param $pattern
     * @param $subject
     *
     * @return mixed
     */
    protected function regexFirstMatch($pattern, &$subject)
    {
        preg_match($pattern, $subject, $match);
        if (count($match) > 0) {
            return $match[1];
        }
    }

    /**
     * @param $pattern
     * @param $subject
     *
     * @return mixed
     */
    protected function regexSecondMatch($pattern, &$subject)
    {
        preg_match($pattern, $subject, $match);
        if (count($match) > 0) {
            return $match[2];
        }
    }

    /**
     * @param string $argument
     * @param string $namespaceclass
     * @param string $text
     *
     * @return string
     */
    protected function replaceConst(string &$argument, string $namespaceclass, string &$text): string
    {
        preg_match_all($this->patternConcatSplit, $argument, $argumentdotparts, PREG_PATTERN_ORDER, 0);
        $argumentdotparts = $argumentdotparts[0];
        foreach ($argumentdotparts as $adkey => $adpart) {
            $constparts = explode('::', $adpart);
            if (count($constparts) > 1) {
                // we have a constant, replace it with value
                if ($constparts[0] == 'self') {
                    // const within self::
                    $constparts[0] = $namespaceclass;
                    $constname = implode('\\', $constparts);
                    if (isset($this->_constElements[$constname])) {
                        // self refers to itself
                        $argumentdotparts[$adkey] = $this->_constElements[$constname];
                    }
                } else {
                    // const within some other class
                    $otherclass = $constparts[0];
                    $useclass = $this->regexFirstMatch(str_replace('<Searchstring>', $otherclass, $this->patternUse), $text);
                    $constparts[0] = $useclass;
                    $constname = implode('\\', $constparts);
                    if (isset($this->_constElements[$constname])) {
                        // self refers to use class
                        $argumentdotparts[$adkey] = $this->_constElements[$constname];
                    } else {
                        // self refers to use as class
                        $useasclass = $this->regexFirstMatch(str_replace('<Searchstring>', $otherclass, $this->patternUseas), $text);
                        $constparts[0] = $useasclass;
                        $constname = implode('\\', $constparts);
                        if (isset($this->_constElements[$constname])) {
                            // self refers to useas class
                            $argumentdotparts[$adkey] = $this->_constElements[$constname];
                        }
                    }
                }
                $constname = implode('\\', $constparts);
                if (isset($this->_constElements[$constname])) {
                    $argumentdotparts[$adkey] = $this->_constElements[$constname];
                }
            }
            $argumentdotparts[$adkey] = trim($argumentdotparts[$adkey], '"');
            $argumentdotparts[$adkey] = trim($argumentdotparts[$adkey], "'");
        }
        return implode('', $argumentdotparts);

    }

    /**
     * @param $translators
     * @param $text
     *
     * @return bool
     */
    protected function containsTranslator($translators, &$text)
    {
        return preg_match(
                '#(' . implode('\s*\()|(', array_map('preg_quote', $translators)) . '\s*\()#i',
                $text
            ) > 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
        }
    }
}
