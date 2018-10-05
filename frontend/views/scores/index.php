<?php

use kartik\grid\GridView;
use common\helpers\TraitIndex;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);
?>

<div class="reserves-index">

    <?php $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => 'ID',
            'attribute'      => 'score_id',
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
        'court_id',
        'set_one',
        'set_two',
        'set_three',
        'set_four',
        'set_five',
        [
            'attribute'     => 'createUserName',
            'enableSorting' => false,
            'format'        => 'raw'
        ],
        [
            'attribute'     => 'updateUserName',
            'enableSorting' => false,
            'format'        => 'raw'
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
