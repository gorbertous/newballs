<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
//use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);
?>

<div class="reserves-index">

    <?php 
    //    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
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

    $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = GridviewHelper::getActionColumn(
        '{view}{update}{delete}',
        $currentBtn);
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
    $toolbar[] = [
    'content' =>
         GridviewHelper::getNewbutton($currentBtn) . ' ' .
         GridviewHelper::getResetgrida($currentBtn)
    ];
    $toolbar[] = GridviewHelper::getExportMenu($dataProvider, $gridColumn);
    $toolbar[] = '{toggleData}';
    
    echo GridView::widget([
                'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                'columns'        => $gridColumn,
                'id' => 'gridview-club-id',
                'responsive'          => true,
                'responsiveWrap' => true,
                'condensed' => true,
                'panelBeforeTemplate' => GridviewHelper::getPanelBefore(),
                'panel' => [
                    'type'    => Gridview::TYPE_DEFAULT,
                    'heading' => $header,
                ],
                'toolbar'             => $toolbar,
                'itemLabelSingle'     => Yii::t('modelattr', 'record'),
                'itemLabelPlural'     => Yii::t('modelattr', 'records'),
                'replaceTags' => [
                    '{lefttoolbar}' => join(' ', $lefttoolbar)
                ],
            ]
        );
    //    Pjax::end();
 ?>
    
</div>
