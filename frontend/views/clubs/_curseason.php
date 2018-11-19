<?php

use kartik\grid\GridView;
use common\helpers\ViewsHelper;

$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'member_id',
        'label'     => Yii::t('modelattr', 'Name'),
        'format'    => 'raw',
        'value'     => function($model) {
            return $model->name;
        },
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => ViewsHelper::getMembersList(null, '', ['is_active' => true, 'has_paid' => true]),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true]
        ],
        'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-search-member_id'],
    ],
    [
        'attribute' => 'player_stats_scheduled',
        'value'     => function($model) {
//                    $model->getCoachingCourts();
            return $model->getMemberStats();
        },
        'enableSorting' => true,
    ],
    [
        'attribute' => 'player_stats_played',
        'value'     => function($model) {
            return $model->getMemberStats(['status_id' => 1]);
        },
        'enableSorting' => true,
    ],
    [
        'attribute' => 'token_stats',
        'value'     => function($model) {
            return $model->getMemberStats(['tokens' => true]);
        },
    ],
    [
        'attribute' => 'coaching_stats',
        'value'     => function($model) {
            return $model->getCoachingStats();
        },
    ],
    [
        'attribute' => 'status_stats',
        'value'     => function($model) {
            return $model->getMemberStats(['status_id' => [3, 7]]);
        },
    ],
];

echo GridView::widget([
    'dataProvider'   => $dataProvider,
    'filterModel'    => $searchModel,
    'columns'        => $gridColumn,
    'id'             => 'gridview-members-id',
    'responsive'     => true,
    'responsiveWrap' => true,
    'condensed'      => true,
    'panel'          => [
        'type'        => Gridview::TYPE_DEFAULT,
        'heading'     => 'Player Stats - Current Season',
    ],
]);
