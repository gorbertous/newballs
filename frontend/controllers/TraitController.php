<?php

namespace frontend\controllers;

use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

//use common\dictionaries\ContextLetter;

/**
 * Trait TraitController
 * @package frontend\controllers
 */
trait TraitController
{

    /**
     * @param array $errors
     *
     * @throws \yii\base\Exception
     */
    protected function getBaseMsg(array $errors)
    {
        throw new \yii\base\Exception('Something went wrong while validating data. The following errors occurred: ' . json_encode($errors, JSON_PRETTY_PRINT) . PHP_EOL);
    }

    /**
     * @param string $e
     *
     * @throws Exception
     */
    protected function getDbMsg(string $e)
    {
        /** @var $e \yii\db\Exception */
        throw new Exception('Transaction rolled back, error saving to the database. Errors: ' . $e);
    }

    /**
     * Check if the user is author
     *
     * @return boolean
     */
    protected function isUserAuthor()
    {
        return $this->findModel(Yii::$app->request->get('id'))->member_id === $this->getSessionMemberID();
    }

    /**
     * If the request is made via ajax render the view in ajax
     * otherwise render the normal way
     *
     * @param $form
     * @param $params
     * @return mixed
     */
    public function renderNormalorAjax($form, $params)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($form, $params);
        }
        return $this->render($form, $params);
    }

    /**
     * Return the current model name as a string
     * Attention, some Controllers share the same model
     * this function takes care of this
     *
     * @return string
     */
    public function getModelName(): string
    {
        $modelname = str_replace('Controller', '', ucfirst(Yii::$app->controller->id));
        // some Controllers share the same model
        switch ($modelname) {
            case 'Playdates':
                $modelname = 'PlayDates';
                break;
            case 'Gamesboard':
            case 'Rota':
                $modelname = 'GamesBoard';
                break;
            case 'Membershiptype':
                $modelname = 'MembershipType';
                break;
            case 'Adminusers':
                $modelname = 'User';
                break;
        }
        return $modelname;
    }

    /**
     * Return the model path as a string
     * Attention, some Models are not in the backend folder
     * this function takes care of this
     *
     * @param string $modelname
     *
     * @return string
     */
    public function getModelPath(string $modelname): string
    {
        $modelpath = "backend\\models\\";

        switch ($modelname) {
            case 'User':
                $modelpath = "common\\models\\";
                break;
            case 'Authitem':
            case 'Authitemchild':
                $modelpath = "common\\rbac\\models\\";
                break;
        }

        return $modelpath;
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Object
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        /** @var $class Object */
        /** @var $model Object */
        $modelname = $this->getModelName();
        $class = $this->getModelPath($modelname) . $modelname;

        if (($model = $class::findOne($id)) !== null) {
//            if ($model->hasAttribute('ID_Mandant') && !Yii::$app->user->can('developer')) {
//                // check if user has access to this mandant or access to the library
//                if ($model->ID_Mandant !== $this->getSessionClubID() && $model->ID_Mandant !== 0) {
//                    throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
//                }
//            }
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The item was not found.'));
    }

    /**
     * Return the Current Search model
     *
     * @return mixed
     */
    protected function newSearchModel()
    {
        $modelname = $this->getModelName();
        $class = $this->getModelPath($modelname) . $modelname . "Search";
        return new $class;
    }

    /**
     * Returns the Current model
     *
     * @return mixed
     */
    protected function newModel()
    {
        $modelname = $this->getModelName();
        $class = $this->getModelPath($modelname) . $modelname;
        return new $class;
    }

    /**
     * Returns a session value based on the $key
     *
     * @param string $key
     * @return mixed
     */
    public function getSession(string $key)
    {
        return Yii::$app->session->get($key);
    }

    /**
     * Set session
     *
     * @param string $key
     * @param $value
     */
    public function setSession(string $key, $value)
    {
        return Yii::$app->session->set($key, $value);
    }

    /**
     * Deletes a session based on the $key
     *
     * @param string|null $key
     * @return mixed
     */
    public function removeSession(string $key)
    {
        return Yii::$app->session->remove($key);
    }

    /**
     * Checks if the session has still a valid club_id
     * if not, redirects to /site/select
     *
     * @param string $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        // the user is authentificated, but he has not properly selected the
        // mandant to work on, redirect him to select page and stop this action
        if ($this->getSessionMemberID() === null) {
            $this->redirect('/logout'); return false;
        }
        // Currently displayed language for this mandant
        // this is set in common\helpers\TraitIndex every time a index page is displayed
        // after a test if UI language is available for this mandant
        // (because the UI language might be changed with the codemix component)
    
        $UIlanguage = strtoupper(Yii::$app->language);
        $manlan = Yii::$app->session->get('club_languages');

        if (in_array($UIlanguage, $manlan)) {
            // content language is UI language
            Yii::$app->session->set('_content_language', '_' . $UIlanguage);
        } else {
            // content language us primary language
            Yii::$app->session->set('_content_language', '_' . $manlan[0]);
        }
        //dd(Yii::$app->session->get('_content_language'));
        // fallback to primary language and then to EN
        Yii::$app->session->set('_fallback1_language', '_' . $manlan[0]);
        Yii::$app->session->set('_fallback2_language', '_EN');

        /** @noinspection PhpUndefinedClassInspection */
        return parent::beforeAction($action);
    }

    /**
     * Returns the mandant id based on current session
     *
     * @return int if session is valid
     *         or
     *         null if session is not valid
     */
    public function getSessionClubID()
    {
        return Yii::$app->user->member->c_id ?? null;
    }

    /**
     * Returns the contact id based on current session
     *
     * @return int if session is valid
     *         or
     *         null if session is not valid
     */
    public function getSessionMemberID()
    {
        return $this->getSession('member_id');
    }

    /**
     * Returns the context letter based on current session
     *
     * @return string if session is valid
     *         or
     *         null if session is not valid
     */
    public function getSessionContext()
    {
        return $this->getSession('CW_Type');
    }

    /**
     * Set the context letter based in current session
     *
     * @param string $CW_Type
     *
     * @return string if session is valid
     *         or
     *         null if session is not valid
     */
    public function setSessionContext($CW_Type)
    {
        return $this->setSession('CW_Type', $CW_Type);
    }

    /**
     * @return $this
     */
    public function setJsonHeaders()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Yii::$app->response->headers->add('Content-Type', 'json');
    }

    /**
     * Return the current context name as a string
     * Attention, some Controllers share the same context
     * this function takes care of this
     *
     * @return string
     */
    public function getContextName(): string
    {
        $contextname = str_replace('Controller', '', ucfirst(Yii::$app->controller->id));

        // some Controllers share the same context
        switch ($contextname) {
            case 'Tags':
                // overwrite with News
                $contextname = 'News';
                break;
            case 'Location':
            case 'Fees':
            case 'Membershiptype':
                // overwrite with clubs
                $contextname = 'Clubs';
                break;
            case 'Userrolefields':
                // overwrite with User
                $contextname = 'User';
                break;
            case 'Adminusers':
                // overwrite with User
                $contextname = 'Authitem';
                break;
            case 'Gamesboard':
            case 'Reserves':
            case 'Scores':
                // overwrite with User
                $contextname = 'Playdates';
                break;
        }

        return $contextname;
    }

    /**
     * Returns the whole context for the current model
     *
     * @return array
     */
    public function getWholeContextArray(): array
    {
        /** @var $context Object */
        $context = "common\\context\\Context" . $this->getContextName();
        return $context::getContextArray();
    }

    /**
     * Returns the specific context for the current model
     *
     * @return array|null
     */
    public function getSpecificContextArray()
    {
        /** @var $context Object */
        $context = "common\\context\\Context" . $this->getContextName();
        return !empty(self::getSessionContext()) ? $context::getContextArray()[self::getSessionContext()] : null;
    }

    /**
     * remove all the links from related models pointing to this model
     * by setting these fields to null
     *
     * @param int $id
     *
     * @return mixed
     * @throws Exception
     */
    public function actionUnlinkrelateddata(int $id)
    {
        // first remove all the links from this model pointing to related models
        /* @var $model \backend\models\Trainings */
        $model = $this->findModel($id);
//        $model->Refreshid = null;
//        $model->Prereqid = null;
//        $model->save(false);
        // second remove all the links from related models pointing to this model
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
        $fks = Yii::$app->getDb()->createCommand("
            select distinct
                    kcu.referenced_table_name as rtn, referenced_column_name as rcn, 
                    kcu.TABLE_NAME as tn, kcu.COLUMN_NAME as cn, col.COLUMN_NAME as pk,
                    rc.DELETE_RULE as dr
            from
                    information_schema.key_column_usage kcu
                            join information_schema.columns col ON 
                                    (kcu.TABLE_SCHEMA = col.TABLE_SCHEMA AND
                                      kcu.TABLE_NAME = col.TABLE_NAME AND
                                      col.COLUMN_KEY = 'PRI')
                            join information_schema.referential_constraints rc ON
                                    (kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME)
            where
                    kcu.TABLE_SCHEMA = '" . $schema . "' AND 
                    kcu.referenced_table_name = '" . $model->tableName() . "' AND
                    kcu.referenced_table_name is not null")
                ->queryAll();

        foreach ($fks as $row) {
            $attrname = $row['cn'];
            $reldata = Yii::$app->getDb()->createCommand("
                SELECT " . $row['pk'] . " FROM " . $row['tn'] . " WHERE " . $row['cn'] . "=" . $model[$row['rcn']])
                    ->queryAll();
            foreach ($reldata as $relrow) {
                /* @var $relatedmodel \backend\models\Trainings */
                $relatedmodel = $this::findModel($relrow[$row['pk']]);
                if ($row['dr'] != 'CASCADE' &&
                        $relatedmodel->c_id == $model->c_id) {
                    if ($relatedmodel->$attrname == $id) {
                        $relatedmodel->$attrname = null;
                    }
                    $relatedmodel->save(false);
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
