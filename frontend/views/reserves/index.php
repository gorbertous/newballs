<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
//use yii\widgets\Pjax;
use common\helpers\ViewsHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReservesSearch */
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
            'attribute'      => 'id',
            'contentOptions' => ['style' => 'width:20px;'],
            'visible' => Yii::$app->user->can('team_member'),
        ],
        [
            'attribute'           => 'termin_id',
            'label'               => Yii::t('modelattr', 'Date'),
            'value'               => 'termin.termin_date',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(backend\models\PlayDates::find()
                ->select(['termin_id', 'termin_date'])
                ->where(['c_id' => Yii::$app->session->get('c_id')])
                ->orderBy(['termin_id' => SORT_DESC])
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
            'filter'              => ViewsHelper::getMembersList(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_member'],
        ],
    ];

     $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = GridviewHelper::getActionColumn(
        '{update}{delete}',
        $currentBtn);


    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
    $toolbar[] = [
    'content' =>
         GridviewHelper::getNewbutton($currentBtn) . ' ' .
         GridviewHelper::getResetgrida($currentBtn)
    ];
    $toolbar[] = '{export}';
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
