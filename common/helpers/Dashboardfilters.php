<?php

namespace common\helpers;

use Yii;

/**
 * Class Dashboardfilters
 * @package common\helpers
 */
class Dashboardfilters
{
    /**
     * @return mixed
     */
    public static function getDashboardfilters()
    {
        $Dashboardfilter[null] = [];

        $Dashboardfilter['Active'] = [
            'filtertitle' => Yii::t('appMenu', 'Active records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Inactive'] = [
            'filtertitle' => Yii::t('appMenu', 'Inactive records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['NotCompleted'] = [
            'filtertitle' => Yii::t('appMenu', 'Records not completed'),
            'box-color'   => 'bg-red'
        ];

        $Dashboardfilter['Completed'] = [
            'filtertitle' => Yii::t('appMenu', 'Completed records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Verified'] = [
            'filtertitle' => Yii::t('appMenu', 'Verified records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['NotVerified'] = [
            'filtertitle' => Yii::t('appMenu', 'Records not verified'),
            'box-color'   => 'bg-red'
        ];

        $Dashboardfilter['Able'] = [
            'filtertitle' => Yii::t('appMenu', 'Able records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Unable'] = [
            'filtertitle' => Yii::t('appMenu', 'Unable records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Active & Able'] = [
            'filtertitle' => Yii::t('appMenu', 'Active record & Able records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Active & Unable'] = [
            'filtertitle' => Yii::t('appMenu', 'Active records & Unable records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Inactive & Able'] = [
            'filtertitle' => Yii::t('appMenu', 'Inactive records & Able records'),
            'box-color'   => 'bg-blue'
        ];

        $Dashboardfilter['Inactive & Unable'] = [
            'filtertitle' => Yii::t('appMenu', 'Inactive records & Unable records'),
            'box-color'   => 'bg-blue'
        ];

        return $Dashboardfilter;
    }
}
