<?php

use kartik\grid\GridView;
use common\helpers\TraitIndex;
use yii\helpers\ArrayHelper;
use backend\models\Clubs;
use backend\models\Location;
use common\dictionaries\ClubSessions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlayDatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);
?>

<div class="clubs-index">

    <?php $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => 'ID',
            'attribute'      => 'termin_id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
        [
            'attribute'           => 'c_id',
            'label'               => Yii::t('modelattr', 'Club'),
            'value'               => 'club.name',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Clubs::find()
                ->select(['c_id', 'name'])
                ->all(), 'c_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-ID_Clubs'],
        ],
        [
            'attribute'           => 'location_id',
            'label'               => Yii::t('modelattr', 'Location'),
            'value'               => 'location.name',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Location::find()
                ->select(['location_id', 'name'])
                ->all(), 'location_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-ID_Location'],
        ],
       
        'termin_date',
        [
            'attribute' => 'session_id',
            'value' => function($model) {
                return isset($model->session_id) ? ClubSessions::get($model->session_id) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ClubSessions::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-categories'],
        ],
        'courts_no',
        'slots_no',
        
    ];

    $gridColumn[] = TraitIndex::getActionColumn(
        '{view}{update}{delete}',
        $currentBtn);


    $gridParams = [
        'dataProvider'        => $dataProvider,
        'filterModel'         => $searchModel,
        'columns'             => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar'             => [
            ['content' =>
                 TraitIndex::getNewbutton($currentBtn) . ' ' .
                 TraitIndex::getResetgrida($currentBtn)
            ],
        ],
        'exportdataProvider'  => $dataProvider,
        'exportcolumns'       => $gridColumn
    ];

    TraitIndex::echoGridView(
        $gridParams,
        $context_array,
        $currentBtn
    );
    ?>

</div>
