<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\News;
use frontend\models\NewsSearch;
use backend\models\Tags;
use backend\models\JNewsTags;
use common\helpers\Errorhandler as Errorhandler;
use common\dictionaries\ContextLetter;

/**
 * Class NewsController
 * @package frontend\controllers
 */
class NewsController extends Controller
{

    use TraitController;
    use TraitFileUploads;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setSessionContext(ContextLetter::NEWS);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => array_merge(self::FileUploadRules(), [
                    [
                        'controllers' => ['news'],
                        'actions'     => ['create', 'update', 'delete'],
                        'allow'       => true,
                        'roles'       => ['team_member']
                    ],
                    [
                        'controllers' => ['news'],
                        'actions'     => ['index', 'view'],
                        'allow'       => true,
                        'roles'       => ['reader']
                    ]
                ])
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $searchModel->is_valid = -1;
        $searchModel->is_public = -1;
        $searchModel->to_newsletter = -1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'context_array' => $this->getSpecificContextArray()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionView($id)
    {
        return $this->renderNormalorAjax('view', [
                    'model' => $this->findModel($id)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        $model = new News();
        $model->c_id = $model->c_id = Yii::$app->session->get('c_id');
        $model->is_public = 1;
        $model->is_valid = 0;
        $model->to_newsletter = 1;
        $model->tags_ids = [];

        if ($model->load(Yii::$app->request->post())) {
            $news_array = (Yii::$app->request->post('News'));

            $model->AjaxUpdateModel('ajaxfilefeatured');
            $model->AjaxUpdateModel('ajaxfilecontent');

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                if ($flag = $model->save(false)) {

                    if (!empty($news_array['tags_ids'])) {
                        foreach ($news_array['tags_ids'] as $value) {
                            if (is_numeric($value)) {
                                //exisiting tags - array value is numeric
                                $j_news_tags = new JNewsTags;
                                $j_news_tags->news_id = $model->id;
                                $j_news_tags->tag_id = $value;
                                $j_news_tags->save(false);
                            } else {
                                //new tags - array value is a string
                                //check if the tag already exists in our db
                                if (empty(Tags::findOneByName($value))) {
                                    $new_tag = new Tags;
                                    $new_tag->name_FR = $value;
                                    $new_tag->name_EN = $value;
                                    $new_tag->name_DE = $value;
                                    $new_tag->save(false);

                                    $j_news_tags2 = new JNewsTags;
                                    $j_news_tags2->news_id = $model->id;
                                    $j_news_tags2->tag_id = $new_tag->tag_id;
                                    $j_news_tags2->save(false);
                                }
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();
                $this->getDbMsg($e->getMessage());
            }

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderNormalorAjax('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdate($id)
    {
        /** @var $model \monitoring\models\base\News */
        $model = $this->findModel($id);
        $model->tags_ids = $model->tags;

        if ($model->load(Yii::$app->request->post())) {
            $news_array = (Yii::$app->request->post('News'));

            $model->AjaxUpdateModel('ajaxfilefeatured');
            $model->AjaxUpdateModel('ajaxfilecontent');

            $valid = $model->validate();

            if (!$valid) {
                $this->getBaseMsg($model->errors);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                if ($flag = $model->save(false)) {

                    JNewsTags::deleteAll(['news_id' => $model->id]);

                    if (!empty($news_array['tags_ids'])) {
                        foreach ($news_array['tags_ids'] as $value) {
                            if (is_numeric($value)) {
                                //exisiting tags - array value is numeric
                                $j_news_tags = new JNewsTags;
                                $j_news_tags->news_id = $model->id;
                                $j_news_tags->tag_id = $value;
                                $j_news_tags->save(false);
                            } else {
                                //new tags - array value is a string
                                //check if the tag already exists in our db
                                if (empty(Tags::findOneByName($value))) {
                                    $new_tag = new Tags;
                                    $new_tag->name_FR = $value;
                                    $new_tag->name_EN = $value;
                                    $new_tag->name_DE = $value;
                                    $new_tag->save(false);

                                    $j_news_tags2 = new JNewsTags;
                                    $j_news_tags2->news_id = $model->id;
                                    $j_news_tags2->tag_id = $new_tag->tag_id;
                                    $j_news_tags2->save(false);
                                }
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();
                $this->getDbMsg($e->getMessage());
            }

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            //$model->logView();
            return $this->renderNormalorAjax('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $modelbackup = $model;

        try {
            $model->delete();
        } catch (\Exception $ex) {
            \Yii::$app->getSession()->setFlash('error', Errorhandler::getRelatedData($model));
        }

        $modelbackup->DeleteStoreFiles('ajaxfilefeatured');
        $modelbackup->DeleteStoreFiles('ajaxfilecontent');

        return $this->redirect(Yii::$app->request->referrer);
    }

}
