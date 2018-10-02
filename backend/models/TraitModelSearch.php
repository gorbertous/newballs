<?php

namespace backend\models;

use Yii;

/**
 * Trait TraitModels
 *
 * @gorbertous
 * @package backend\models\base
 */
trait TraitModelSearch
{
    /**
     * merges in the Worker global filters in a query
     *
     * @param $query
     * @return mixed
     */
    static function mergeWorkersGlobalFilters($query)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_worker_ids = Yii::$app->session->get('Filter_all_workers_ids');
        $workunits_ids = Yii::$app->session->get('Filter_workunits_ids');
        $workplaces_ids = Yii::$app->session->get('Filter_locations_ids');
        if (count($all_worker_ids) > 0 ||
            count($workunits_ids) > 0 ||
            count($workplaces_ids) > 0) {
            $query->andWhere(['Contacts.ID_Contact' => $all_worker_ids]);
        }
        $worker_status = Yii::$app->session->get('Filter_worker_status');
        if ($worker_status != -1) {
            $query->andWhere([
                '(Contacts.StartDate <= DATE(NOW()) AND 
                    (Contacts.EndDate>=DATE(NOW()) OR Contacts.EndDate IS NULL))' => $worker_status
            ]);
        }
        return $query;
    }

    /**
     * merges in the Workunits global filters in a query
     *
     * @param $query
     * @param $fieldnameWU
     * @return mixed
     */
    static function mergeWorkunitsGlobalFilters($query, $fieldnameWU)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_workunits_ids = array_keys(Yii::$app->session->get('Filter_workunits_ids'));
        if (count($all_workunits_ids) > 0) {
            $query->andWhere([$fieldnameWU => $all_workunits_ids]);
        }
        return $query;
    }

    /**
     * merges in the Workplace global filters in a query
     *
     * @param $query
     * @param $fieldnameWPL
     * @return mixed
     */
    static function mergeWorkplacesGlobalFilters($query, $fieldnameWPL)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_workplaces_ids = array_keys(Yii::$app->session->get('Filter_locations_ids'));
        if (count($all_workplaces_ids) > 0) {
            $query->andWhere([$fieldnameWPL => $all_workplaces_ids]);
        }
        return $query;
    }

    /**
     * merges in the Employer global filters in a query
     *
     * @param $query
     * @param $fieldnameEMP
     * @return mixed
     */
    static function mergeEmployersGlobalFilters($query, $fieldnameEMP)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_employers_ids = array_keys(Yii::$app->session->get('Filter_employers_ids'));
        if (count($all_employers_ids) > 0) {
            $query->andWhere([$fieldnameEMP => $all_employers_ids]);
        }
        return $query;
    }

    /**
     * merges in the Trainings global filters in a query
     *
     * @param $query
     * @param $fieldnameTR
     * @return mixed
     */
    static function mergeTrainingsGlobalFilters($query, $fieldnameTR)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_trainings_ids = array_keys(Yii::$app->session->get('Filter_trainings_ids'));
        if (count($all_trainings_ids) > 0) {
            $query->andWhere([$fieldnameTR => $all_trainings_ids]);
        }
        return $query;
    }
    
    /**
     * merges in the Object groups global filters in a query
     *
     * @param $query
     * @param $fieldnameTR
     * @return mixed
     */
    static function mergeObjectgroupsGlobalFilters($query, $fieldnameTR)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_objectgroups_ids = array_keys(Yii::$app->session->get('Filter_object_groups_ids'));
        if (count($all_objectgroups_ids) > 0) {
            $query->andWhere([$fieldnameTR => $all_objectgroups_ids]);
        }
        return $query;
    }
    
    /**
     * merges in the Object types global filters in a query
     *
     * @param $query
     * @param $fieldnameTR
     * @return mixed
     */
    static function mergeObjecttypesGlobalFilters($query, $fieldnameTR)
    {
        /* @var $query \backend\models\ActionsQuery */
        $all_objecttypes_ids = array_keys(Yii::$app->session->get('Filter_object_types_ids'));
        if (count($all_objecttypes_ids) > 0) {
            $query->andWhere([$fieldnameTR => $all_objecttypes_ids]);
        }
        return $query;
    }
}
