<?php

use kartik\grid\GridView;
use common\helpers\TraitIndex;
use yii\helpers\ArrayHelper;
use common\dictionaries\OutcomeStatus;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GamesboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';
?>

<div class="games-board-index">

    <?php $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => 'ID',
            'attribute'      => 'id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
        [
            'attribute'           => 'termin_id',
            'label'               => Yii::t('modelattr', 'Date'),
            'value'               => 'termin.termin_date',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(backend\models\PlayDates::find()
                ->select(['termin_id', 'termin_date'])
                ->all(), 'termin_id', 'termin_date'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_termin'],
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Member'),
            'value'               => 'member.name',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(backend\models\Members::find()
                ->select(['member_id', 'firstname', 'lastname'])
                ->all(), 'member_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_member'],
        ],
        'court_id',
        'slot_id',
        [
            'attribute'           => 'status_id',
            'label'               => Yii::t('modelattr', 'Status'),
            'value' => function($model) {
                return isset($model->status_id) ? OutcomeStatus::get($model->status_id) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => OutcomeStatus::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_status'],
        ],
//        'fines',
        [
            'attribute' => 'tokens',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->tokens == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'late',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->late == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        
        
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
